<?php
/**
 * welcome to my pretty notepad ^_^
 *
 * FIXME no, the above is not a good docstring
 */

require('notepad.php');

$path = getRealPath($_GET['p']);

if ($path) {
	// title stuff {{{
	if ($path === NOTEPAD_ROOT . '/' . CONTENT_DIR) {
		$title = '~';
	} else {
		$title = basename($path);
		if ($title === '') { // for folders
			$title = substr($path, -strrpos($path, '/'));
		}
		if (substr($title, -MD_EXT_LEN) === MD_EXT) {
			$title = substr($title, 0, -MD_EXT_LEN);
		}
	}
	// }}}
	writeHead($title);
	writeNavBar($path);
	echo '<section id="content">';
	Display($path);
	echo '</section>';
} else { // handle invalid paths
	writeHead('Path Invalid');
	writeNavBar(NOTEPAD_ROOT_URL);
	echo '<section id="content" class="error">';
	include(NOTEPAD_ROOT . '/assets/error_template.php');
	echo '</section>';
}

writeFoot();
?>
