<?php
/**
 * template for all up to the <body> tag
 */

// if we don't have a title, get it from $path if set
if (!isset($title) && isset($path)) {
	if ($path === NOTEPAD_ROOT . '/' . CONTENT_DIR) {
		$title = '~';
	} else {
		$title = basename($path);
		if ($title === '') { // for folders
			$title = substr($path, -strrpos($path, '/'));
		}
		if (substr($title, -MD_EXT_LEN) === MD_EXT) { // for files
			$title = substr($title, 0, -MD_EXT_LEN);
		}
	}
}

if (isset($title)) {
	$title .= ' // Kamila\'s Pretty Notepad';
} else {
	$title = 'Kamila\'s Pretty Notepad';
}

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?php echo htmlentities($title); ?></title>
		<meta name="author" content="Kamila Souckova" />
		<meta name="contact" content="kamila@vesmir.sk" />

		<link rel="stylesheet" media="all" href="<?php echo NOTEPAD_ROOT_URL; ?>/assets/all.min.css" />

		<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
	</head>
	<body>
