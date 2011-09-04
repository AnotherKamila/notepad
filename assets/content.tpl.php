<?php
/**
 * template for the main content
 *
 * FIXME this file looks really ugly, and not really template-y
 */
?>
<section id="content">

<?php
if (!file_exists($html)) {
	echo '<p class="error">Could not generate HTML.</p>';
} else {
	include($html);
}
?>

<?php if (is_dir($path)) : // index directory if needed ?>
	<section id="dir-index">
		<h1>In here:</h1>
	<?php
	$dir = opendir($path);
	if (!$dir) {
		echo '<p class="error">Cannot open directory for listing.</p>';
	} else {
		echo '<ul>';

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

		$here_url = NOTEPAD_ROOT_URL . '/' . substr($path, strlen(NOTEPAD_ROOT . '/' . CONTENT_DIR . '/'), strlen($path));
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
	?>
	</section><!-- #dir-index -->
<?php endif; ?>

</section><!-- #content -->
