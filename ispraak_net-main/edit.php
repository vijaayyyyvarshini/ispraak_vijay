<?php

//This page is for adding a user-selected audio file to serve as an activity prompt

session_start();
//Continues Session from previous PHP page

//Comment the below off to turn off error warnings
error_reporting(0);

//Get database variables: this path is  confirmed 
include_once("../../config_ispraak.php");

//get the file name first, and also the unique key for the database update command

$mylink = $_SESSION['mp3link'];
$mykey = $_SESSION['mykey'];
$mykey2 = $_SESSION['mykey2'];

//Connect to the database

$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

if (mysqli_connect_errno())
{
  	echo "Unable to connect to the database. Please try again later.";
  	//echo "Failed to connect to MySQL because: " . mysqli_connect_error();
}

//define the query into a string

$mylink = mysqli_real_escape_string($msi_connect, $mylink);
$mykey = mysqli_real_escape_string($msi_connect, $mykey);

//define the query
//$query = "INSERT INTO ispraak VALUES ('$email', '$language', '$audiofile', '$blocktext','$mykey')";

$query = "UPDATE ispraak SET audiofile='$mylink' WHERE mykey='$mykey'";

//execute the query
mysqli_query($msi_connect, $query);

//echo "Database updated."; 

$lti_message = "Your activity has been saved & e-mailed to you.</b> You can also copy the links below (right click) in case the automated e-mail does not reach you."; 

$context_id=$_COOKIE['context_id'];
if (strlen($context_id) > 3)
{
	$lti_message = "Great news! <br><br>Your activity has been created in your LMS.</b> Press the REFRESH button in your dashboard to see this activity. ";
}



echo "$ispraak_header <form id=\"form_1007732\" class=\"ispraak_form\"  method=\"post\" action=\"makeit.php\">
<div class=\"form_description\">$ispraak_logo Success!<br><br> The audio has been updated and your activity has been created! <br><br></div>

<br><br><center>

<a target=\"_blank\" href=\"$domain_name/ispraak.php?mykey=$mykey&mykey2=$mykey2\">Review Activity (Student Link)</a> 
			
			</center>
			<br>
	</p>
			<ul >
			</ul>
		</form>	
$ispraak_footer
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
</html>";


//close your connection to the DB
mysqli_close($msi_connect);
?>
