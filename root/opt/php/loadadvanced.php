<?php
# Load current advanced.yaml settings
include('spyc.php');
$adv = Spyc::YAMLLoad('/config/cfg/advanced.yaml');
$announcementSubject = file_get_contents('/opt/announcementSubject');
$announcementMessage = file_get_contents('/config/announcement_body.html');

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
?>
