<?php
if (isset($_POST['changepass'])) {
	# change password
	if(!empty($_POST['ns_username']) && !empty($_POST['ns_password'])) {
		# Write ns user/pass to secure.php - Escape single quotes and backslashes
  		$user = addcslashes($_POST['ns_username'], "\'");
  		$pass = addcslashes($_POST['ns_password'], "\'");
		$login_file = "/config/cfg/secure.php";
        $lines = file($login_file, FILE_IGNORE_NEW_LINES);
        $lines[9] = "'$user' => '$pass'";
        file_put_contents($login_file , implode("\n", $lines));
  	}
	else {
		echo "Fill in username/password fields";
	}     
  
  # delete cookie and logout
  setcookie("NowShowing", '', -1, '/');
  header("Location: http://".$_SERVER['HTTP_HOST']."/admin");
  exit;
}
exit;
?>
