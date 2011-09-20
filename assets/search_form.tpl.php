<?php
/*
 * search form for my notepad
 */
?>
<form id="search-form" action="<?php echo NOTEPAD_ROOT_URL; ?>/search.php" method="get">
	<input type="search" id="q" name="q" placeholder="type & press Enter to search" size="27"></input>
	<input type="submit" id="qsubmit" value="" />
</form>
