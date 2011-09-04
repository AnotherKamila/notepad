<?php
/**
 * template for the navigation bar on top
 */
?>
<nav>

<?php
	$path = substr($path, strlen(NOTEPAD_ROOT . '/' . CONTENT_DIR . '/'));
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
?>

<?php if ($IS_FILE) : ?>
	<aside>view MarkDown source by appending &#145;.text&#146; to URL</aside>
<?php endif; ?>

	<a class="about" href="<?php echo NOTEPAD_ROOT_URL; ?>/about/" title="About My Pretty Notepad">?</a>
</nav>
