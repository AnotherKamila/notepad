<?php
/**
 * template for the main content -- basically includes the generated file
 */
?>
<section id="content">

<?php if (!isset($html) || $html === NULL) : ?>
	<p class="error">Could not generate HTML!</p>
<?php else : ?>
	<?php echo $html; ?>

	<?php if (substr($path, -MD_EXT_LEN, strlen($path)) === MD_EXT) { // if we are displaying a file not index
		echo '<aside>view MarkDown source by appending &#145;.text&#146; to URL</aside>';
	} ?>

<?php endif; ?>

</section><!-- #content -->
