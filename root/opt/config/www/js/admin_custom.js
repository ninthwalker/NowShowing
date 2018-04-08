// ------------------------------------------------
// allows linking directly to tab name .ie: #report
// ------------------------------------------------

$(function() {
   var hash = window.location.hash;
   if (hash) {
     $('.nav-tabs a[href="' + hash + '"]').tab('show');
   }
 });
 
 
// ----------------------------------------------
// Save Settings status text: show, then fade out
// ----------------------------------------------

$(function() {
$("#mainform").submit(function() {
 $('#settingsModal').modal('hide');
// $('#logout').blur(); tried to use to get rid of focus color after modal closing
// e.preventDefault() using return false instead of this for now
 $.ajax({
  url: "save_settings.php",
  type: 'post',
  data: $('#mainform').serialize(),
  success: function(response){
	console.log(response);
    // on success
	if ( response.indexOf("Saved") > -1 ) {
	$('#status_text').css({
		color: 'green'
	});
	$('#status_text').text(response);
	$("#status_text").delay(3000).fadeOut(2000,function() {
		$("#status_text").empty().show();
	});
	}
	else {
		$('#status_text').css({
		color: '#cc0000'
	});
	$('#status_text').text(response);
	}
  },
  error: function(){
    // on failure;
	console.log("Error: Could not divide by zero");
	$('#status_text').css({
		color: '#cc0000'
	});
	$('#status_text').text("Error: Could not divide by zero");
  }
});
  return false;
});
});


// ---------------------------------------------------------------------
// Test Report: status of running then completed with '...' during
// ---------------------------------------------------------------------
$(function() {
    $("#test_report_form").submit(function() {
			$('#testReportModal').modal('hide');
			$('#test_report_button').attr("disabled", true);
			$('#ondemand_report_button').attr("disabled", true);
			$('#test_report_button').css( 'cursor', 'not-allowed' );
			$('#ondemand_report_button').css( 'cursor', 'not-allowed' );
			$.ajax({
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							if (evt.lengthComputable) {
								console.log("start");
								$('#status_text').css({
									color: '#ffff4d'
								});
								$('#status_text').text("Test Report: Running");
								$('.status').css({
									visibility: 'visible'
								});
							}
						}
					}, false);

					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							console.log("end");
							$('#status_text').css({
									color: 'green'
							});
							$('.status').css({
								visibility: 'hidden'
							});
						}
					}, false);

					return xhr;
				},
				type: 'POST',
				url: "test_report.php",
				data: { extra_details : $('#extra_details').val(),
						test_report: "test_report",
						report_type : $('#report_type').val()
				},
				success: function (data) {
					$('#status_text').text("Test Report: Finished");
				},
				complete: function (data){
                    $('#test_report_button').attr("disabled", false);
					$('#ondemand_report_button').attr("disabled", false);
					$('#test_report_button').css( 'cursor', 'pointer' );
					$('#ondemand_report_button').css( 'cursor', 'pointer' );
                }
			});
			return false;
	});
});

// ---------------------------------------------------------------------
// On Demand Report: status of running then completed with '...' during
// ---------------------------------------------------------------------
$(function() {
    $("#ondemand_report_form").submit(function() {
			$('#ondemand_report_button').attr("disabled", true);
			$('#test_report_button').attr("disabled", true);
			$('#test_report_button').css( 'cursor', 'not-allowed' );
			$('#ondemand_report_button').css( 'cursor', 'not-allowed' );
			$('#ondemandReportModal').modal('hide');
			$.ajax({
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							if (evt.lengthComputable) {
								console.log("start");
								$('#status_text').css({
									color: '#ffff4d'
								});
								$('#status_text').text("On Demand Report: Running");
								$('.status').css({
									visibility: 'visible'
								});
							}
						}
					}, false);

					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							console.log("end");
							$('#status_text').css({
									color: 'green'
							});
							$('.status').css({
								visibility: 'hidden'
							});
						}
					}, false);

					return xhr;
				},
				type: 'POST',
				url: "ondemand_report.php",
				data: { ondemand_report: "ondemand_report"},
				success: function (data) {
					$('#status_text').text("On Demand Report: Finished");
				},
				complete: function (data){
                    $('#ondemand_report_button').attr("disabled", false);
					$('#test_report_button').attr("disabled", false);
					$('#test_report_button').css( 'cursor', 'pointer' );
					$('#ondemand_report_button').css( 'cursor', 'pointer' );
                }
			});
			return false;
	});
});


// ---------------------------------------------------------------------
// Announcement Email: status of running then completed with '...' during
// ---------------------------------------------------------------------
$(function() {
    $("#announcement_report").click(function() {
			$('#ondemand_report_button').attr("disabled", true);
			$('#test_report_button').attr("disabled", true);
			$('#test_report_button').css( 'cursor', 'not-allowed' );
			$('#ondemand_report_button').css( 'cursor', 'not-allowed' );
			$('#announcement_button').attr("disabled", true);
			$('#announcement_button').css( 'cursor', 'not-allowed' );
			$('#announcement_test_button').attr("disabled", true);
			$('#announcement_test_button').css( 'cursor', 'not-allowed' );
			$('#announcementReportModal').modal('hide');
			$.ajax({
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							if (evt.lengthComputable) {
								console.log("start");
								$('#status_text').css({
									color: '#ffff4d'
								});
								$('#status_text').text("Announcement Email: Sending");
								$('.status').css({
									visibility: 'visible'
								});
							}
						}
					}, false);

					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							console.log("end");
							$('#status_text').css({
									color: 'green'
							});
							$('.status').css({
								visibility: 'hidden'
							});
						}
					}, false);

					return xhr;
				},
				type: 'POST',
				url: "announcement.php",
				data: $('#announcement_report_form').serialize() + '&announcement_report=' + 'announcement_report',
				success: function (data) {
					$('#status_text').text(data);
				},
				complete: function (data){
                    $('#ondemand_report_button').attr("disabled", false);
					$('#test_report_button').attr("disabled", false);
					$('#test_report_button').css( 'cursor', 'pointer' );
					$('#ondemand_report_button').css( 'cursor', 'pointer' );
					$('#announcement_button').attr("disabled", false);
					$('#announcement_button').css( 'cursor', 'pointer' );
					$('#announcement_test_button').attr("disabled", false);
					$('#announcement_test_button').css( 'cursor', 'pointer' );
                }
			});
			return false;
	});
});

// ---------------------------------------------------------------------
// Announcement TEST Email: status of running then completed with '...' during
// ---------------------------------------------------------------------
$(function() {
    $("#announcement_test_report").click(function() {
			$('#ondemand_report_button').attr("disabled", true);
			$('#test_report_button').attr("disabled", true);
			$('#test_report_button').css( 'cursor', 'not-allowed' );
			$('#ondemand_report_button').css( 'cursor', 'not-allowed' );
			$('#announcement_button').attr("disabled", true);
			$('#announcement_button').css( 'cursor', 'not-allowed' );
			$('#announcement_test_button').attr("disabled", true);
			$('#announcement_test_button').css( 'cursor', 'not-allowed' );
			$('#announcementTestReportModal').modal('hide');
			$.ajax({
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							if (evt.lengthComputable) {
								console.log("start");
								$('#status_text').css({
									color: '#ffff4d'
								});
								$('#status_text').text("Announcement Test: Sending");
								$('.status').css({
									visibility: 'visible'
								});
							}
						}
					}, false);

					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							console.log("end");
							$('#status_text').css({
									color: 'green'
							});
							$('.status').css({
								visibility: 'hidden'
							});
						}
					}, false);

					return xhr;
				},
				type: 'POST',
				url: "announcement.php",
				data: $('#announcement_report_form').serialize() + '&announcement_test_report=' + 'announcement_test_report',
				success: function (data) {
					$('#status_text').text(data);
				},
				complete: function (data){
                    $('#ondemand_report_button').attr("disabled", false);
					$('#test_report_button').attr("disabled", false);
					$('#test_report_button').css( 'cursor', 'pointer' );
					$('#ondemand_report_button').css( 'cursor', 'pointer' );
					$('#announcement_button').attr("disabled", false);
					$('#announcement_button').css( 'cursor', 'pointer' );
					$('#announcement_test_button').attr("disabled", false);
					$('#announcement_test_button').css( 'cursor', 'pointer' );
                }
			});
			return false;
	});
});


// ---------------------------------------------------------------------
// Announcement save for later
// ---------------------------------------------------------------------

$(function() {
$("#announcement_save_report").click(function() {
 $('#announcementSaveReportModal').modal('hide');
 $.ajax({
  url: "save_announcement.php",
  type: 'post',
  data: $('#announcement_report_form').serialize() + '&announcement_save_report=' + 'announcement_save_report',
  success: function(response){
	console.log(response);
    // on success
	if ( response.indexOf("Saved") > -1 ) {
	$('#status_text').css({
		color: 'green'
	});
	$('#status_text').text(response);
	$("#status_text").delay(3000).fadeOut(2000,function() {
		$("#status_text").empty().show();
	});
	}
	else {
		$('#status_text').css({
		color: '#cc0000'
	});
	$('#status_text').text(response);
	}
  },
  error: function(){
    // on failure;
	console.log("Error: Could not divide by zero");
	$('#status_text').css({
		color: '#cc0000'
	});
	$('#status_text').text("Error: Could not divide by zero");
  }
});
  return false;
});
});

// ---------------------------------------------------------------------
// Get Token
// ---------------------------------------------------------------------

$(function() {
$("#get_token_form").submit(function() {
 $('#tokenModal').modal('hide');
 $.ajax({
  url: "gettoken-pipes.php",
  type: 'post',
  data: $('#get_token_form').serialize(),
  success: function(response){
	var json = $.parseJSON(response);
    // on success
	if ( response.indexOf("Saved") > -1 ) {
	$('#plex_token').val(json.token);
	$('#status_text').css({
		color: 'green'
	});
	$('#status_text').text(json.statustext);
	$("#status_text").delay(3000).fadeOut(2000,function() {
		$("#status_text").empty().show();
	});
	}
	// on credential failure
	else {
		console.log(response);
		$('#status_text').css({
		color: '#cc0000'
	});
	$('#status_text').text(json.statustext);
	$("#status_text").delay(4000).fadeOut(2000,function() {
		$("#status_text").empty().show();
	});
	}
  },
  error: function(){
    // on failure
	console.log(response);
	$('#status_text').css({
		color: '#cc0000'
	});
	$('#status_text').text("Error: Could not divide by zero");
	$("#status_text").delay(3000).fadeOut(2000,function() {
		$("#status_text").empty().show();
	});
  }
});
  return false;
});
});

// ---------------------------------------------------------------------
// Setup Wizard
// ---------------------------------------------------------------------

	// Enter token manually hideaway
	$('#showToken').click(function() {
	  $('#tokenDiv').toggle('fast', function() {
		// Animation complete.
	  });
	});
	// --------------------
	
	// Show Token help
	$('#showHelp').click(function() {
	  $('#helpDiv').toggle('fast', function() {
		// Animation complete.
	  });
	});
	// --------------------
	
		// block next button
		function blockNext() {
			$('#mynextbutton').prop('disabled', true);
			$('#mynextbutton').css( 'cursor', 'not-allowed' );
		};
	// --------------------
	
	// Submit Setup Modal Function
function submitSetup() {
 $.ajax({
  url: "save_setup.php",
  type: 'post',
  data: $('#setupForm').serialize(),
  success: function(response){
	location.href = 'index.php';
	console.log(response);
  },
  error: function(){
    // on failure;
	console.log("Error: Could not divide by zero");
  }
});
  // return false;
};
	
	
	// ---------------------
	// Setup - Get Token
$(function() {
$("#getToken").click(function() {
$("#status_text").empty();
var data = { 
 plex_username: $('#plex_username').val(),
 plex_password: $('#plex_password').val()
}
 $.ajax({
  url: "gettoken-pipes-setup.php",
  type: 'post',
  data: data,
    success: function(response){
	var json = $.parseJSON(response);
    // on success
	if ( response.indexOf("Saved") > -1 ) {
	$('#status_text').css({
		color: 'green'
	});
	$('#status_text').text(json.statustext);
	$('#mynextbutton').prop('disabled', false);
	$('#mynextbutton').css('cursor', 'pointer');
	}
	// on credential failure
	else {
		console.log(response);
		$('#status_text').css({
		color: '#cc0000'
	});
	$('#status_text').text(json.statustext);
	$('#mynextbutton').prop('disabled', true);
	$('#mynextbutton').css('cursor', 'not-allowed');
	}
  },
  error: function(){
    // on failure
	console.log(response);
	$('#status_text').css({
		color: '#cc0000'
	});
	$('#status_text').text("Error: Could not divide by zero");
  }
});
 return false;
});
});
	
	
	
// ---------------- END --------------------