<?php
if (isset($_POST['changepass'])) {
	# change password
	if(!empty($_POST['ns_username']) && !empty($_POST['ns_password'])) {
		# Write ns user/pass to secure.php
  		$user = $_POST['ns_username'];
  		$pass = $_POST['ns_password'];
  		exec("sed -i \"10s/.*/\'$user\\' => \'$pass\'/\" /config/cfg/secure.php");
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

