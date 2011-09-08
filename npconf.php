<?php
/**
 * conf.php -- the configuration file for my notepad
 */

/* the extension you want to use for markdown files */
define('MD_EXT', '.text');
define('MD_EXT_LEN', strlen(MD_EXT));

/* the subdir for content -- also needs to be edited in .htaccess */
define('CONTENT_DIR', 'content');

/* for correct mtime display */
date_default_timezone_set('Europe/Bratislava');
