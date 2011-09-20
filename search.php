<?php
/**
 * search page for my pretty notepad
 *
 * TODO this repeats a lot of stuff from view.php, should be merged probably
 */

ob_start('ob_gzhandler');

require('notepad.php');

$vars = array('title' => 'Search for: ' . $_GET['q'], 'path' => NOTEPAD_ROOT_URL);
renderTemplate('head', $vars);
renderTemplate('nav_bar', $vars);
renderTemplate('search_results', $vars);
renderTemplate('foot');

ob_end_flush();

?>
