<?php

//Starts or continues session from previous PHP page
session_start();

//Get config file variables and functions
include_once("../../config_ispraak.php");

//Connect to the database
$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

if (mysqli_connect_errno())
{
  	echo "Unable to connect to the database. Please try again later.";
  	//echo "Failed to connect to MySQL because: " . mysqli_connect_error();
}

//Get mykey from query string & declare session variable
$mykey=$_GET['mykey'];
$_SESSION['mykey']=$mykey;
$mykey2=$_GET['mykey2'];
$_SESSION['mykey2']=$mykey2;

//Get student info from the form & declare session variables
$student_name=$_POST['student_name'];
$student_email=$_POST['student_email'];

//check for query string for anonymous submission
//and define student name and email as ANON

$mykey3=$_GET['mykey3'];
if  ($mykey3 == "anonymous")
{
$student_name="iSpraak Guest"; 
$student_email="guest@ispraak.net";
}

//this may be an LTI request, so get info from cookies
$origin=$_GET['entry'];

if ($origin==="808")
{
	$student_name = $_COOKIE["lis_person_name_full"]; 
	$student_email = $_COOKIE["lis_person_contact_email_primary"]; 
	
	//also a mykey cookie but its in the query string anyway
}

//declare session variables for student name and email

$_SESSION['student_name'] = $student_name;
$_SESSION['student_email'] = $student_email;

//Save students trouble from logging in multiple times for each activity
//if they are just being e-mailed links and are not logged in otherwise

$_SESSION['start_name'] = $student_name;
$_SESSION['start_email'] = $student_email;

//Get variables from iSpraak table

$myresult = mysqli_query($msi_connect, "SELECT * FROM ispraak where mykey='$mykey' AND mykey2='$mykey2'");
$row = mysqli_fetch_array($myresult);
$a=$row["language"];
$b=$row["audiofile"];
$c=$row["blocktext"];
$d=$row["email"];

//Create local cookies for javascript calls OR in case session variable breaks
 
setcookie("cookie_mykey", $mykey, time()+7200, '/'); 
setcookie("cookie_instructor_email", $d, time()+7200, '/'); 
setcookie("cookie_block_text", $c, time()+7200, '/'); 
setcookie("cookie_language", $a, time()+7200, '/'); 
setcookie("cookie_student_name", $student_name, time()+7200, '/'); 
setcookie("cookie_student_email", $student_email, time()+7200, '/'); 

//See if Google can javascript synth the voice, assume it cannot

$synth = "none";
$synth_text = ""; 
$synth_button = "";

//Decide to show iSpeech synth buttons are not
//Assume that it SHOULD be displayed and turn it OFF for languages it won't work
//iSpeech is deactivated by the followin call - $i_speech = ""; 

$mylang = $a; 
$i_speech = ""; 
$i_speech = "<iframe src=\"premium_tri.php?lang=$mylang&mykey=$mykey&btext=$c\" align=\"right\" width=\"135\" height=\"50\" scrolling=\"no\" frameBorder=\"0\"></iframe>";

//arabic needs to have this iframe aligned left because it is a right-to-left language
//and otherwise causes display problems

$i_speech_arabic = "<iframe src=\"premium_tri.php?lang=$mylang&mykey=$mykey&btext=$c\" align=\"left\" width=\"135\" height=\"50\" scrolling=\"no\" frameBorder=\"0\"></iframe>";

//Make instructor e-mail into session variable

$_SESSION['instructor_email'] = $d;

//Make the blocktext a session variable for check-errors.php

$_SESSION['block_text'] = $row["blocktext"];

//Figure out correct language codes for Forvo & Google Default

$_SESSION['language'] = $row["language"];

$mylang = $row["language"];

$iframe_text = "<br><br>Error: Unable to determine source language!<br><br>";

if ($mylang == "fr") { $synth = "fr-FR"; }
if ($mylang == "it") { $synth = "it-IT";}
if ($mylang == "es") { $synth = "es-MX"; }
if ($mylang == "de") { $synth = "de-DE"; }
if ($mylang == "en") { $synth = "en-US"; }


//turn off iSpeech for Vietnamese, Hindi, & Croatian

if ($mylang == "hi") { $synth = "hi-IN"; $i_speech = ""; }

//turn off iSpeech AND synth for Amharic, Croatian, Swahili, Vietnamese, Zulu, etc.

if ($mylang == "am") { $i_speech = ""; }
if ($mylang == "hr") { $i_speech = ""; }
if ($mylang == "sw") { $i_speech = ""; }
if ($mylang == "vi") { $i_speech = ""; }
if ($mylang == "zu") { $i_speech = ""; }

if ($mylang == "ur") { $i_speech = ""; }
if ($mylang == "uk") { $i_speech = ""; }
if ($mylang == "bn") { $i_speech = ""; }
if ($mylang == "id") { $i_speech = ""; }
if ($mylang == "ro") { $i_speech = ""; }


//Hebrew and Farsi and Urdu adjustments for RTL language 

if ($mylang == "he") 
{ 
$i_speech = ""; 
//also change $c variable to include align right text
$before_c = "<table width=\"600\" border=\"0\"><tr><td align=\"right\"><div class=\"ex1\">";
$after_c = "</div></td></tr></table></p>";
$c = $before_c . $c . $after_c;
}

if ($mylang == "fa")
{
$i_speech = ""; 
//also change $c variable to include align right text
$before_c = "<table width=\"600\" border=\"0\"><tr><td align=\"right\"><div class=\"ex1\">";
$after_c = "</div></td></tr></table></p>";
$c = $before_c . $c . $after_c;
}

if ($mylang == "ur") 
{ 
$i_speech = ""; 
//also change $c variable to include align right text
$before_c = "<table width=\"600\" border=\"0\"><tr><td align=\"right\"><div class=\"ex1\">";
$after_c = "</div></td></tr></table></p>";
$c = $before_c . $c . $after_c;
}

//Chinese has a transliteration option for pinyin through Glosbe

$zhtext = "";
$pinyin = ""; 

if ($mylang == "zh") 
{
$synth = "zh-CN";
$bt = $_SESSION['block_text']; 
$bt = urlencode ($bt); 
$json = file_get_contents("https://glosbe.com/transliteration/api?from=Han&dest=Latin&text=$bt&format=json");
$obj = json_decode($json);
$pinyin = $obj->{'text'}; 
$pinyin2 = $obj->{'result'};
$zhtext = "<img alt=\"iSpraak\" src=\"images/pinyin.png\" align=\"right\" width=\"30\" id=\"998\" onclick=\"myFunction5()\">"; 
}

//Russian has a transliteration option for Latin text through Glosbe

if ($mylang == "ru")
{
$bt = $_SESSION['block_text']; 
$bt = urlencode ($bt); 
$json = file_get_contents("https://glosbe.com/transliteration/api?from=Cyrillic&dest=Latin&text=$bt&format=json");
$obj = json_decode($json);
$pinyin = $obj->{'text'}; 
$pinyin2 = $obj->{'result'};
$zhtext = "<img alt=\"iSpraak\" src=\"images/latin.png\" align=\"right\" width=\"30\" id=\"998\" onclick=\"myFunction5()\">"; 
}

//trouble accessing transliteration API as of April 2022
$pinyin = "Sorry. The transliteration feature is currently unavailable.";

if ($mylang == "ja") { $synth = "ja-JP"; }
if ($mylang == "ko") { $synth = "ko-KR"; }
if ($mylang == "pt") { $synth = "none"; }
if ($mylang == "pl") { $synth = "none"; }

if ($mylang == "ar")
{
$iframe_text = "<iframe src=\"languages/arabic_sa.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$before_c = "<table width=\"600\" border=\"0\"><tr><td align=\"right\"><div class=\"ex1\">";
$after_c = "</div></td></tr></table></p>";
$c = $before_c . $c . $after_c;
}

if ($mylang == "el") { $synth = "none"; }
if ($mylang == "tr") { $synth = "none"; }
if ($mylang == "nl") { $synth = "none"; }
if ($mylang == "ca") { $synth = "none"; }
if ($mylang == "cs") { $synth = "none"; }
if ($mylang == "sv") { $synth = "none"; }

//Decide if we should display the MP3 audio player or not
//if the variable B is = 2, then a display it

$player_text = " ";

if ($b !== "1")
{
//$player_text = "<br><EMBED SRC=\"http://phrants.net/ispraak/uploadmp3/$b\" HEIGHT=30 WIDTH=200><br>";

$player_text = "<audio controls><source src=\"uploadmp3/$b\" type=\"audio/mpeg\">Your browser does not support the audio playback element.
</audio>";

//also now disable synth since there is audio provided

$synth = "none";

//also disable ispeech since there is audio provided

$i_speech = ""; 

}

if ($synth !== "none")
{

$c2 = addslashes($c);

$synth_text = "<script type=\"text/javascript\">

function myFunction()
		{
		
	speechSynthesis.cancel();	
		
     var uhm = new SpeechSynthesisUtterance();
     uhm.text = '$c2';
     uhm.lang = '$synth';
     uhm.rate = 1.0;
      
     speechSynthesis.speak(uhm);
          
     }  
     
     function myFunction2()
		{
		
			speechSynthesis.cancel();	
		
		
     var ubb = new SpeechSynthesisUtterance();
     ubb.text = '$c2';
     ubb.lang = '$synth';
     ubb.rate = 0.8;
     
     speechSynthesis.speak(ubb);
     } 
     
            function myFunction5()
		{
		
     	document.getElementById(\"alert\").style.display = \"block\";
     }
     

     
  	</script>";
  	
$synth_button = "<img alt=\"iSpraak\" src=\"images\synthx2.gif\" align=\"right\" width=\"30\" id=\"998\" onclick=\"myFunction2()\"><img alt=\"iSpraak\" src=\"images\synthx.gif\" align=\"right\" width=\"30\" id=\"998\" onclick=\"myFunction()\">";

}
else
{

$synth_button = "<script type=\"text/javascript\">
      function myFunction5()
		{
		
     	document.getElementById(\"alert\").style.display = \"block\";
     }
     	</script>";


}


$extra_audio_js = "<script language=\"javascript\" type=\"text/javascript\">
<!--
function popitup(url) {
	newwindow=window.open(url,'name','height=130,width=350');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>

";

$extra_audio = "$extra_audio_js <a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$c\" onclick=\"return popitup('tts_call.php?lang=$mylang&mykey=$mykey&btext=$c')\"
	><img src=\"more_audio.png\" align=\"right\" width=\"80\" ></a>";

$extra_audio_safe = "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$c\" onclick=\"javascript:void window.open('tts_call.php?lang=$mylang&mykey=$mykey&btext=$c','_blank',
'width=350,height=120,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\"><img src=\"more_audio.png\" align=\"right\" width=\"80\" ></a>";

$extra_audio2 = ""; 

//notes to self

$iframe_text2 = ""; 
$iframe_text2 = "<iframe src=\"premium_tri.php?lang=$mylang&mykey=$mykey&btext=$c\" align=\"right\" width=\"135\" height=\"50\" scrolling=\"no\" frameBorder=\"0\"></iframe>";

//big change to JUST one page rather than 26 near identical HTML pages
$iframe_text = "<iframe src=\"asr_languages.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";

//make sure a student gets redirected if nothing set for these variables -- it means someone just shared the link from the wrong spot

if ($student_email == "" || $student_name == "")
{
	header('Location: ispraak.php?mykey='.$mykey.'&error=86&mykey2='.$mykey2);
	//echo "<br><br><h3><center> Oops! Something went awry!</h3><br><center>Please try this page: <a href=\"ispraak.php?mykey=$mykey\">REDIRECT</a></center><br><br><center>Please enter your e-mail and name when prompted!<br><br><br><center><img src=\"hal_error.png\" width=50>";
}
else
{


//<span style=\"font-size:small\">Push the mic button and practice speaking the text below:</span>

echo "

<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\" media=\"all\">
<script type=\"text/javascript\" src=\"javascript/javascript.js\"></script>
</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"images/top.png\" alt=\"\">
	<div id=\"form_container\">
	<div id=\"headerBar\"></div>
	
		<form id=\"ispraak\" class=\"ispraak_form\"  method=\"post\" action=\"makeit.php\">
					<div class=\"form_description\">
						
					<img style=\"float: left; padding: 0px 20px 0px 0px\" src=\"images/logo5.png\" height=\"35\" alt=\"iSpraak-Logo\" align=\"left\"> 
			
			$zhtext $synth_button
			<br>

			<span style=\"font-size:medium\">
			<br>$i_speech<br><div class=\"target\">$c</div><br>$extra_audio2<br></span></p>
			
			$player_text
				
		</div>			
		
		
<div class=\"alert\" id=\"alert\" style=\"display:none\">
  <span class=\"closebtn\" onclick=\"this.parentElement.style.display='none';\">&times;</span> 
  <strong><br><br>$pinyin </strong>
</div>
		
					
			<ul >

$iframe_text
			</ul>
		</form>	
		$ispraak_footer
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
</html>
$synth_text
";

}
//end else statement for improper page load

//close your connection to the DB
mysqli_close($msi_connect);

?>