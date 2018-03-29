<?php
# check for ondemand submit
if (isset($_POST['ondemand_report'])) {

$command = "/usr/local/sbin/ondemand_report";
 
# run ondemand report
exec("$command", $output, $return);
	if($return !== 0) {
		echo "report failed";	
	}
	else {
		echo "report completed";
	}
}
?>

