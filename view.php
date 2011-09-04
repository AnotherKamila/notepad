<?php
/**
 * welcome to my pretty notepad ^_^
 *
 * FIXME no, the above is not a good docstring
 */

ob_start('ob_gzhandler'); // compresses buffer only if client supports it

require('notepad.php');

$path = getRealPath($_GET['p']);

if ($path) {
	$vars = array('path' => $path);
	renderTemplate('head', $vars);
	renderTemplate('nav_bar', $vars);
	Display($path);
} else { // handle invalid paths
	$vars = array('title' => 'Path Invalid', 'path' => NOTEPAD_ROOT_URL);
	renderTemplate('head', $vars);
	renderTemplate('nav_bar', $vars);
	renderTemplate('error', array('p' => $_GET['p']));
}
renderTemplate('foot');

ob_end_flush();

?>
