<?php

session_start();
//Starts or continues session from previous PHP page

//Get all from query string 
$mykey=$_GET['mykey'];
$mylang=$_GET['lang'];
$btext=$_GET['btext'];

$option = "all";

//keep in mind the idea to have Brazil/Portugal flags (male/female)
//and same for Chinese dialects (two females, different regions)


if ($mylang == "sv" or $mylang == "cs" or $mylang == "ca" or $mylang == "ja" or $mylang == "ko" or $mylang == "el" or $mylang == "nl" or $mylang == "pl" or $mylang == "da" or $mylang == "hu" or $mylang == "fi" or $mylang == "no")
{
//only have female voices, don't we
$option = "set1"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=s\"><img src=\"images/turtle.png\" width=\"35\"  align=\"right\"></a>"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext\"><img src=\"images/lady.png\" width=\"35\" align=\"right\"></a>"; 
}


if ($mylang == "ar")
{
//only have male voices, don't we
$option = "set2"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=s\"><img src=\"images/turtle.png\" width=\"35\"  align=\"right\"></a>"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=m\"><img src=\"images/dude.png\" width=\"35\" align=\"right\"></a>"; 
}

if ($mylang == "zh")
{
//two female voices different regions
$option = "set3"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=s\"><img src=\"images/turtle.png\" width=\"35\"  align=\"right\"></a>"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=m\"><img src=\"images/lady2.png\" width=\"35\" align=\"right\"></a>"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext\"><img src=\"images/lady.png\" width=\"35\" align=\"right\"></a>"; 
}

if ($mylang == "pt")
{
//man and woman, two regions for portuguese
$option = "set4"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=s\"><img src=\"images/turtle.png\" width=\"35\"  align=\"right\"></a>"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=m\"><img src=\"images/dude_pt.png\" width=\"35\" align=\"right\"></a>"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext\"><img src=\"images/lady_br.png\" width=\"35\" align=\"right\"></a>"; 
}

if ($option == "all")
{
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=s\"><img src=\"images/turtle.png\" width=\"35\"  align=\"right\"></a>"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext&tts_pref=m\"><img src=\"images/dude.png\" width=\"35\" align=\"right\"></a>"; 
echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext\"><img src=\"images/lady.png\" width=\"35\" align=\"right\"></a>"; 
}


?>

