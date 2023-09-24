<?php 

session_start();
//Starts or continues session from previous PHP page

//Comment the below off to turn off error warnings
error_reporting(0);

//Fancy PHP class for pointing out differences
//Trying to find another solution for this, so will leave commented out
//include_once("finediff.php");

//Get database variables: this path is  confirmed 
//include_once("../../../config_ispraak.php");
include_once("../../config_ispraak.php");

//all pages must display UTF-8 for any special chars

header('Content-type: text/html; charset=utf-8');
echo "<head><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"> <link rel=\"stylesheet\" type=\"text/css\" href=\"css/ispraak.css\"><title>iSpraak Frame</title></head><body style=\"background-color:white\">";

//get whole shebang transcript, not just the halves
$sbang= $_GET['sbang'];

//Display container for this page, regardless of session variables
//Session timeout message should show within CSS content wrapper
//Les enfants sont avec leurs amis dans le jardin à côté du café.

$block_text = $_SESSION['block_text'];

$student_name = $_SESSION['student_name'];

$iemail = $_SESSION['instructor_email'];

$student_email = $_SESSION['student_email'];

//testing ispraak session varialbe fix
//if the php session is destroyed for some reason, like inactivity or load balancing, use the cookies as a backup 

if (isset($_SESSION['student_email'])) 
{
	$unusedvariable = "999"; 
}
else
{

	$block_text = $_COOKIE["cookie_block_text"];
	//$block_text = "Unable to find this file.";  
	$student_name = $_COOKIE["cookie_student_name"];  
	$iemail = $_COOKIE["cookie_instructor_email"];  
	$student_email = $_COOKIE["cookie_student_email"];  
	$activity_id = $_COOKIE["cookie_mykey"]; 
	$mylang = $_COOKIE["cookie_language"]; 

	//now reset into session variables again

	$_SESSION['mykey'] = $activity_id;
	$_SESSION['language'] = $mylang;
	$_SESSION['student_name'] = $student_name;
	$_SESSION['instructor_email'] = $iemail;
    $_SESSION['student_email'] = $student_email;
	

}

//this whole thing works fine without the above ifisseet condition
//only flakes if a php session variable is destroyed

//but what about no cookies either?

if ($student_email == "") 
{
	echo "<br><br><center>Oops! There was an error saving your e-mail address. <br><br> Please relaunch this activity from the original link.<br><br>If you are blocking cookies or running an ad-blocker, please disable it now.<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>"; 	
}

//echo "Your name: $student_name"; 

//echo "<center><br><br>The model sentence was: <b>$block_text</b><br><br>"; 

$result = $sbang;

$result = stripslashes($result);

echo "I think you said: $result";

//How is this string encoded?
//echo mb_detect_encoding($ftext);

$good_text = $block_text;

similar_text("$result", "$good_text", $sim);

$sim=round($sim); 

//echo "<br><h3>Score of $sim%</h3>";

//get more accurate score by ignoring case sensitivity and removing punctuation

   $myitems7 = array ( "!","¡",":","。","，","。",",","\"","?", ".", "¿","”","“"," ");

   $phrase1 = str_replace($myitems7,"",$result); 
   $phrase2 = str_replace($myitems7,"",$good_text); 
   
   $str1 = strtolower($phrase1);
   $str2 = strtolower($phrase2);
   
similar_text("$str1", "$str2", $sim);

$sim=round($sim);    

echo "<br><h3>Score of $sim%</h3>";

//echo "s1: $str1 ---- s2: $str2<br><br>";

//change the letter code here for Forvo.com's seach function
//French is FR, Spanish is ES
//get language $_SESSION['language']

$forvo_code = "99"; 

$mylang = $_SESSION['language'];

if ($mylang == "fr")
{
$forvo_code = "fr"; 
}
if ($mylang == "hi")
{
$forvo_code = "hi"; 
}
if ($mylang == "es")
{
$forvo_code = "es"; 
}
if ($mylang == "de")
{
$forvo_code = "de"; 
}
if ($mylang == "en")
{
$forvo_code = "en"; 
}
if ($mylang == "it")
{
$forvo_code = "it"; 
}
if ($mylang == "ja")
{
$forvo_code = "ja"; 
}
if ($mylang == "pt")
{
$forvo_code = "pt"; 
}
if ($mylang == "ru")
{
$forvo_code = "ru"; 
}
if ($mylang == "ko")
{
$forvo_code = "ko"; 
}
if ($mylang == "hr")
{
$forvo_code = "hr"; 
}
if ($mylang == "zh")
{
$forvo_code = "zh"; 
}
if ($mylang == "ar")
{
$forvo_code = "ar"; 
}
if ($mylang == "el")
{
$forvo_code = "el"; 
}
if ($mylang == "vi")
{
$forvo_code = "vi"; 
}
if ($mylang == "tr")
{
$forvo_code = "tr"; 
}
if ($mylang == "nl")
{
$forvo_code = "nl"; 
}

if ($mylang == "ca")
{
$forvo_code = "ca"; 
}
if ($mylang == "cs")
{
$forvo_code = "cs"; 
}
if ($mylang == "sv")
{
$forvo_code = "sv"; 
}

if ($mylang == "he")
{
$forvo_code = "he"; 
}

if ($mylang == "pl")
{
$forvo_code = "pl"; 
}

if ($mylang == "zu")
{
$forvo_code = "zu"; 
}

if ($mylang == "sw")
{
$forvo_code = "sw"; 
}

if ($mylang == "am")
{
$forvo_code = "am"; 
}

if ($mylang == "fa")
{
$forvo_code = "fa"; 
}

if ($mylang == "no")
{
$forvo_code = "no"; 
}

if ($mylang == "da")
{
$forvo_code = "da"; 
}

if ($mylang == "fi")
{
$forvo_code = "fi"; 
}

if ($mylang == "hu")
{
$forvo_code = "hu"; 
}


if ($mylang == "uk") { $forvo_code = "uk"; }
if ($mylang == "ur") { $forvo_code = "ur"; }
if ($mylang == "ro") { $forvo_code = "ro"; }
if ($mylang == "id") { $forvo_code = "ind"; }
if ($mylang == "bn") { $forvo_code = "bn"; }



//Need to separate Chinese and Japanese Characters somehow

if ($mylang == "zh" || $mylang == "ja")
{
	//effort chinese text to be broken up
	//also need to break up model chinese text
	
	$str = $result;

	$split=1; 
	
    $funarray = array();
     
    for ( $i=0; $i < strlen( $str ); )
    { 
        $value = ord($str[$i]); 
    
        if($value > 127)
        { 
            if($value >= 192 && $value <= 223) 
                $split=2; 
            elseif($value >= 224 && $value <= 239) 
                $split=3; 
            elseif($value >= 240 && $value <= 247) 
                $split=4; 
        }
        else
        { 
            $split=1; 
        } 
            $key = NULL; 
        
        	for ( $j = 0; $j < $split; $j++, $i++ ) 
        	{ 
            $key .= $str[$i]; 
        	}
        	 
        array_push( $funarray, $key ); 
    } 

//so funarray becomes effort_words array
//now do same thing for MODEL text, call it nofunarray for model_words array

	$str = $good_text;

	$split=1; 
	
    $nofunarray = array();
     
    for ( $i=0; $i < strlen( $str ); )
    { 
        $value = ord($str[$i]); 
    
        if($value > 127)
        { 
            if($value >= 192 && $value <= 223) 
                $split=2; 
            elseif($value >= 224 && $value <= 239) 
                $split=3; 
            elseif($value >= 240 && $value <= 247) 
                $split=4; 
        }
        else
        { 
            $split=1; 
        } 
            $key = NULL; 
        
        	for ( $j = 0; $j < $split; $j++, $i++ ) 
        	{ 
            $key .= $str[$i]; 
        	}
        	 
        array_push( $nofunarray, $key ); 
    } 

$model_words = $nofunarray;
$effort_words = $funarray;

}
else
{

//need to simplify strings before they become arrays, not after.
//start with lowercasing all elements
//$block_text = strtolower($block_text)
//$result = strtolower($result)
//above not working, don't know why; whole page won't load with it uncommneted 

//2017 BEFORE EXPLODE of MODEL, take out punctuation
//Dont take out spaces

$myitems = array ( "!","¡",":","。","，","。",",","?","\"", ".","”","“","¿");
$block_text = str_replace($myitems,"",$block_text); 

//Is above what I needed? 


$block_text = strtolower($block_text);
$result = strtolower($result);

$model_words = preg_split('/\s+/', $block_text);
$effort_words = preg_split('/\s+/', $result);

//$model_words = explode(" ", $block_text);
//$effort_words = explode(" ", $result);

}

//lowercase everything
//$model_words = strtolower($model_words);
//$effort_words = strtolower($effort_words);

//toss out punctuation, but keep spaces

// $myitems8 = array ( "!","¡", ",", "?", ".", "¿");
// $model_words2 = str_replace($myitems8,"",$model_words); 
// $effort_words2 = str_replace($myitems8,"",$effort_words); 
   

//$missing_words = array_udiff($model_words, $effort_words, 'strcasecmp');
$missing_words = array_diff($model_words, $effort_words);


//echo "<br>I didn't hear these words:"; 

//print_r($missing_words);

//don't know about this function
//re-indexes pointers well
$missing_words = array_values(array_filter($missing_words));

/*echo "<br><br>DEBUGGING:<br><br>";
echo "MODEL:<br>";
var_dump($model_words);
echo "<br><br>EFFORT:<br>";
var_dump($effort_words);
echo "<br><br>MISSING:<br>";
var_dump($missing_words);
*/

$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

if (mysqli_connect_errno())
{
  	echo "Unable to connect to the database. Please try again later!";
  	//echo "Failed to connect to MySQL because: " . mysqli_connect_error();
}




if ($sim < 100)
{

echo "<br>Use <b>Forvo.com</b> to review these words: <br><br>"; 

for ($i = 0; $i < count($missing_words); $i++)
{
   $thisword = $missing_words[$i];
   
   //strip punctuation
   //$thisword = preg_replace('/[^a-z0-9]+/i', '', $thisword);
   
   $myitems = array ( "!","¡",":","。","，","。",","," ","?","\"","”","“", ".", "¿");
   
   $thisword = str_replace($myitems,"",$thisword); 
   
   if ($thisword != "")
   {
   echo "[<a href=\"http://www.forvo.com/search-$forvo_code/$thisword\" class=\"cutelink\" target=\"_blank\">$thisword</a>] ";
   }
	   
   //for each bad word, throw it into the DB under ispraak_stats
   $activity_id9 = $_SESSION['mykey'];
   $misc9 = "999";
   $query88 = "INSERT INTO ispraak_stats VALUES ('$activity_id9','$iemail','$student_email','$thisword','$misc9','')";
	//execute the query
	mysqli_query($msi_connect, $query88);
   
   
}

}

$top_score = 0; 
$activity_id9 = $_SESSION['mykey'];
$result2020 = mysqli_query($msi_connect, "SELECT * FROM ispraak_grades WHERE activity_id='$activity_id9' ORDER BY score DESC");
//$row = mysql_fetch_array($myresult);
$row2020 = mysqli_fetch_array($myresult2020);
//$num2020=mysql_numrows($result2020);
$num2020 = $result2020->num_rows;


//$top_score=$row2020["score"];
$i2 = 0; 
$top_score=mysqli_result($result2020,$i2,"score");

$praise = "<br><br>Your homework has been saved!<img src=\"images/bars.png\" width=\"20\"></a><br>Results sent to $iemail<br><br>"; 

if ($top_score <= $sim)
{
	$praise = "<p><img src=\"images/topscore.png\" align=\"middle\" width=\"80\"><h2>You have the top score in this class!</h2>";
}

echo "$praise"; 






if ($sim > 93)
{
echo "<h2>Great Job!</h2>";
}
else
{
echo "<h2>Keep practicing!</h2>";
} 

//end the GREAT JOB IF STATEMENT

//Get all info required for insertion

//$faid = $_SESSION['faid'];
//$fscore = $sim;
//$fname = $_SESSION['name'];
//$femail = $_SESSION['email'];

$misc = "undefined"; 

$student_name = $_SESSION['student_name'];
$teacher_email = $_SESSION['instructor_email'];
$student_email = $_SESSION['student_email'];
$score = $sim; 
$effort = $result;
$activity_id = $_SESSION['mykey'];
$timestamp = time();
$missed_words = count($missing_words);

//connect to the DB

$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

if (mysqli_connect_errno())
{
  	echo "Unable to connect to the database. Please try again later.";
  	//echo "Failed to connect to MySQL because: " . mysqli_connect_error();
}


//Glitch discovered with apostrophes on text saved into database
//already took out slashes, but now need to do string prep
//this prep must occur after connection is opened

$effort = mysqli_real_escape_string($msi_connect, $effort);
$student_name = mysqli_real_escape_string($msi_connect, $student_name);

//Define query

$query = "INSERT INTO ispraak_grades VALUES ('$student_name','$student_email','$score','$effort','$activity_id','$teacher_email','$timestamp','$missed_words','$misc', '')";

//changed the empty quotes at the end to explicitly read NULL--- wasn't saving otherwise

//execute the query


mysqli_query($msi_connect, $query)  or die(mysqli_error()."<br>iSpraak error saving results to database! Please report to dnickol1@slu.edu immediately! ");


//check if this is an LMS request
$context_id = $_COOKIE["context_id"];

if (strlen($context_id) > 3)
{
	$lti_message = "YES";
	$role = "student"; 
	$misc999 = "999"; 
	$query2 = "INSERT INTO ispraak_lti VALUES ('$context_id', '$activity_id', '$student_email', '$role','$misc999','$misc999','$timestamp','')";
	mysqli_query($msi_connect, $query2);
}


//close your connection to the DB

mysqli_close($msi_connect);


?>