<?php

session_start();

//Get database variables: this path is  confirmed 
include_once("../../config_ispraak.php");


//Get mykey from query string & declare session variable
$mykey=$_GET['mykey'];
$_SESSION['mykey']=$mykey;

//is there an error here?
$error=$_GET['error'];

if ($error == 86)
{
	$error = "<i><b><div class=\"target\">Please enter name and e-mail address to proceed!</div></b></i><br><br>";
}
else
{
	$error = "";
}


if (isset($_SESSION['start_name']))
{
$start_name = $_SESSION['start_name'];
$start_email = $_SESSION['start_email'];
}
else
{
$start_name = "";
$start_email = "";
}

//connect to the DB to verify this is a real activity
//and doesn't need to display alternate McGill form

//$username="phrantsn_dbuser";
//$password="Vr00m86!";
//$database="phrantsn_DansDB";

//mysql_connect(localhost,$username,$password);
//@mysql_select_db($database) or die( "Unable to connect to iSpraak database");

//Connect to the database

$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

if (mysqli_connect_errno())
{
  	echo "Unable to connect to the database. Please try again later.";
  	//echo "Failed to connect to MySQL because: " . mysqli_connect_error();
}



$myresult = mysqli_query($msi_connect, "SELECT * FROM ispraak where mykey='$mykey'");
$j = 0;
$temail=mysqli_result($myresult,$j,"email");

$student_name_field = "Your Name: "; 
$student_name_field2 = "By sharing your name and email, your instructor can find and see your submission! <br><br>If you'd rather stay anonymous, just click <a href=\"#\" class=\"cutelink\">here!</a>"; 
$student_name_field3 = "maxlength=\"255\""; 
$field_status = "";
$student_name_field4 = "Your Email: ";
	
if (strpos($temail,'@mcgill.ca') == true) 
{
	//McGill activity!
	$student_name_field = "Your Initials: ";
	$student_name_field2 = "2-3 initials only"; 
	$student_name_field3 = "maxlength=\"3\""; 
	$start_email = "student@mcgill.ca";
	$field_status = "disabled=\"disabled\"";
	$field_status = "type=\"hidden\"";
	$student_name_field4 = "";

}
 
//Display form

echo "$ispraak_header
	
		<form id=\"form_1007732\" class=\"ispraak_form\"  method=\"post\" action=\"review.php?mykey=$mykey&mykey2=$mykey2\">
			
					<div class=\"form_description\">
		$ispraak_logo
			Welcome to iSpraak! Your teacher <b>($temail)</b> has shared this activity with you. Please enter your info below and let's practice speaking!<br><br>
		</div>		$error				
			<ul >
			
					<li id=\"li_1\" >
		<label class=\"description\" for=\"student_name\">$student_name_field</label>
		<div>
			<input id=\"student_name\" name=\"student_name\" class=\"element text medium\" type=\"text\" $student_name_field3 value=\"$start_name\"/> 
		</div><p class=\"guidelines_on\" id=\"guide_1\">$student_name_field2</p> 
		</li>		
		
					<li id=\"li_2\" >
		<label class=\"description\" for=\"student_email\">$student_name_field4 </label>
		<div>
			<input id=\"student_email\" name=\"student_email\" $field_status class=\"element text medium\" type=\"text\" maxlength=\"255\" value=\"$start_email\"/> 
		</div><p class=\"guidelines\" id=\"guide_1\"></p> 
		</li>		
		
					<li class=\"buttons\">
			    <input type=\"hidden\" name=\"form_id\" value=\"1007732\" />
			    
				<input id=\"saveForm\" class=\"button5\" type=\"submit\" name=\"submit\" value=\"Start Activity\" />
		</li>
			</ul>
			
			
			<div class=\"alert\" id=\"alert\" style=\"display:none\">
  <span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> 
  <strong>Warning: Google Chrome is required to use iSpraak!</strong>
</div>

			
			
		</form>	
		<div id=\"footer\">
			© D. Nickolai
		</div>
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
	
<script>
    if (!('webkitSpeechRecognition' in window)) 
	{
	document.getElementById(\"alert\").style.display = \"block\";
	}
</script>	
	
	
</html>";

?>

