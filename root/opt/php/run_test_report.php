<?php
#Test Report Settings

if (isset($_POST['test_report'])) {
	$reporttype = $_POST['report_type'];
	$details = $_POST['extra_details'];
	
	# With Details
	if ($details == 'yes') {
		switch ($reporttype) {
			case 'both':
				$command = "combinedreport -t -d";
				break;
			case 'webonly':
				$command = "webreport -t -d";
				break;
			case 'emailonly':
				$command = "emailreport -t -d";
				break;
			default:
				$command = "combinedreport -t -d";
				break;
		}
	}
	
	# Without details
	else {
		switch ($reporttype) {
			case 'both':
				$command = "combinedreport -t";
				break;
			case 'webonly':
				$command = "webreport -t";
				break;
			case 'emailonly':
				$command = "emailreport -t";
				break;
			default:
				$command = "combinedreport -t";
				break;
		}
	}
	
	# run test report
	exec("$command", $output, $return);
        if($return !== 0) {
            echo "report failed";
        }
        else {
			echo "report completed";
        }
	exit;
}
exit;
?>

