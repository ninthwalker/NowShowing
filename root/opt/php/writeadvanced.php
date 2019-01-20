<?php
include('spyc.php');

if (isset($_POST['save_settings'])) {
  $adv_file = "../../cfg/advanced.yaml";

  $libraries_to_skip = explode(',',strip_tags($_POST['libraries_to_skip']));
  $recipients_email = explode(',',strip_tags($_POST['recipients_email']));
  $recipients = explode(',',strip_tags($_POST['recipients']));
  $provider = strip_tags($_POST['email_provider']);
  if (isset($_POST['stats'])) {
   $stats = implode($_POST['stats']);
  }
  else {
    $stats = "";
  }

  # get email provider selection
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

  $adv_array['email'] = array('title' => strip_tags($_POST['title']), 'image' => strip_tags($_POST['image']), 'footer' => strip_tags($_POST['footer']), 'language' => strip_tags($_POST['language']));
  $adv_array['web'] = array('title_image' => strip_tags($_POST['title_image']), 'logo' => strip_tags($_POST['logo']), 'headline_title' => strip_tags($_POST['headline_title']), 'headliners' => strip_tags($_POST['headliners']), 'footer' => strip_tags($_POST['web_footer']), 'language' => strip_tags($_POST['web_language']));
  $adv_array['plex'] = array('plex_user_emails' => strip_tags($_POST['plex_user_emails']), 'libraries_to_skip' => $libraries_to_skip, 'server' => strip_tags($_POST['server']));
  $adv_array['mail'] = array('from' => strip_tags($_POST['from']), 'subject' => strip_tags($_POST['subject']), 'recipients_email' => $recipients_email, 'recipients' => $recipients, 'provider' => $provider, 'address' => $smtp_address, 'port' => $smtp_port, 'username' => strip_tags($_POST['email_username']), 'password' => $_POST['email_password'], 'sender' => strip_tags($_POST['email_sender']),);
  $adv_array['report'] = array('interval' => strip_tags($_POST['interval']), 'report_type' => strip_tags($_POST['report_type']), 'email_report_time' => strip_tags($_POST['email_report_time']), 'web_report_time' => strip_tags($_POST['web_report_time']), 'extra_details' => strip_tags($_POST['extra_details']), 'test' => strip_tags($_POST['test']));
  $adv_array['tautulli'] = array('server' => strip_tags($_POST['plexpy_server']), 'port' => strip_tags($_POST['plexpy_port']), 'https' => strip_tags($_POST['plexpy_https']), 'httproot' => strip_tags($_POST['plexpy_root']), 'api_key' => strip_tags($_POST['plexpy_api']), 'title' => strip_tags($_POST['plexpy_title']), 'stats' => strip_tags($stats));
  $adv_array['token'] = array('api_key' => strip_tags($_POST['plex_token']));
  
  $adv_yaml = Spyc::YAMLDump($adv_array,2,0);
  file_put_contents($adv_file, $adv_yaml);
  
  # delete current cron entries and creat new cron
  exec("/usr/local/sbin/create-cron && crontab /opt/nowshowing_schedule.cron");
  
  #header("Location: index2.php");
  #header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
  #header("Location: http://".$_SERVER['HTTP_HOST']."/admin");
  echo "Settings Saved!";
}
else {
echo "Error: Failed to save settings!";
}
exit;
?>

