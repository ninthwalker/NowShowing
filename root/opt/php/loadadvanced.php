<?php
# Load current advanced.yaml settings
include('spyc.php');
$adv = Spyc::YAMLLoad('/config/cfg/advanced.yaml');
$announcementSubject = file_get_contents('/opt/announcementSubject');
$announcementMessage = file_get_contents('/config/announcement_body.html');
$this_ver = file_get_contents('/config/cfg/version');

if (!empty($adv['mail']['recipients'])) {
        $recipients_array = implode(',',$adv['mail']['recipients']);
}
else {
        $recipients_array = "";
}

if (!empty($adv['mail']['recipients_email'])) {
        $recipients_email_array = implode(',',$adv['mail']['recipients_email']);
}
else {
        $recipients_email_array = "";
}

if (!empty($adv['plex']['libraries_to_skip'])) {
        $libraries_to_skip_array = implode(',',$adv['plex']['libraries_to_skip']);
}
else {
        $libraries_to_skip_array = "";
}

if (!empty($adv['mail']['sender'])) {
        $smtp_sender = $adv['mail']['sender'];
}
else {
        $smtp_sender = "";
}

# show special message or status
function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
}
$msg = get_data('https://raw.githubusercontent.com/ninthwalker/docker-templates/master/msg');
$cur_ver = get_data('https://raw.githubusercontent.com/ninthwalker/NowShowing/v2/root/opt/config/cfg/version');

if ($this_ver != $cur_ver) {
	$update_available = "<a href=\"https://github.com/ninthwalker/NowShowing/releases\" target=\"_blank\">New Version Available!</a><br>Update your docker image for latest $cur_ver release.";
}
else {
	$update_available = "";
}
?>
