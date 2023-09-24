<?php

//Comment below lines out to stop error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//Starts or continues session from previous PHP page

//Get config file variables and functions
include_once("../../config_ispraak.php");

//Connect to the database
$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

//Assume there is no error fetching grades
$error = ""; 

if (mysqli_connect_errno())
{
  	echo "Unable to connect to the database. Please try again later.";
  	$error = "Unable to connect to database."; 
  	//echo "Failed to connect to MySQL because: " . mysqli_connect_error();
}

//Get email address from query string 
$id=$_GET['id'];

//Determine if page needs to confirm the unsub or actually execute it
//options are ?action=check ?action=confirm
$action=$_GET['action'];

//Catch all in case there are other problems with the query string
$error = "Sorry. Uknown error occurred, please check back soon.";

if ($id == "" || $action == "")
{
  	$error = "Zut alors! This page can't load. It is possible that you  have incorrectly entered the URL in the address bar above. Please confirm the link is not broken and reload the page."; 

}
else
{

//Going to check the database, so prepare the email address
$id = mysqli_real_escape_string($msi_connect, $id);

//Has this person already unsubscribed?
$myresult = mysqli_query($msi_connect, "SELECT * FROM ispraak_unsubscribe where email='$id'");
//$row = mysqli_fetch_array($myresult);
$num=mysqli_num_rows($myresult);

if ($num > 0 && $action == "check")  { $error = "It appears you have already unsubscribed the address: $id";}
if ($num == 0 && $action == "check")  
	{ 
		$error = "Please confirm you wish to unsubscribe the address: $id <br><br><center>
		<a href=\"unsubscribe.php?id=$id&action=confirm\" class=\"button5\">Confirm Email Preference</a></center>";
	}
if ($num == 0 && $action == "confirm")  
	{ 
		$error = "You have successfully unsubscribed the address: $id";
		$rigchtnow = time();
		$mycode = "NDD"; //no daily digest
		$mycode2 = "NCE"; //no creator email
		$query = "INSERT INTO ispraak_unsubscribe VALUES ('$id', '$mycode', '$mycode2', '$rightnow','')";
		//execute the query and determine if it was a good insert
		$good_insert = mysqli_query($msi_connect, $query);
		if (!$good_insert)	{ $error = "Oops. Sorry unable to update your email preferences right now.";}
	}
}

echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title><link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\" media=\"all\">
<script type=\"text/javascript\" src=\"javascript/ispraak.js\"></script></head>
<body id=\"main_body\" ><img id=\"top\" src=\"images/top.png\" alt=\"\">
<div id=\"form_container\"><div id=\"headerBar\"></div>
<form id=\"ispraak\" class=\"ispraak_form\"  method=\"post\" action=\"#\">
<div class=\"form_description\">
<img style=\"float: left; padding: 0px 20px 0px 0px\" src=\"images/logo5.png\" height=\"35\" alt=\"iSpraak-Logo\" align=\"left\"> 
<br><br><br><p>$error";

echo "<br><br></div></p></form>$ispraak_footer</div><img id=\"bottom\" src=\"bottom.png\" alt=\"\"></body></html>";
			
//close your connection to the DB
mysqli_close($msi_connect);

?>