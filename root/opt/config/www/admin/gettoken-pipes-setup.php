<?php
include('../../../opt/php/spyc.php');
$adv_file = "../../cfg/advanced.yaml";

if(isset($_POST['plex_username']) && isset($_POST['plex_password'])) {

$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "/config/logs/plex_token_errors.log", "a") // stderr is a file to write to
);
$process = proc_open('ruby /usr/local/sbin/gettoken-pipes', $descriptorspec, $pipes);

	if (is_resource($process)) {
		$userpass = strip_tags($_POST['plex_username']) . '|+/+/+|' . strip_tags($_POST['plex_password']);
		fwrite($pipes[0], $userpass);
		fclose($pipes[0]);

		$stream = stream_get_contents($pipes[1]);
		$stream = str_replace("\n", '', $stream);
		$stream = explode(" ",$stream);
		$token = $stream[0];
		$avatar = isset($stream[1]) ? $stream[1] : "";
		fclose($pipes[1]);
		proc_close($process);
	}
	
	$adv_array = Spyc::YAMLLoad('../../cfg/advanced.yaml');
	$adv_array['token'] = array('api_key' => $token);
	
	# d/l gravatar avatar
	exec("wget $avatar -O /config/www/img/avatar.png");

	$adv_yaml = Spyc::YAMLDump($adv_array,2,0);
	file_put_contents($adv_file, $adv_yaml);
	
	if (!empty($token)) {
		$statustext = "Plex Token Saved!";
		$tokenarray = array( 'token' => $token, 'statustext' => $statustext);
		echo json_encode($tokenarray);
		exit;
	}
	else {
		$statustext = "Error: Verify username/password!";
		$tokenarray = array( 'statustext' => $statustext);
		echo json_encode($tokenarray);
		exit;
	}
}
echo "Check username/password!";
exit;
?>
