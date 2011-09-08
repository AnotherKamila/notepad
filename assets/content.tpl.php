<?php
/**
 * template for the main content -- basically includes the generated file
 */
?>
<section id="content">

<?php if (!isset($html) || $html === NULL) : ?>
	<p class="error">Could not generate HTML!</p>
<?php else : ?>
	<?php include($html); ?>
<?php endif; ?>

</section><!-- #content -->
