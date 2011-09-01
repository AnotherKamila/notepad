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
define('NOTEPAD_ROOT_URL', substr(NOTEPAD_ROOT, strlen($_SERVER['DOCUMENT_ROOT'])));

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
	$path = 'content/' . $path;

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
 * displays a pretty directory index of markdown files and subdirs in the given 
 * directory
 *
 * @param string $dir path to the directory being indexed
 *
 * expects the path to be a valid path to a directory, having passed thru 
 * getRealPath()
 */
function writeDirIndex($path) {
	echo '<ul id="dir-index">';
	echo '<h1>In here:</h1>';

	$dir = opendir($path);
	if (!$dir) {
		echo '<p class="error">Cannot open directory for listing.</p>';
		return;
	}
	$listing = array();
	while ($f = readdir($dir)) {
		if ($f[0] !== '.') {
			if ((filetype($path . '/' . $f) === 'dir') || (substr($f, -MD_EXT_LEN) === MD_EXT)) {
				$listing[] = $f;
			}
		}
	}
	closedir($dir);
	sort($listing);

	$here_url = NOTEPAD_ROOT_URL . '/' . substr($path, strlen(NOTEPAD_ROOT . '/content/'), strlen($path));
	foreach ($listing as $f) {
		if ($f === 'index' . MD_EXT) {
			$class = 'index file';
		} else {
			$class = filetype($path . '/' . $f);
		}
		if (filetype($path . '/' . $f) === 'file') {
			$f = substr($f, 0, -MD_EXT_LEN);
		} else {
			$f .= '/';
		}
		echo '<li><a class="' . $class . '" href="' . $here_url . '/' . $f . '">' . htmlentities($f) . '</a></li>';
	}

	echo '</ul>';
}

/**
 * displays the markdown file pointed to by $path as HTML
 *
 * if $path is a directory, it displays $path/index.text, and calls writeDirIndex
 *
 * @param string $path path to the file being displayed
 *
 * expects the path to point to something real and IT MUST BE SAFE, does not do any 
 * checking by itself
 * we reallu don't want anyone to pass a 'something; do_evil_stuff' as $path, so *make
 * sure* to check what is being passed here
 */
function Display($path) {
	$INDEX = false; // do we need to append a directory index?
	// first determine if we are accessing a dir or a file
	if (is_dir($path)) {
		$path .= '/index' . MD_EXT;
		$INDEX = true;
	}
	
	$html_path = substr($path, 0, -MD_EXT_LEN) . '.html';

	// create the HTML if needed
	if (!file_exists($html_path) || (filemtime($html_path) < filemtime($path))) {
		$command = MARKDOWN_CMD . ' ' . $path . ' > ' . $html_path;
		exec($command); // we are safe here as long as $path is what we think it is, which it is in case of my pretty notepad
	}
	if (!file_exists($html_path)) {
		echo '<p class="error">Could not generate HTML for ' . basename($path) . '.</p>';
	} else {
		include($html_path);
	}

	// index directory if needed
	if ($INDEX) {
		writeDirIndex(dirname($path));
	}
}

/**
 * renders the path with clickable components inside a <nav> element
 *
 * @param string $path the path to be turned into nav
 *
 * @expects path to look proper, i.e. have been put through realpath()
 */
function writeNavBar($path) {
	echo '<nav>';

	$path = substr($path, strlen(NOTEPAD_ROOT . '/content/'));
	$IS_FILE = false;
	if (substr($path, -MD_EXT_LEN, strlen($path)) === MD_EXT) {
		$IS_FILE = true;
		$path = substr($path, 0, -MD_EXT_LEN);
	}
	$p = NOTEPAD_ROOT_URL;
	echo '<a href="' . $p . '">~</a>';
	foreach (explode('/', $path) as $component) {
		// we don't want to render any empty components
		if ($component === '') {
			continue;
		}
		echo '/';
		$p .= '/' . $component;
		echo '<a href="' . $p . '/' . '">' . htmlentities($component) . '</a>';
	}

	if ($IS_FILE) {
		echo '<aside>view MarkDown source by appending &#145;.text&#146; to URL</aside>';
	}

	echo '<a class="about" href="' . NOTEPAD_ROOT_URL . '/about">?</a>';
	echo '</nav>';
}

/**
 * writes all up to the <body> tag, setting the 1st part of title to the argument
 *
 * @param string $title self-explanatory
 */
function writeHead($title) {
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?php echo htmlentities($title); ?> | Kamila's Pretty Notepad</title>
		<meta name="author" content="Kamila Souckova" />
		<meta name="contact" content="kamila@vesmir.sk" />

		<link rel="stylesheet" media="all" href="<?php echo NOTEPAD_ROOT_URL; ?>/assets/all.css" />

		<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
	</head>
	<body>
<?php
}

/**
 * writes the footer and closes <body> and <html>
 */
function writeFoot() {
?>
	<footer>
		<a href="http://fletcherpenney.net/multimarkdown/">mmd</mark></a>
		|
		<a rel="license" href="<?php echo NOTEPAD_ROOT_URL; ?>/about/PIRATEME"><img alt="Public Domain -- Please Pirate" style="border:0" src="<?php echo NOTEPAD_ROOT_URL; ?>/assets/pd.png" /></a>
		| 
		by <a href="http://people.ksp.sk/~kamila/" rel="author">me</a>
	</footer>
	</body>
</html>
<?php
}
?>
