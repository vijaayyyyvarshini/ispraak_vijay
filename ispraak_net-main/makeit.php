<?php

//This file is called from index.html and serves to create an instructor's activity

//Continues Session from previous PHP page
session_start();

//Comment below lines out to stop error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Get database and configuration variables, and custom functions
include_once("../../config_ispraak.php");

//PHP Pear Packages Needed
ini_set("include_path", '/home2/dnickol1/php:' . ini_get("include_path") );

//Do not use default PHP mail function
require_once "Mail.php";

//Look up IP and USER Agent for Logging and SPAM control
$visitor_ip = getIP();
$user_agent = $_SERVER['HTTP_USER_AGENT'];
 
//grab variables from Form that was submitted
$email=$_POST['element_1'];
$language=$_POST['element_3'];
$audiofile=$_POST['element_4'];
$blocktext=$_POST['element_2'];
$honey_pot=$_POST['user_zip_kode'];

//do a basic log of this request... see if a bot writes to the honeypot
$logrightnow = time();
$logrightnow = $logrightnow -21600; 
$logrightnow = date('m/d/Y H:i:s', $logrightnow);
$mylogfile = fopen("activity_log.txt", "a") or die("Unable to open log file!");
$logtxt = "\n $email\n $language\n $audiofile\n $blocktext\n honey: $honey_pot \n IP: $visitor_ip\n $logrightnow\n $user_agent \n";
fwrite($mylogfile, $logtxt);
fclose($mylogfile);

//might be an LTI entry to this page...
//this was to check for an LTI call to this page
/*

$lti_running = "no";
$lti_message = " and e-mailed to you. You can also copy the links below (right click) in case the automated e-mail does not reach you or if you have unsubscribed from the creator e-mails."; 

$context_id=$_GET['cid'];
if (strlen($context_id) > 3)
{
	$lti_message = " and is now available in your LMS. Press the REFRESH button in your dashboard to see this activity. ";
	$lti_running = "yes";
}
*/

//create an error message variables, by default no error and everything assumed good 
$error_saving_db = "<p style=\"color:green\">Database connection: ✓</span>";
$vcode="good";
$validity=""; 

//connect to the database
$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

if (mysqli_connect_errno())
{
  	$error_saving_db = "<p style=\"color:red\">Database connection: X</span>";
  	$vcode = "bad";
  	//echo "Unable to connect to the database. Please try again later.";
  	//echo "Failed to connect to MySQL because: " . mysqli_connect_error();
}
else
{
//since there is no connection error, sanitize all the user input
$email = mysqli_real_escape_string($msi_connect, $email);
$language = mysqli_real_escape_string($msi_connect, $language);
$audiofile = mysqli_real_escape_string($msi_connect, $audiofile);
$blocktext = mysqli_real_escape_string($msi_connect, $blocktext);
}

//Dont need to check for registered accounts, all will be free

//First check to see if this request is coming from a valid account
//with a valid e-mail address
/*
$myresult = mysqli_query($msi_connect, "SELECT * FROM ispraak_accounts");
//$num=mysql_numrows($myresult);
$num = $myresult->num_rows;

$validity="<br><br><p style=\"color:red\">You are using the free version of iSpraak with an unregistered account ($email). Some features will be disabled, such as premium text-to-speech services. Please consider registering to help financially support this project! </p>";
$vcode="good";
$vcode2="unregistered";

$warnyou="";

$i=0;
while ($i < $num) 
{
$required_etext=mysqli_result($myresult,$i,"required_etext");
$institution=mysqli_result($myresult,$i,"institution");
$expiry=mysqli_result($myresult,$i,"expiry");
$readable_date = date('m/d/Y', $expiry);


if (strpos($email,$required_etext) !== false) 
{
$validity="<br><br>Registered Account: $institution <br>Licensed until: $readable_date";
$vcode="good";
$vcode2="good";


//If e-mail is valid, should also check on account expiry date

$timenow = time();

if ($timenow > $expiry)
	{
		$warnyou="<br><br><p style=\"color:red\">Please renew your account or complete your registration for $institution!</p>";
		$vcode="bad";
	}

}

$i++;
}

//Query how many times a demo e-mail address has been used

if ($vcode2 == "unregistered")
{
$myresult = mysqli_query($msi_connect, "SELECT * FROM ispraak WHERE email='$email' ORDER BY mykey DESC");
//$num=mysql_numrows($myresult);
$num = $myresult->num_rows;

	if ($num > 100)
	{
	$warnyou="<p style=\"color:red\">You have exceeded your trial for iSpraak. Please register!</p>";
	$vcode="bad";
	}

}

*/

//create an ID pair for this activity
$mykey = time();
$mykey2=substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1).substr(md5(time()),1);


//make session variables to be used by MP3 uploader
$_SESSION['mykey'] = $mykey;
$_SESSION['mykey2'] = $mykey2;

//check for a valid e-mail address string
if (!i($email, FILTER_VALIDATE_EMAIL)) 
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Double check that e-mail address!</p>";
}

//check for empty variables
if ($email == "")
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Double check that e-mail address!</p>";
}

//check for empty variables
if ($blocktext == "")
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Oops! You did not include any text to save.</p>";
}

//anti-spam measures here, based on history of logs
if (strpos($email,'@') == false) 
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Double check that e-mail address!</p>";
}

if (strpos($email,'@ispraak.com') == true) 
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Double check that e-mail address!</p>";
}

if (strpos($email,'@ispraak.net') == true) 
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Double check that e-mail address!</p>";
}

if (strpos($blocktext,'http') == true) 
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Unable to create activity. Text should consist of words only.</p>";
}

if (strpos($blocktext,'www') == true) 
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Unable to create activity. Text should consist of words only.</p>";
}

$hugeness = strlen($blocktext);
if ($hugeness > 500)
{
	$vcode = "bad";
	$warnyou = "<br><p style=\"color:red\">Unable to create activity. Please try a shorter text.</p>";
}

//honey pot entries from spammers look the same, range of numbers between 100,000 and 200,000
//could change this to just check if ANYTHING is sent as a variable here
if (is_numeric("$honey_pot")) 
{ 
	if ($honey_pot > 100000)
	{
		$vcode = "bad";
		$warnyou = "<br><p style=\"color:red\">Unable to create activity. Please disable autofill.</p>";
	}
 
}

//end anti-spam measures... more can be added as logs indicate new problems

if ($vcode == "good")
{

//add both mykey (time) and mykey2 (random) and an auto increase column

//define the query
$query = "INSERT INTO ispraak VALUES ('$email', '$language', '$audiofile', '$blocktext','$mykey', '$mykey2','')";

//execute the query
//determine if it was a good insert
$good_insert = mysqli_query($msi_connect, $query);

//new monster IF statement to avoid duplicate mykey issue
//or any other INSERT problem

if (!$good_insert)
{
//new activity was not inserted

$warnyou = "<br><p style=\"color:red\">Record unable to be updated right now.</p>";
	

echo "

<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\" media=\"all\">
<script type=\"text/javascript\" src=\"javascript/ispraak.js\"></script>

</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"images/top.png\" alt=\"\">
	<div id=\"form_container\">
	<div id=\"headerBar\"></div>
	
		<form id=\"ispraak\" class=\"ispraak_form\"  method=\"post\" action=\"makeit.php\">
					<div class=\"form_description\">
					
					
					<img style=\"float: left; padding: 0px 20px 0px 0px\" src=\"images/logo5.png\" height=\"35\" alt=\"iSpraak-Logo\" align=\"left\"> 
		
			<br><br><br><center>Zut alors! We're having a problem saving your activity right now!<br><br>$error_saving_db $warnyou<br><br><br>
<br>		</div>		
			</p>

		</form>	
		<div id=\"footer\">
			© D. Nickolai
		</div>
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
</html>";



}
else
{
//new activity was inserted


//if this request came from the LTI, you need to update that database as well
//for development purposes, putting no for now
$lti_running = "no";

if ($lti_running === "yes")
{
	$role = "instructor"; 
	$misc999 = "999"; 
	$query2 = "INSERT INTO ispraak_lti VALUES ('$context_id', '$mykey', '$email', '$role','$misc999','$misc999','$mykey','')";
	mysqli_query($msi_connect, $query2);
}
else
{

//2018, LTI NOT running, so send an e-mail

//The email delivery is what will allow us to limit unregistered or expired users
//from finding out their activity code
//Let's define strings based on the vCodes saved above
//Can also include the warn-you variable text in the e-mail


$helpu = "<br><br>For other help and activity creation guidelines, check out our
 help page <a href=\$domain_name/help.html\">here.</a> If you don't want to receive these activity creation e-mails, you can <a href=\"$domain_name/unsubscribe.php?id=$email&action=check\">unsubscribe</a>.";



$to5 = "slulanguages@gmail.com";
$subject = "iSpraak Activity Created";
$from = "iSpraak <ispraak.bot@ispraak.com>";
$student_body = "<table border = 0 width = 500><tr><td><img src=\"$domain_name/images/ispraak.png\"><br><h2>Your iSpraak Links</h2><br>New activity #$mykey has been created.<br><br>Student link: <a href=\"$domain_name/ispraak.php?mykey=$mykey&mykey2=$mykey2\">HERE</a><br>Instructor link: <a href=\"$domain_name/grades.php?mykey=$mykey&dou=courr&mykey2=$mykey2\">HERE</a></b><br><br>For technical assistance, please send an e-mail to help@ispraak.com. This message has been sent from an address that is not monitored. $helpu </td></tr>";
$student_body2 = "<table border = 0 width = 500><tr><td><img src=\"$domain_name/images/ispraak.png\"><br><h2>iSpraak Links for $email</h2><br>New activity #$mykey has been created.<br><br>Student link: <a href=\"$domain_name/ispraak.php?mykey=$mykey&$mykey2\">HERE</a><br>Instructor link: <a href=\"$domain_name/grades.php?mykey=$mykey&dou=courr&mykey2=$mykey2\">HERE</a></b><br><br>For technical assistance, please send an e-mail to help@ispraak.com. This message has been sent from an address that is not monitored. $helpu </td></tr>";

//new variables for august 2018
//renamed these variables in the config file, be sure to chech them out
/*
$hostz = "ssl://mail.ispraak.com";
$portz = "465";
$usernamez = "ispraak.bot@ispraak.com";
$passwordz = "ksfkjMMM345dkkkL";
$contentz = "text/html; charset=utf-8";
$mimez = "1.0";
$reply_addressz = "no_reply@ispraak.com";

$mail_host = "ssl://mail.ispraak.net";
$mail_port = "465";
$mail_username = "ispraak.bot@ispraak.net";
$mail_password = "ZZZo5mFEEMKwjNcuRnTolKm";
$mail_content = "text/html; charset=utf-8";
$mail_mime = "1.0";
$mail_reply_address = "no_reply@ispraak.net";

*/

$headers = array ('From' => $from,
  'To' => $email,
  'Subject' => $subject,
  'Reply-To' => $mail_reply_address,
  'MIME-Version' => $mail_mime,
  'Content-type' => $mail_content,
  'Date' => date('r', time())
  
  );
$smtp = Mail::factory('smtp',
  array ('host' => $mail_host,
    'port' => $mail_port,
    'auth' => true,
    'username' => $mail_username,
    'password' => $mail_password));

//$mailz = $smtp->send($email, $headers, $student_body);
//old functions that often go straight to junk folder 
//mail($email, $subject, $student_body, "From: $from\nContent-Type: text/html; charset=iso-8859-1");

//enough people have asked to stop getting these for every assignment, so.... 
//has this person opted out of e-mail communication, let's find out
    
    $tname2 = mysqli_real_escape_string($msi_connect, $email);
    $myresultw = mysqli_query($msi_connect, "SELECT * FROM ispraak_unsubscribe2 WHERE email = '$tname2'");
	//$numw=mysql_numrows($myresultw);
	
	//commented out for development and added it to be zero for warnings on 2022
	//$numw= $myresultw->num_rows;
	$numw = "0";
	
	$corrected_text = ""; 

	if ($numw > 0)
	{
		$corrected_text = "<br>Note: $tname2 has unsubscribed from the creator's e-mail.<br>";
	} 
	else
	{
		//below line sends a copy to our generic gmail
		//mail($to5, $subject, $student_body2, "From: $from\nContent-Type: text/html; charset=iso-8859-1");
		$mailz = $smtp->send($email, $headers, $student_body);
		//echo "debuggin - $mailz";
	
	}

//good as of august 2020
//mail($to5, $subject, $student_body2, "From: $from\nContent-Type: text/html; charset=iso-8859-1");

//end non-LTI e-mail generator
//no sense in emailing a bunch when LTI system is keeping track 
}


if ($audiofile == "1")
{

echo "


<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\" media=\"all\">
<script type=\"text/javascript\" src=\"javascript/ispraak.js\"></script>

</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"images/top.png\" alt=\"\">
	<div id=\"form_container\">
	<div id=\"headerBar\"></div>
	
		<form id=\"ispraak\" class=\"ispraak_form\"  method=\"post\" action=\"makeit.php\">
					<div class=\"form_description\">
					
					
					<img style=\"float: left; padding: 0px 20px 0px 0px\" src=\"images/logo5.png\" height=\"35\" alt=\"iSpraak-Logo\" align=\"left\"> 
	
		Success!<br><br>Your activity has been created and is now ready to be shared! <br><br></div>

								<br><center>
			<a target=\"_blank\" class=\"cutelink\" href=\"$domain_name/ispraak.php?mykey=$mykey&mykey2=$mykey2\">Review Activity (Student Link)</a><br>
			Copy this link and share with your students<br><br>
			<a target=\"_blank\" class=\"cutelink\" href=\"$domain_name/grades.php?mykey=$mykey&mykey2=$mykey2\">Check Grades (Instructor Link)</a> <br>Do not share your private link! 
			
			<br><br>			
			</p>

		</form>	
		$ispraak_footer
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
</html>";

}



if ($audiofile == "2")
{
//echo "<center>"; 
//echo "Activity requires audio file. ";

echo "

<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\" media=\"all\">
<script type=\"text/javascript\" src=\"javascript/ispraak.js\"></script>

</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"images/top.png\" alt=\"\">
	<div id=\"form_container\">
	<div id=\"headerBar\"></div>
	
		<form id=\"form_1007732\" class=\"ispraak_form\" enctype=\"multipart/form-data\" method=\"post\" action=\"mp3uploader.php\">
					<div class=\"form_description\">
					
					<img style=\"float: left; padding: 0px 20px 0px 0px\" src=\"images/logo5.png\" height=\"35\" alt=\"iSpraak-Logo\" align=\"left\"> 
	<br><br><br>
					
			<br>Please select an MP3 file to upload from your computer. <br><br>File cannot be greater than 2 MB.<br><br></p>
		</div>						
			<ul >

<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"4999999\"/>
<input id=\"element_1\" name=\"file\" class=\"element file\" id=\"file\" type=\"file\"/> 
<input type=\"hidden\" name=\"form_id\" value=\"329912\" />
			    <br><br>
                <input type=\"submit\" value=\"Upload File\"/>
                
                </form>
                </ul>
		</form>	
		<div id=\"footer\">
			© D. Nickolai
		</div>
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
</html>";


}


//all the above brackets is for successful insert 
//into mysql db
}

}

if ($audiofile=='3'){
	echo"
	<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\" media=\"all\">
<script type=\"text/javascript\" src=\"javascript/ispraak.js\"></script>

</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"images/top.png\" alt=\"\">
	<div id=\"form_container\">
	<div id=\"headerBar\"></div>
	
		<form id=\"form_1007732\" class=\"ispraak_form\" enctype=\"multipart/form-data\" method=\"post\" action=\"mp3uploader.php\">
					<div class=\"form_description\">
					
					<img style=\"float: left; padding: 0px 20px 0px 0px\" src=\"images/logo5.png\" height=\"35\" alt=\"iSpraak-Logo\" align=\"left\"> 
	<br><br><br>
</html>";
}
else
{
//vcode is clearly bad... 

echo "

<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\" media=\"all\">
<script type=\"text/javascript\" src=\"javascript/ispraak.js\"></script>

</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"images/top.png\" alt=\"\">
	<div id=\"form_container\">
	<div id=\"headerBar\"></div>
	
		<form id=\"ispraak\" class=\"ispraak_form\"  method=\"post\" action=\"makeit.php\">
					<div class=\"form_description\">
					
					
					<img style=\"float: left; padding: 0px 20px 0px 0px\" src=\"images/logo5.png\" height=\"35\" alt=\"iSpraak-Logo\" align=\"left\"> 
	
		
				
					
			<br><br><br><center>Zut alors! We're having a tiny problem saving your activity. <br><br>$error_saving_db $validity $warnyou <br><br><br>
<br>		</div>		
			</p>

		</form>	
		$ispraak_footer
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
</html>";


}





//close your connection to the DB
mysqli_close($msi_connect);
?>
