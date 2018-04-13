<?php
include('../../../opt/php/spyc.php');
$adv_file = "../../cfg/advanced.yaml";

if(isset($_POST['tautulliCheck'])) {

$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "/config/logs/nowshowing.log", "a") // stderr is a file to write to
);
$process = proc_open('ruby /usr/local/sbin/tautulli_check', $descriptorspec, $pipes);

	if (is_resource($process)) {
		$stream = stream_get_contents($pipes[1]);
		$stream = str_replace("\n", '', $stream);
		$testy = isset($stream[0]) ? $stream[0] : "failed";
		fclose($pipes[1]);
		proc_close($process);
	}
			
	if ($testy == "s") {
        	echo "Successful!";
		exit;
	}
	elseif ($testy == "e") {
		echo "Check API key!";
		exit;
	}
	else {
		echo "Failed!";
		exit;
	}
}
echo "Error: Could not divide by zero!";
exit;
?>
