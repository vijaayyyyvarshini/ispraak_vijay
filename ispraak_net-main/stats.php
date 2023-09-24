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

//Get variables from iSpraak GRADES table

$result = mysql_query("SELECT * FROM ispraak_stats WHERE activity_id='$mykey' ORDER BY missed_word ASC");
//$row = mysql_fetch_array($myresult);
$num=mysql_numrows($result);


echo "

<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<title>iSpraak</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"view.css\" media=\"all\">
<script type=\"text/javascript\" src=\"view.js\"></script>


<style>

a:visited {
    background-color: #FFFF85;
}


a:active {
    background-color: #FF704D;
} 


a:link {
    text-decoration: none;
        background-color: #D1F0FF;

}

a:hover {
    background-color: #4B75B3; 
    color:#CCFFFF;
}


</style>




</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"top.png\" alt=\"\">
	<div id=\"form_container\">
	
		<h1><a>iSpraak</a></h1>
		<form id=\"form_1007732\" class=\"appnitro\"  method=\"post\" action=\"makeit.php\">
					<div class=\"form_description\">
			<h2><img src=\"images/logo5.png\" height=\"35\"> <img src=\"bars.png\" alt=\"iSpraak\" align=\"right\" width=\"50\" vspace=\"10\" hspace=\"20\"></h2>
			<br><b>Statistical Error Frequency Count</b> 
			
			
			<br></p>
			
			Error Frequency for $mykey<br><br>
			
		</div>						
			<ul >";
			
//Calculate some stats for teacher, but don't do for Chinese or Japanese
//ja or zh
//if ($a !== "ja" && $a !== "zh")

//want this stuff to float left so the iframe and stats graphics can float right

echo "<div style=\"float:left;width:300px\">";
echo "<SPAN STYLE=\"background-color: #E6E6E6\">Missed Words and Frequency:</SPAN><br><br>"; 


$first_word = mysql_result($result,0,"missed_word");
$stand_count = 0; 
$darray = array();


$resultC = mysql_query("SELECT * FROM ispraak_stats WHERE activity_id='$mykey' AND missed_word='$first_word'");
$numC=mysql_numrows($resultC);

echo "$first_word ($numC) <br><br>"; 



for ($i = 0; $i < $num; $i++)
{
  
  $mw=mysql_result($result,$i,"missed_word");
  
  //build up items in your array for the stats graph

  $darray[] = $mw;  
  
  if ($mw !== "$first_word")
  {
  	echo "$mw "; 
  	
  	
  	$resultB = mysql_query("SELECT * FROM ispraak_stats WHERE activity_id='$mykey' AND missed_word='$mw'");
	$numB=mysql_numrows($resultB);

  	echo "($numB) <br><br>"; 
  	
  	$stand_count = 1;
  	$first_word = $mw;
  }
  else
  {
    $stand_count++; 
  }
}

//end left float

echo "</div>";


//find frequency of items and put them in order
$counts = array_count_values($darray);
arsort($counts);

//print_r(array_count_values($darray));

//print_r($counts);


  // get the first key (the word)
  $key1 = array_shift(array_keys($counts));
  

  // get the first value (the number)
  $word1 = array_shift(array_values($counts));

  //echo "mm: $key1 and $word1";

  unset($counts["$key1"]) ;
  
//print_r($counts);

//get second word and key woot

  // get the first key (the word)
  $key2 = array_shift(array_keys($counts));
  

  // get the first value (the number)
  $word2 = array_shift(array_values($counts));

  //echo "mm: $key1 and $word1";

  unset($counts["$key2"]) ;

//get third word and key woot

  // get the first key (the word)
  $key3 = array_shift(array_keys($counts));
  

  // get the first value (the number)
  $word3 = array_shift(array_values($counts));

  //echo "mm: $key1 and $word1";

  unset($counts["$key3"]) ;


//get fourth word and key woot

  // get the first key (the word)
  $key4 = array_shift(array_keys($counts));
  

  // get the first value (the number)
  $word4 = array_shift(array_values($counts));

  //echo "mm: $key1 and $word1";

  unset($counts["$key4"]) ;




//for certain languages, we need to turn off the graphing label
//since the characters will just show up as boxes
//this may be a non-issue if we dont allow acccess to this page
//for those languages




//echo "<SPAN STYLE=\"background-color: #E6E6E6\">Visual of Top 4 Words Missed:</SPAN>";   

echo "<iframe frameborder=\"0\" height=\"300\" marginheight=\"0\" marginwidth=\"0\" scrolling=\"no\"
src=\"graphite_ispraak.php?a=$word1&b=$word2&c=$word3&d=$word4&w1=$key1&w2=$key2&w3=$key3&w4=$key4\" width=\"300\"></iframe>";


			
echo "

			</ul>
		</form>	
		<div id=\"footer\">
			© D. Nickolai
		</div>
	</div>
	<img id=\"bottom\" src=\"bottom.png\" alt=\"\">
	</body>
</html>";

//close your connection to the DB
mysql_close();


?>