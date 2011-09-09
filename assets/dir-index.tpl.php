<?php
/**
 * template for the directory index
 *
 * expects to get the index in $listing
 */
?>
<section id="dir-index">

<?php if ($index === NULL) : ?>
	<p class="error">Cannot open directory for listing.</p>
<?php else : ?>
	<ul>
		<h1>In here:</h1>

<?php $here_url = NOTEPAD_ROOT_URL . '/' . substr($path, strlen(NOTEPAD_ROOT . '/' . CONTENT_DIR . '/'), strlen($path)); ?>

<?php foreach ($index as $f) : ?>
<li>
	<a class="<?php echo $f['class']; ?>" href="<?php echo $here_url . '/' . $f['name']; ?>">
		<span class="fname"><?php echo htmlentities($f['name']); ?></span>
		<span class="fmtime"><?php echo date('l, M j<\s\up>S</\s\up> Y g:ia', $f['mtime']); ?></span>
	</a>
</li>
<?php endforeach; ?>

	</ul>
<?php endif; /* $index !== NULL */ ?>

</section><!-- #dir-index -->
