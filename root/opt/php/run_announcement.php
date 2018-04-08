<?php
include('spyc.php');

if(!empty($_POST['announcementSubject']) && !empty($_POST['announcementMessage'])) {

  # get plex user email decision
  $adv = Spyc::YAMLLoad('/config/cfg/advanced.yaml');
  $plexUsers = $adv['plex']['plex_user_emails'];

  # import variables from form
  $subject = $_POST['announcementSubject'];
  $message = $_POST['announcementMessage'];
  $subjectLOC = "/opt/announcementSubject";
  $messageLOC = "/config/announcement_body.html";
  
  #write subject and message to files for ruby script
  file_put_contents($subjectLOC, $subject);
  file_put_contents($messageLOC, $message);
  
  if (isset($_POST['announcement_report']) && $plexUsers == 'yes') {
	$command = "announcementreport";
	  
  }
  elseif (isset($_POST['announcement_report']) && $plexUsers == 'no') {
	$command = "announcementreport -n";
	  
  }
  elseif (isset($_POST['announcement_test_report'])) {
	$command = "announcementreport -t";
  }
  
  # read and send announcement
  exec("$command", $output, $return);
	if($return !== 0) {
		echo "Announcement Email Failed!";
    }
    else {
		echo "Announcement Email Sent!";
    }
exit;
}
else {
echo "Please fill out Subject & Message.";
exit;
}
exit;
?>
