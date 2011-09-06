<?php
/**
 * supporting functions for my pretty notepad
 *
 * @author Kamila Souckova <kamila@vesmir.sk>
 */

define('MD_EXT', '.text');
define('MD_EXT_LEN', strlen(MD_EXT));

define('MARKDOWN_CMD', '/usr/share/multimarkdown/bin/MultiMarkdown.pl');

define('NOTEPAD_ROOT', realpath(substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/'))));
define('NOTEPAD_ROOT_URL', dirname($_SERVER['PHP_SELF']));

define('CONTENT_DIR', 'content');

define('LOCAL_LINKS_PREFIX', 'np::'); // markdown files can contain links in the form of
									  // [something](np::path/to/otherfile#heading-id),
									  // which will be substituted to point to the
									  // correct note/folder inside notepad

/**
 * takes a path representation like in the URL, and returns the appropriate absolute path
 *
 * as path is input, we absolutely need to make sure it does not do anything evil
 * (like pointing outside my notepad, or a pretty `; something_horrendous')
 *
 * we also want to get something neater than `./something//../something///something'
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

	// create the HTML if needed
	if (!file_exists($html_path) || (filemtime($html_path) < filemtime($src_path))) {
		$command = MARKDOWN_CMD . ' ' . escapeshellarg($src_path) . ' > ' . escapeshellarg($html_path);
		exec($command);
	}
	if (!file_exists($html_path)) {
		$html_path = NULL; // this is to be handled inside template
	}

	$vars = array('html' => $html_path, 'path' => $path);

	if (is_dir($path)) { // index directory if needed {{{
		$listing = array();
		$dir = opendir($path);
		if (!$dir) {
			$listing = NULL;
		} else {
			while ($f = readdir($dir)) {
				if ($f[0] !== '.') {
					if (filetype($path . '/' . $f) === 'dir') {
						$listing[] = array('name' => $f . '/', 'class' => 'dir');
					}
					else if ($f === 'index' . MD_EXT) {
						$listing[] = array('name' => substr($f, 0, -MD_EXT_LEN), 'class' => 'file index');
					}
					else if (substr($f, -MD_EXT_LEN) === MD_EXT) {
						$listing[] = array('name' => substr($f, 0, -MD_EXT_LEN), 'class' => 'file');
					}
				}
			}
			closedir($dir);
			sort($listing);
		}

		$vars['index'] = $listing;
	} // }}}
		
	ob_start('ob_content_postprocess');
	renderTemplate('content', $vars);
	ob_end_flush();
}

/**
 * post-processes the content part (so far only handles np::something links)
 */
function ob_content_postprocess($buffer) {
	$buffer = preg_replace('/\b' . LOCAL_LINKS_PREFIX . '/', NOTEPAD_ROOT_URL, $buffer);
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
