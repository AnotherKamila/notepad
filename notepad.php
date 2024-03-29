<?php
/**
 * supporting functions for my pretty notepad
 *
 * @author Kamila Souckova <kamila@vesmir.sk>
 */

require_once('npconf.php');

define('NOTEPAD_ROOT', realpath(substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/'))));
define('NOTEPAD_ROOT_URL', dirname($_SERVER['PHP_SELF']));

/* my deepest admiration and thanks goes to http://michelf.com/projects/php-markdown/ */
include_once('phpmd/markdown.php');

/**
 * takes a path representation like in the URL, and returns the appropriate absolute path
 *
 * as path is input, we absolutely need to make sure it does not do anything evil
 * (like pointing outside my notepad)
 *
 * we also prefer to get something neater than `./something//../something///something'
 *
 * @param string $path   the path to be checked/normalized
 * @returns string|bool  the normalized absolute path, or false if input was invalid
 */
function getRealPath($path) {
	$path = CONTENT_DIR . '/' . $path;

	// first append extension if needed, so that realpath() does not fail because of
	//  nonexistent files
	if (!is_dir($path)) {
		$path .= MD_EXT;
	}

	$path = realpath($path);

	if (substr($path, 0, strlen(NOTEPAD_ROOT)) === NOTEPAD_ROOT) { // we are displaying something from my notepad, yay!
		return $path;
	} else { // we don't want people to look into other parts of the filesystem
		return false;
	}
}

/**
 * displays the markdown file pointed to by $path as HTML according to the 
 * 'content' template, creating the HTML from .text if needed
 *
 * @param string $path path to the file being displayed
 *
 * expects the path to point to something real and safe, does not do any checking by
 * itself
 */
function Display($path) {
	// first determine if we are accessing a dir or a file
	if (is_dir($path)) {
		$src_path = $path . '/index' . MD_EXT;
	} else {
		$src_path = $path;
	}
	
	$html_path = substr($src_path, 0, -MD_EXT_LEN) . '.html';

	$html = Markdown(file_get_contents($src_path));

	ob_start('ob_content_postprocess');
	renderTemplate('content', array('html' => $html, 'path' => $path));
	ob_end_flush();

	if (is_dir($path)) { // index directory if needed {{{
		$listing = array();
		$dir = opendir($path);

		if (!$dir) {
			$listing = NULL;
		} else {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			while ($f = readdir($dir)) {
				if ($f[0] !== '.') {
					$thisfile = array();

					if (substr($f, -MD_EXT_LEN) === MD_EXT) { // handle markdown files {{{
						$thisfile['name'] = substr($f, 0, -MD_EXT_LEN);
						$thisfile['class'] = 'note';
						if ($f === 'index' . MD_EXT) {
							$thisfile['class'] = 'note index';
						}
					}
					// }}}
					else { // handle other files based on mime-type {{{
						switch (finfo_file($finfo, $path . '/' . $f)) {
						case "directory":
							$thisfile['name'] = $f . '/';
							$thisfile['class'] = 'dir';
							break;
						case "image/jpeg":
						case "image/png":
						case "image/gif":
							$thisfile['name'] = $f;
							$thisfile['class'] = 'image';
							break;
						case "application/x-httpd-php":
						case "text/x-php":
							break;
						default:
							$thisfile['name'] = $f;
						}
					} // }}}
					$thisfile['mtime'] = filemtime($path . '/' . $f);

					if (isset($thisfile['name']) && $thisfile['name'] !== '') {
						$listing[] = $thisfile;
					}
				}
			}
			closedir($dir);

			// sort by date then alphabetically
			foreach($listing as $key => $row) {
				$mtime[$key] = $row['mtime'];
				$name[$key] = $row['name'];
			}
			array_multisort($mtime, SORT_DESC, $name, SORT_ASC, $listing);
			// }}}
		}

		renderTemplate('dir-index', array('index' => $listing, 'path' => $path));
	} // }}}
}

/**
 * post-processes the content part
 */
function ob_content_postprocess($buffer) {
	// markdown files can contain links in the form of
	// [something](np::path/to/otherfile#heading-id), which will be substituted
	// to point to the correct note/folder inside notepad
	$LOCAL_LINKS_PREFIX = 'np::';
	$buffer = preg_replace('/\b' . $LOCAL_LINKS_PREFIX . '/', NOTEPAD_ROOT_URL . '/', $buffer);

	// things marked img::{something} will be exchanged for the needed HTML, and
	// then parsed using the websequencediagrams.com JS service
	$WSD_PREFIX = 'img::';
	$buffer = preg_replace('/\b' . $WSD_PREFIX . '{([^}]*)}/', '<div class="wsd" wsd_style="napkin"><pre>\\1</pre></div><script type="text/javascript" src="http://www.websequencediagrams.com/service.js"></script>', $buffer);

	return $buffer;
}

/**
 * renders a part of the notepad using a template file
 *
 * @param string $name name of template (stored as NOTEPAD_ROOT/assets/$name.tpl.php)
 * @param string $variables variables which the template will want to access
 *
 * stolen from Drupal: http://api.drupal.org/api/drupal/includes--theme.inc/function/theme_render_template
 */
function renderTemplate($name, $variables = NULL) {
	if ($variables) {
		extract($variables, EXTR_SKIP);
	}
	include('assets/' . $name . '.tpl.php');
}
