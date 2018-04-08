<?php
/*
	@name 			Server Logs Viewer
	@description 	Emulates the tail() function. View the latest lines of your LAMP server logs in your browser.
	@author 		Alexandre Plennevaux (pixeline.be)
	@team			Oleg Basov (olegeech@sytkovo.su)
*/

define('LOG_PATH', '/config/logs/');
define('DISPLAY_REVERSE', true); // true = displays log entries starting with the most recent
//define('DIRECTORY_SEPARATOR', '/');
defined('DIRECTORY_SEPARATOR') or define('DIRECTORY_SEPARATOR', '/');

$log = (!isset($_GET['p'])) ? $default : urldecode($_GET['p']);
$lines = (!isset($_GET['lines'])) ? 25 : $_GET['lines'];
$files = get_log_files(LOG_PATH);

ksort($files);
foreach ($files as $dir_name => $file_array) {
	ksort($file_array);
}
$filename = $log;
$title = substr($log, (strrpos($log, '/')+1));
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NowShowing Logs</title>
  <meta name="description" content="PHP Script that presents your Server Logs in an easy to use layout.">
  <link rel="stylesheet" href="../css/pure-min.css">
  <style type="text/css" media="screen">

 body {
    color: #e6e6e6;
	background-color:#1f1f1f;
}
pre {
	font-size:14px;font-family:monospace;color:#e6e6e6;line-height: 1;
	white-space:pre-wrap;
	background-color:#1f1f1f;
	border: none;
}
/*
Add transition to containers so they can push in and out.
*/
#layout,

/*
This is the parent `<div>` that contains the menu and the content area.
*/
#layout {
    position: relative;
    padding-left: 0;
}
    #layout.active {
        position: relative;
        left: 200px;
    }
/*
The content `<div>` is where all your content goes.
*/
.content {
    margin: 0 auto;
    padding: 0 2em;
    max-width: 800px;
    margin-bottom: 50px;
    line-height: 1.6em;
}

.header {
     margin: 0;
     color: #e6e6e6;
     text-align: center;
     padding: 2.5em 2em;
     /* border-bottom: 1px solid #eee; */
 }
    .header h1 {
        margin: 0.2em 0;
        font-size: 3em;
        font-weight: 300;
    }
     .header h2 {
        font-weight: 300;
        color: #e6e6e6;
        padding: 0;
        margin-top: 0;
    }

.content-subhead {
    margin: 50px 0 20px 0;
    font-weight: 300;
    color: #888;
}

/*
Hides the menu at `48em`, but modify this based on your app's needs.
*/
@media (min-width: 48em) {

    .header,
    .content {
        padding-left: 2em;
        padding-right: 2em;
    }
}
.truncate {
  width: 100px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
ol{
	list-style: decimal-leading-zero;
	list-style-position: outside;
}
ol li{
	border-bottom:1px solid #DDD;
	color:#e6e6e6;
	font-weight: 100;
	font-size:12px;
}
ol li:last-child{
		border-bottom:0px solid #DDD;
}
ol li pre{
	height:auto;
	overflow: visible;
}
.pure-form select {
	color:#e6e6e6;
	background-color:#404040;
}

  </style>
</head>

<body>
<div id="layout">
    <div id="main">
        <div class="header">
            <h3><?php echo $title;?></h3><br>
            <?= (!empty($filename)) ? '<h5 style="font-weight:normal;"><b>[</b> Displaying the last '. $lines. ' lines <b>]</b></h5>': ''; ?>
            
			<!--
	        <form action="" method="get" class="pure-form pure-form-aligned">
			<input type="hidden" name="p" value="<?php echo $log ?>">
			<label>How many lines to display?
			<select name="lines" onchange="this.form.submit()">
				<option value="10" <?php echo ($lines=='10') ? 'selected':'' ?>>10</option>
				<option value="50" <?php echo ($lines=='50') ? 'selected':'' ?>>50</option>
				<option value="100" <?php echo ($lines=='100') ? 'selected':'' ?>>100</option>
				<option value="500" <?php echo ($lines=='500') ? 'selected':'' ?>>500</option>
				<option value="1000" <?php echo ($lines=='1000') ? 'selected':'' ?>>1000</option>
			</select>
			</label>
	</form>
	-->
        </div>

        <div class="content">

<ol reversed>
<?php
$output = tail($filename, $lines);

if ($output){
	$output = explode("\n", $output);
	if(DISPLAY_REVERSE){
		// Latest first
		$output = array_reverse($output);
	}
	$output = implode('</pre><li><pre>', $output);
	echo $output;
} else{
?>
        	<ul>

        	<?php show_list_of_files($files, $lines); ?>


			</ul>
	<?php
}


?>
</ol>
</div>
    </div>
</div>
	</body>
</html>
<?php

function get_log_files($dir, &$results = array()) {

	$files = scandir($dir);
	if($files){
		foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);

			if(!is_dir($path)) {
				$files_list[] = $path;
			}
			elseif ($value != "." && $value != "..") {
				$dirs_list[] = $path;
			}
		}

		foreach ($files_list as $path) {
			preg_match("/^.*\/(\S+)$/", $path, $matches);
			$name = $matches[1];
			$results[$dir][$name] = array('name' => $name, 'path' => $path);
		}
		if (isset($dirs_list)) {
		  if(count($dirs_list)>0){
			  foreach ($dirs_list as $path) {
				  get_log_files($path, $results);
			  }
		    }
	    }
		return $results;
	}
	return false;
}
function tail($filename, $lines = 50, $buffer = 4096) {
        // Check if allowed
        global $files;
        $dir = dirname($filename);
        $file = basename($filename);
        if (!isset($files[$dir . DIRECTORY_SEPARATOR]) || $files[$dir . DIRECTORY_SEPARATOR][$file]["path"] != $filename) {
			return "Not allowed";
        }
	// Open the file
	if(!is_file($filename)){
		return false;
	}
	$f = fopen($filename, "rb");
	if(!$f){
		return false;
	}
	
	// Jump to last character
	fseek($f, -1, SEEK_END);

	// Read it and adjust line number if necessary
	// (Otherwise the result would be wrong if file doesn't end with a blank line)
	if(fread($f, 1) != "\n") $lines -= 1;

	// Start reading
	$output = '';
	$chunk = '';

	// While we would like more
	while(ftell($f) > 0 && $lines >= 0)
	{
		// Figure out how far back we should jump
		$seek = min(ftell($f), $buffer);

		// Do the jump (backwards, relative to where we are)
		fseek($f, -$seek, SEEK_CUR);

		// Read a chunk and prepend it to our output
		$output = ($chunk = fread($f, $seek)).$output;

		// Jump back to where we started reading
		fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

		// Decrease our line counter
		$lines -= substr_count($chunk, "\n");
	}

	// While we have too many lines
	// (Because of buffer size we might have read too many)
	while($lines++ < 0)
	{
		// Find first newline and remove all text before that
		$output = substr($output, strpos($output, "\n") + 1);
	}

	// Close file and return
	fclose($f);
	return $output;
}

function show_list_of_files($files, $lines  = 50){
	if(empty($files)){
		return false;
	}
}