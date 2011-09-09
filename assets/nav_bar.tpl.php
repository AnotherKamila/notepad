<?php
/**
 * template for the navigation bar on top
 */
?>
<nav>

<?php
$path = substr($path, strlen(NOTEPAD_ROOT . '/' . CONTENT_DIR . '/'));
$path = pathinfo($path, PATHINFO_FILENAME);
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
?>

	<a class="about" href="<?php echo NOTEPAD_ROOT_URL; ?>/about/" title="About My Pretty Notepad">?</a>
</nav>
