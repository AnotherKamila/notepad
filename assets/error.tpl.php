<?php
/**
 * error page for my pretty notepad
 */
?>
<section id="content" class="error">
	<h1>Nope, I won't eat this...</h1>
	<h2>The file/folder does not exist.</h2>
	<p><span style="color: #666;">You asked for:</span> <?php echo htmlentities($p); ?></p>
	<hr />
	<div class="cf">
		<section>
			<h1>Looking for something?</h1>
			<p><mark>TODO</mark> search</p>
		</section>
		<section>
			<h1>Trying to find a security flaw in my pretty notepad?</h1>
			<p>Go ahead! <a title="AnotherKamila/notepad on GitHub" href="http://github.com/AnotherKamila/notepad">Here is the source code</a>.</p>
		</section>
	</div>
</section><!-- #content -->
