<?php
if (!empty($_POST['announcementSubject']) && !empty($_POST['announcementMessage'])) {
	if (isset($_POST['announcement_save_report'])) {

	  # import variables from form
	  $subject = $_POST['announcementSubject'];
	  $message = $_POST['announcementMessage'];
	  $subjectLOC = "/opt/announcementSubject";
	  $messageLOC = "/config/announcement_body.html";
	  
	  #save subject and message
	  file_put_contents($subjectLOC, $subject);
	  file_put_contents($messageLOC, $message);
	  
	  echo "Announcement Saved!";
	}
	else {
		echo "Error: Failed to save announcement!";
	}
exit;
}
else {
	echo "Please fill out Subject & Message.";
}
exit;
?>
