<?php
include('spyc.php');

if(!empty($_POST['ns_username']) && !empty($_POST['ns_password'])) {
  $adv_file = "/config/cfg/advanced.yaml";
  $adv_array = Spyc::YAMLLoad($adv_file);
  
  # Write ns user/pass to secure.php
  $user = $_POST['ns_username'];
  $pass = $_POST['ns_password'];
  $provider = strip_tags($_POST['email_provider']);
  
  #system('ls '.escapeshellarg($dir));
  exec("sed -i \"10s/.*/\'$user\\' => \'$pass\'/\" /config/cfg/secure.php");
  
  # email provider selection
  	switch ($provider) {
		case 'gmail':
			$smtp_address = 'smtp.gmail.com';
			$smtp_port = '587';
			break;
		case 'yahoo':
			$smtp_address = 'smtp.mail.yahoo.com';
			$smtp_port = '587';
			break;
		case 'microsoft':
			$smtp_address = 'smtp.live.com';
			$smtp_port = '587';
			break;
		case 'office365':
			$smtp_address = 'smtp.office365.com';
			$smtp_port = '587';
			break;
		case 'mail.com':
			$smtp_address = 'smtp.mail.com';
			$smtp_port = '587';
			break;
		case 'zoho':
			$smtp_address = 'smtp.zoho.com';
			$smtp_port = '587';
			break;
		case 'other':
			$smtp_address = strip_tags($_POST['smtp_address']);
			$smtp_port = strip_tags($_POST['smtp_port']);
			break;
}
  
  # save main settings to advanced.yaml
  $adv_array['plex'] = array('plex_user_emails' => "yes", 'libraries_to_skip' => "", 'server' => strip_tags($_POST['server']));
  $adv_array['mail'] = array('from' => "Plex Server", 'subject' => "Now Showing", 'recipients_email' => "", 'recipients' => "", 'provider' => $provider, 'address' => $smtp_address, 'port' => $smtp_port, 'username' => strip_tags($_POST['email_username']), 'password' => strip_tags($_POST['email_password']));
  
  if (!empty($_POST['plex_token'])) {
	$adv_array['token'] = array('api_key' => strip_tags($_POST['plex_token']));
  }

	$adv_yaml = Spyc::YAMLDump($adv_array,2,0);
	file_put_contents($adv_file, $adv_yaml);
  
    unlink('/config/www/admin/index.html');
	unlink('/config/www/admin/gettoken-pipes-setup.php');
	unlink('/config/www/admin/save_setup.php');
	echo "Setup Completed!";
}
else {
echo "Error: Failed to finish setup!";
}
exit;
?>

