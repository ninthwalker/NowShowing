<?php
###############################################################
# Password Protect 2.13
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
##################################################################
#  SETTINGS START
##################################################################
$LOGIN_INFORMATION = array(
'NowShowing' => 'NowShowing'
);

// request login? true - show login and password boxes, false - password box only
define('USE_USERNAME', true);

// User will be redirected to this page after logout
// define('LOGOUT_URL', '../');

// time out after NN minutes of inactivity. Set to 0 to not timeout
// I set to 3 days
define('TIMEOUT_MINUTES', 4320);

// This parameter is only useful when TIMEOUT_MINUTES is not zero
// true - timeout time from last activity, false - timeout time from login
define('TIMEOUT_CHECK_ACTIVITY', false);

##################################################################
#  SETTINGS END
##################################################################

///////////////////////////////////////////////////////
// do not change code below
///////////////////////////////////////////////////////

// show usage example
if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}

// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// logout?
if(isset($_GET['logout'])) {
  setcookie("NowShowing", '', $timeout, '/'); // clear password;
  header("Location: http://".$_SERVER['HTTP_HOST']."/admin/index.php");
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

// show login form
function showLoginPasswordProtect($error_msg) {
?>
<html>
<head>
  <title>NowShowing</title>
  <META content="width=device-width, initial-scale=0.5" name="viewport">
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
</head>
<body>
  <style>
		body  { 
			background-image: url("../img/background.jpg");
            color: #e6e6e6;
        }
		
		@media (max-width: 768px) {
			body {
				background-repeat: no-repeat;
				background-size: 100% 100%;
			}
		}

        .mybutton {
          display: inline-block;
          vertical-align: middle;
          -webkit-transform: perspective(1px) translateZ(0);
          transform: perspective(1px) translateZ(0);
          box-shadow: 0 0 1px transparent;
          overflow: hidden;
          -webkit-transition-duration: 0.2s;
          transition-duration: 0.2s;
          -webkit-transition-property: color, background-color;
          transition-property: color, background-color;
          background-color: #404040;
          color: #e6e6e6;
          padding: 5px 12px;
          border: 1px solid #e5a00d;
          font-weight:bold;
          border-radius: 4px;
          font-weight: normal;
          -webkit-box-shadow: inset 0 2px 2px rgba(0,0,0,.075),0 0 2px #e5a00d;
          box-shadow: inset 0 2px 2px rgba(0,0,0,.075),0 0 2px #e5a00d;
          cursor: pointer;
        }

        .mybutton:hover, .mybutton:focus, .mybutton:active {
          background-color: #e5a00d;
          color: #404040;
        }
		
		input {
         font-weight:normal;
         color: #e6e6e6;
         background-color: #404040;
         border: 0px;
        }

        input:focus {
         border-color: #e5a00d;
         outline: 0;
         -webkit-box-shadow: inset 0 8px 8px rgba(0,0,0,.075),0 0 8px #e5a00d;
         box-shadow: inset 0 8px 8px rgba(0,0,0,.075),0 0 8px #e5a00d;
        }
  </style>
  <div style="width:500px; margin-left:auto; margin-right:auto; text-align:center">
  <form method="post">
    <p>
        <img src="../img/nowshowing-icon.png" alt="nowshowing-Icon" width="68px" style="margin-bottom:5px;margin-right:5px;">
        <img src="../img/nowshowing.png" alt="NowShowing" width="400px">
    </p>
	<h3>Admin Access</h3>
    <font color="red"><?php echo $error_msg; ?></font><br />
<?php if (USE_USERNAME) echo 'Username:<br /><input type="input" name="access_login" /><br /><br />Password:<br />'; ?>
    <input type="password" name="access_password" /><p></p>
	<button class="mybutton" type="submit" name="Submit" value="Submit">Login</button>
  </form>
  <br />
  <a style="font-size:9px; color: #B0B0B0; font-family: Verdana, Arial;" href="https://github.com/ninthwalker/nowshowing/wiki">Forgot Password</a>
  <br />
  </div>
</body>
</html>

<?php
  // stop at this point
  die();
}
}

// user provided password
if (isset($_POST['access_password'])) {

  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = $_POST['access_password'];
  if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) ) 
  ) {
    showLoginPasswordProtect("Incorrect password.");
  }
  else {
    // set cookie if password was validated
    setcookie("NowShowing", md5($login.'%'.$pass), $timeout, '/');
    
    // Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
    // So need to clear password protector variables
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);
  }

}

else {

  // check if password cookie is set
  if (!isset($_COOKIE['NowShowing'])) {
    showLoginPasswordProtect("");
  }

  // check if cookie is good
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
    if ($_COOKIE['NowShowing'] == md5($lp)) {
      $found = true;
      // prolong timeout
      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("NowShowing", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");
  }
}
?>
