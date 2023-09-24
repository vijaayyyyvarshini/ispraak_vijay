<?php

session_start();
//Starts or continues session from previous PHP page

//Get database variables: this path is  confirmed 
include_once("../../config_ispraak.php");


//Connect to the database
$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

if (mysqli_connect_errno())
{
  	echo "Unable to connect to the database. Please try again later.";
  	//echo "Failed to connect to MySQL because: " . mysqli_connect_error();
}

//change this later, just for testing
//$mykey = "1431351886";

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

//Create local cookies in case session variable breaks
 
setcookie("cookie_mykey", $mykey, time()+7200, '/'); 
setcookie("cookie_instructor_email", $d, time()+7200, '/'); 
setcookie("cookie_block_text", $c, time()+7200, '/'); 
setcookie("cookie_language", $a, time()+7200, '/'); 
setcookie("cookie_student_name", $student_name, time()+7200, '/'); 
setcookie("cookie_student_email", $student_email, time()+7200, '/'); 

//See if Google can synth the voice, assume it cannot

$synth = "none";
$synth_text = ""; 
$synth_button = "";

//should the iSpeech synth be available too?
//French, Spanish, German, Italian, Chinese, Japanese
//Korean, Russian, Portuguese, Turkish
//Arabic Male / Greek Female
//Assume that it SHOULD be displayed
//and turn it OFF for languages it won't work

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

$iframe_text = "<br><br>Error: unable to determine source language!<br><br>";

if ($mylang == "fr")
{
$iframe_text = "<iframe src=\"languages/french_france.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$synth = "fr-FR";
}
if ($mylang == "es")
{
$iframe_text = "<iframe src=\"languages/spanish_mexico.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$synth = "es-MX";
}
if ($mylang == "de")
{
$iframe_text = "<iframe src=\"languages/german_germany.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
//german seems problematic with these codes
$synth = "de-DE"; 
}

//turn off iSpeech for English, Vietnamese, Hindi, & Croatian

if ($mylang == "en")
{
$iframe_text = "<iframe src=\"languages/english_usa.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$synth = "en-US";

//Currently iSpeeech for English is OFF, but you can uncomment below to turn it on
//	$i_speech = ""; 

}

if ($mylang == "hi")
{
$iframe_text = "<iframe src=\"languages/hindi_india.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$synth = "hi-IN";
$i_speech = ""; 
}


if ($mylang == "sw")
{
$iframe_text = "<iframe src=\"languages/swahili_kenya.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
//$synth = "sw-TZ";
$i_speech = ""; 
}

if ($mylang == "zu")
{
$iframe_text = "<iframe src=\"languages/zulu_south_africa.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
//$synth = "zu-ZA";
$i_speech = ""; 
}

if ($mylang == "am")
{
$iframe_text = "<iframe src=\"languages/amharic_ethiopia.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
//$synth = "am-ET";
$i_speech = ""; 
}

if ($mylang == "he")
{
$iframe_text = "<iframe src=\"languages/hebrew.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
//$synth = "he-HE";
$i_speech = ""; 

//also change $c variable to include align right text
//$before_c = "<div class=\"ex1\">";
//$after_c = "</div>";

$before_c = "<table width=\"600\" border=\"0\"><tr><td align=\"right\"><div class=\"ex1\">";
$after_c = "</div></td></tr></table></p>";

$c = $before_c . $c . $after_c;

}

if ($mylang == "fa")
{
$iframe_text = "<iframe src=\"languages/persian.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$i_speech = ""; 

//also change $c variable to include align right text
$before_c = "<table width=\"600\" border=\"0\"><tr><td align=\"right\"><div class=\"ex1\">";
$after_c = "</div></td></tr></table></p>";

$c = $before_c . $c . $after_c;


}

if ($mylang == "vi")
{
$iframe_text = "<iframe src=\"languages/vietnamese_vietnam.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$i_speech = ""; 
}

if ($mylang == "hr")
{
$iframe_text = "<iframe src=\"languages/croatian_croatia.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$i_speech = ""; 
}

//languages added a bit later

if ($mylang == "it")
{
$iframe_text = "<iframe src=\"languages/italian_italy.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$synth = "it-IT";
}

//Chinese has a transliteration option for pinyin through Glosbe

$zhtext = "";
$pinyin = ""; 

if ($mylang == "zh")
{
$iframe_text = "<iframe src=\"languages/chinese_china.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$synth = "zh-CN";

$bt = $_SESSION['block_text']; 

$bt = urlencode ($bt); 

$json = file_get_contents("https://glosbe.com/transliteration/api?from=Han&dest=Latin&text=$bt&format=json");
$obj = json_decode($json);
$pinyin = $obj->{'text'}; 
$pinyin2 = $obj->{'result'};

$zhtext = "<img alt=\"iSpraak\" src=\"pinyin.png\" align=\"right\" width=\"30\" id=\"998\" onclick=\"myFunction5()\">"; 

}

//Russian has a transliteration option for Latin text through Glosbe

if ($mylang == "ru")
{
$iframe_text = "<iframe src=\"languages/russian_russia.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";

$bt = $_SESSION['block_text']; 
$bt = urlencode ($bt); 

$json = file_get_contents("https://glosbe.com/transliteration/api?from=Cyrillic&dest=Latin&text=$bt&format=json");
$obj = json_decode($json);
$pinyin = $obj->{'text'}; 
$pinyin2 = $obj->{'result'};

$zhtext = "<img alt=\"iSpraak\" src=\"latin.png\" align=\"right\" width=\"30\" id=\"998\" onclick=\"myFunction5()\">"; 


}

if ($mylang == "ja")
{
$iframe_text = "<iframe src=\"languages/japanese_japan.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$synth = "ja-JP";
}

if ($mylang == "ko")
{
$iframe_text = "<iframe src=\"languages/korean_korea.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
$synth = "ko-KR";
}

if ($mylang == "pt")
{
$iframe_text = "<iframe src=\"languages/portuguese_brazil.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
}


if ($mylang == "pl")
{
$iframe_text = "<iframe src=\"languages/polish_poland.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
}

if ($mylang == "ar")
{
$iframe_text = "<iframe src=\"languages/arabic_sa.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";

//also change $c variable to include align right text
//$before_c = "<div align=\"right\">";
//$after_c = "</div>";

$before_c = "<table width=\"600\" border=\"0\"><tr><td align=\"right\"><div class=\"ex1\">";
$after_c = "</div></td></tr></table></p>";

$c = $before_c . $c . $after_c;

//$i_speech = $i_speech_arabic;

}

if ($mylang == "el")
{
$iframe_text = "<iframe src=\"languages/greek_greece.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
}

if ($mylang == "tr")
{
$iframe_text = "<iframe src=\"languages/turkish_turkey.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
}

if ($mylang == "nl")
{
$iframe_text = "<iframe src=\"languages/dutch_netherlands.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
}


if ($mylang == "ca")
{
$iframe_text = "<iframe src=\"languages/catalan_spain.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
}

if ($mylang == "cs")
{
$iframe_text = "<iframe src=\"languages/czech_czechia.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
}

if ($mylang == "sv")
{
$iframe_text = "<iframe src=\"languages/swedish_sweden.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";
}

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

//also disable ispeech

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

//write an IF statement for certain languages-- this will appear for French, Spanish, German, Italian, Chinese, Korean, Japanese, Russian, Portuguese, Turkish
//look into extent of support for Arabic and Greek with iSpeech-- single gender support 
//arabic having display problems
//maybe NOT English? since browser voice working so well
//could display SYNTH original buttons just for English? 
//vietnamese and croatian don't seem to have any support and HINDI doesn't either (though my mac works for Hindi)

$iframe_text2 = "<iframe src=\"premium_tri.php?lang=$mylang&mykey=$mykey&btext=$c\" align=\"right\" width=\"135\" height=\"50\" scrolling=\"no\" frameBorder=\"0\"></iframe>";

//sometimes a student might leave the email blank or instructor sends student to wrong page
//bypassing the register screen. this if else statements tries to correct for that


//as of May 1, 2022, trying to only use ONE file for all languages
$iframe_text = "<iframe src=\"languages/english_usa.html\" width=\"600\" height=\"340\" scrolling=\"no\" frameBorder=\"0\"></iframe>";




if ($student_email == "" || $student_name == "")
{
	header('Location: ispraak.php?mykey='.$mykey.'&error=86&mykey2='.$mykey2);
	//echo "<br><br><h3><center> Oops! Something went awry!</h3><br><center>Please try this page: <a href=\"ispraak.php?mykey=$mykey\">REDIRECT</a></center><br><br><center>Please enter your e-mail and name when prompted!<br><br><br><center><img src=\"hal_error.png\" width=50>";
}
else
{

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
			<br><br><br>Use the audio buttons to listen and the microphone to practice the following:</b><br>$i_speech<br><div class=\"target\">$c</div><br>$extra_audio2<br></p>
			
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