<?php
if (isset($_POST['reset'])) {
	
  # Reset to Default

  copy('/opt/config/cfg/advanced.yaml', '/config/cfg/advanced.yaml');
  copy('/opt/config/cfg/secure.php', '/config/cfg/secure.php');
  copy('/opt/config/www/admin/gettoken-pipes-setup.php', '/config/www/admin/gettoken-pipes-setup.php');
  copy('/opt/config/www/admin/index.html', '/config/www/admin/index.html');
  copy('/opt/config/www/admin/save_setup.php', '/config/www/admin/save_setup.php');
   
  # Update password with another random one
  require '/opt/php/randomPass.php';

  setcookie("NowShowing", '', -1, '/');
  header("Location: http://".$_SERVER['HTTP_HOST']."/admin");
  exit;
}
exit;
?>

