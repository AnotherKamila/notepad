<?php
/**
 * template for the navigation bar on top
 */
?>
<nav>

<?php
$path = substr($path, strlen(NOTEPAD_ROOT . '/' . CONTENT_DIR . '/'));
if (substr($path, strlen($path)-MD_EXT_LEN) === MD_EXT) {
	$path = substr($path, 0, strlen($path)-MD_EXT_LEN);
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
	echo '<a href="' . $p . '">' . htmlentities($component) . '</a>';
}
?>

	<?php include('search_form.tpl.php'); ?>

	<!--<a class="about" href="<?php echo NOTEPAD_ROOT_URL; ?>/about/" title="About My Pretty Notepad">?</a>-->
</nav>
