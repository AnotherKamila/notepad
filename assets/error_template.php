<?php
/**
 * error page for my pretty notepad
 */
?>
<h1>Nope, I won't eat this...</h1>
<h2>Either the path is invalid, or the file does not exist.</h2>
<p>You asked for: <?php echo htmlentities($_GET['p']); ?></p>
<hr />
<div class="cf">
	<section>
		<h1>Looking for something?</h1>
		<p><mark>TODO</mark> search</p>
	</section>
	<section>
		<h1>Trying to find a security flaw in my pretty notepad?</h1>
		<p>Go ahead! Here is the source code.<mark>TODO link</mark></p>
	</section>
</div>
