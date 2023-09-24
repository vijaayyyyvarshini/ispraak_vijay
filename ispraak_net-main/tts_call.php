<?php

//this file takes inputs from a query string, calls the iSpeech.org API
//and generates an audio wav file based on the requested parameters

//Comment below lines out to stop error reporting
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//below mostly from iSpeech API download page
//added _tts to file name to keep from overwriting original file
 
include_once('ispeech_tts.php');

//get the developer key from the config file for iSpeech
include_once("../../config_ispraak.php");

//figure out what language from posted variable for language
//figure out what activity ID from posted variable

$mykey=$_GET['mykey'];
$mylang=$_GET['lang'];
$btext=$_GET['btext'];
$c = $btext; 

//also get the parameters of the speech request - male, female, region, slow?
//is it male, female, slow, or region specific (Brazil vs. Portugal?)
$tts_pref=$_GET['tts_pref'];

$error1 = "<img src=\"images/alert.png\" align=right>"; 

//do not process huge files for TTS

$hugeness = strlen($btext);
if ($hugeness > 325)
{
	//This text is too long for text-to-speech
	$language = "error";
}

//change lang as appropriate to iSpeech code
//default code should be error
$language = "error";

if ($mylang == "fr") { $language = "eurfrenchfemale"; }
if ($mylang == "es") { $language = "usspanishfemale"; }
if ($mylang == "de") { $language = "eurgermanfemale"; }
if ($mylang == "en") { $language = "usenglishfemale"; }
if ($mylang == "it") { $language = "euritalianfemale"; }
if ($mylang == "zh") { $language = "chchinesefemale"; }
if ($mylang == "ru") { $language = "rurussianfemale"; }
if ($mylang == "ja") { $language = "jpjapanesefemale"; }
if ($mylang == "ko") { $language = "krkoreanfemale"; }
if ($mylang == "pt") { $language = "brportuguesefemale"; }
if ($mylang == "tr") { $language = "eurturkishfemale"; }
if ($mylang == "ar") { $language = "arabicmale"; }
if ($mylang == "el") { $language = "eurgreekfemale"; }
if ($mylang == "nl") { $language = "eurdutchfemale"; }
if ($mylang == "pl") { $language = "eurpolishfemale"; }
if ($mylang == "cs") { $language = "eurczechfemale"; }
if ($mylang == "ca") { $language = "eurcatalanfemale"; }
if ($mylang == "sv") { $language = "swswedishfemale"; }
if ($mylang == "da") { $language = "eurdanishfemale"; }
if ($mylang == "no") { $language = "eurnorwegianfemale"; }
if ($mylang == "fi") { $language = "eurfinnishfemale"; }
if ($mylang == "hu") { $language = "huhungarianfemale"; }

//Set the model text variable

$model_text = $btext; 

//directory to save newly generated audio with default female audio name

$fullpath = "audio_saves/".$mykey.".wav";

//male voices exist for SOME iSpeech but not all

if ($tts_pref == "m")
{
if ($mylang == "fr") { $language = "eurfrenchmale"; }
if ($mylang == "es") { $language = "usspanishmale"; }
if ($mylang == "de") { $language = "eurgermanmale"; }
if ($mylang == "en") { $language = "usenglishmale"; }
if ($mylang == "it") { $language = "euritalianmale"; }
if ($mylang == "ru") { $language = "rurussianmale"; }
if ($mylang == "ja") { $language = "jpjapanesefemale"; }
if ($mylang == "pt") { $language = "eurportuguesemale"; } //european portuguese
if ($mylang == "tr") { $language = "eurturkishmale"; }
if ($mylang == "ar") { $language = "arabicmale"; }
if ($mylang == "zh") { $language = "hkchinesefemale"; } //for cantonese 


$fullpath = "audio_saves/".$mykey."_male.wav";
}

//if a slowed down file is requested, indicate that here
if ($tts_pref == "s")
{
$fullpath = "audio_saves/".$mykey."_slow_.wav";
//also need to set slow downed parameter below
//  $SpeechSynthesizer->setParameter('speed', '-5');
}

//before using a transaction, check the audio_saves folder for a $mykey.wav file
//if it exists, then there is no need to create a new file

if ($language == "error")
{
echo "$error1 <br><br><br>Oops! Unable to produce speech for this language";
}
else
{

if (file_exists($fullpath)) 
{
    //echo "The file $filename exists. We've already made this file.";
    //echo "<p style=\"font-size:8px\">Audio File found:<br>";

    echo "<a href=\"premium_tri.php?lang=$mylang&mykey=$mykey&btext=$c\"><img src=\"images/go_back_blue.png\" width=\"35\" align=\"right\"></a>"; 
    echo "<audio controls autoplay hidden><source src=\"$fullpath\" type=\"audio/wav\">Your browser does not support the audio playback element.</audio>";


	//echo "full path is $fullpath and tts_pref = $tts_pref";
// no more text with iframe solution
//    echo "<br>More audio options:<br>";
//    echo "<br><a href=\"tts_call_slow.php?lang=$mylang&mykey=$mykey&btext=$btext\">SLOW</a> || ";
//    echo "<a href=\"tts_call_male.php?lang=$mylang&mykey=$mykey&btext=$btext\">Male Voice</a>";
    
} 
else 
{
    //echo "The file $filename does not exist. Making one now...";
    //echo "<p style=\"font-size:8px\">Audio File Created, "; 

//if it doesn't exist, then load a placeholder icon and do a 5 second refresh
//GIF loader: Loading... 

//hide the text via a white mask

echo "<font color=\"white\">"; 


  $SpeechSynthesizer = new SpeechSynthesizer();
  $SpeechSynthesizer->setParameter('server', 'http://api.ispeech.org/api/rest');
  $SpeechSynthesizer->setParameter('apikey', $ispeech_key);
  //$SpeechSynthesizer->setParameter('text', 'yes');
  $SpeechSynthesizer->setParameter('text', $model_text);
  

  if ($tts_pref == "s") { $SpeechSynthesizer->setParameter('speed', '-5'); }
  
  $SpeechSynthesizer->setParameter('format', 'wav');
  //$SpeechSynthesizer->setParameter('voice', 'usenglishfemale');
  $SpeechSynthesizer->setParameter('voice', $language);
  $SpeechSynthesizer->setParameter('output', 'rest');
  $result = $SpeechSynthesizer->makeRequest();
  
  if (is_array($result)) //error occurred 
    echo '<pre>'.htmlentities(print_r($result, true), null, 'UTF-8');
  else
    echo file_put_contents($fullpath, $result) . '';
     //echo file_put_contents('audio_saves/$mykey.wav', $result) . ' bytes saved';
     
     
      echo "<a href=\"premium_tri.php?lang=$mylang&mykey=$mykey&btext=$c\"><img src=\"images/go_back_black.png\" width=\"35\" align=\"right\"></a>"; 
   
  echo "<audio controls autoplay hidden><source src=\"$fullpath\" type=\"audio/wav\">Your browser does not support the audio playback element.</audio>";
  
  	//echo "<br>More audio options:<br>";
    //echo "<br><a href=\"tts_call_slow.php?lang=$mylang&mykey=$mykey&btext=$btext\">SLOW</a> || ";
    //echo "<a href=\"tts_call_male.php?lang=$mylang&mykey=$mykey&btext=$btext\">Male Voice</a>";
    
    //echo "<br>More audio options:<br>";
    //echo "<br><a href=\"tts_call_slow.php?lang=$mylang&mykey=$mykey&btext=$btext\">SLOW</a> || ";
    //echo "<a href=\"tts_call.php?lang=$mylang&mykey=$mykey&btext=$btext\">Female Voice</a>";
    
    //track actual words used by this account
   $use_credits = str_word_count($model_text);
   
   //for character sets without spaces
   if ($use_credits < 3 && $hugeness > 10)
   {
   	$hugeness = ($hugeness / 3); 
   	$use_credits = $hugeness; 
   	
   }
   
   //define the query to track actual credits used
	//$query = "INSERT INTO ispraak_credits VALUES ('$this_school', '$mykey', '$use_credits',NULL)";

	//execute the query
	//mysql_query($query);
	
	//that should be the end of tracking credits
	//japanese and chinese done with string count divided by three
    




}

}
//above colon ends the non-error situation for the language code


  
?>
