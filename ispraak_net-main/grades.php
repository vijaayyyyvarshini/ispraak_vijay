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

//Get mykey from query string & declare session variable
$mykey=$_GET['mykey'];
$mykey2=$_GET['mykey2'];

//Check iSpraak Table to confirm mykey1 and mykey2 are a secure pair
$myresult = mysqli_query($msi_connect, "SELECT * FROM ispraak where mykey='$mykey' AND mykey2='$mykey2'");
$row = mysqli_fetch_array($myresult);
$rowcount=mysqli_num_rows($myresult);
$num = 0; 

if ($rowcount < 1)
{
  	$error = "Crikey! This page can't load. It is possible that you  have incorrectly entered the URL in the address bar above. Please confirm the link is not broken and reload the page."; 

}
else
{
$myresult = mysqli_query($msi_connect, "SELECT * FROM ispraak_grades where activity_id='$mykey' ORDER BY uniquekey DESC");
$row = mysqli_fetch_array($myresult);
$num=mysqli_num_rows($myresult);
if ($num == 0) { $error = "Looks like you're in the right place, but there has been no student activity yet on this exercise. Please check back once students have submitted their work.";}

}


echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title><link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\" media=\"all\">
<script type=\"text/javascript\" src=\"javascript/ispraak.js\"></script></head>
<body id=\"main_body\" ><img id=\"top\" src=\"images/top.png\" alt=\"\">
<div id=\"form_container\"><div id=\"headerBar\"></div>
<form id=\"ispraak\" class=\"ispraak_form\"  method=\"post\" action=\"#\">
<div class=\"form_description\">
<img style=\"float: left; padding: 0px 20px 0px 0px\" src=\"images/logo5.png\" height=\"35\" alt=\"iSpraak-Logo\" align=\"left\"> 
<br><br><br>$error";

//for loop to display results


$i=0;
while ($i < $num) 
{

$sname=mysqli_result($myresult,$i,"student_name");
$semail=mysqli_result($myresult,$i,"student_email");
$sscore=mysqli_result($myresult,$i,"score");
$seffort=mysqli_result($myresult,$i,"effort_text");
$stime=mysqli_result($myresult,$i,"timestamp");
$readable_date = date('m/d/Y', $stime);
$smissed=mysqli_result($myresult,$i,"missed_words");
$smisc=mysqli_result($myresult,$i,"misc");
$ukey=mysqli_result($myresult,$i,"uniquekey");

//correct some instances of punctuation artefacts creating
//inaccurate number of missed words
//only for more glaring examples where score is perfect

if ($sscore == "100")
{
	$smissed = "0"; 
}

echo "Student: $sname ($semail) | Score: $sscore | Missed Word Count: $smissed <br> ";
echo "Text submitted: <i>$seffort</i> <br>Date: $readable_date<br><br>";

$i++;
}

echo "<br><br></div></p></form>$ispraak_footer</div><img id=\"bottom\" src=\"bottom.png\" alt=\"\"></body></html>";
			

//close your connection to the DB
mysqli_close($msi_connect);


/*


//stats for frequency errors... 

if ($a !== "ja" && $a !== "zh" && $a !== "hi" && $a !== "ar" && $a !== "ko" && $a !== "he" && $a !== "am" && $a !== "fa")
{

//$trouble_words = explode(" ", $c);
//$numtw = count($trouble_words);


//add a space to front of array to resolve first word problem
//could also do this to the model array to see if it works
//if not, could un-shift both arrays to pre-pend a space
//array_unshift($trouble_words, " ");

echo "<a href=\"stats.php?mykey=$mykey\">Analyze Error Frequency</a><br><br>"; 

}
else
{

echo "<a href=\"stats_nla.php?mykey=$mykey\">Error Frequency Analysis</a><br><br>"; 

}

//this is where magic needs to happen


*/


?>