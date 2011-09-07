<?php
/**
 * template for the main content
 */
?>
<section id="content">

<?php if (!isset($html) || $html === NULL) : ?>
	<p class="error">Could not generate HTML!</p>
<?php else : ?>

	<?php include($html); ?>

	<?php if (isset($index)) : ?>
		<section id="dir-index">
		<?php if ($index === NULL) : ?>
			<p class="error">Cannot open directory for listing.</p>
		<?php else : ?>
			<ul>
				<h1>In here:</h1>

<?php $here_url = NOTEPAD_ROOT_URL . '/' . substr($path, strlen(NOTEPAD_ROOT . '/' . CONTENT_DIR . '/'), strlen($path)); ?>
<?php foreach ($index as $f) : ?>
	<li><a class="<?php echo $f['class']; ?>" href="<?php echo $here_url . '/' . $f['name']; ?>"><?php echo htmlentities($f['name']); ?><span class="filectime"><?php echo date('l, F j<\s\up>S</\s\up> Y', $f['ctime']); ?></span></a></li>
<?php endforeach; ?>

			</ul>
		<?php endif; /* $index !== NULL */ ?>
		</section><!-- #dir-index -->
	<?php endif; /* isset($index) */ ?>

<?php endif; ?>

</section><!-- #content -->
