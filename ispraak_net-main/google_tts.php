<?php

// includes the autoloader for libraries installed with composer
//require __DIR__ . '/vendor/autoload.php';


require_once '../../vendor/autoload.php';

//you need to get from the query string the language and THE text to synethesize

//example query
//https://www.ispraak.net/google_tts.php?lang=es&mykey=1651611033&btext=hola%20amigos,%20me%20allegro%20de%20estar%20aqu%C3%AD
//currently getting an ENABLE billing error sent back to us as an array

// Imports the Cloud Client Library
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;


# Explicitly use service account credentials by specifying the private key
# file.

	$projectID = "voltaic-cirrus-281718";
	$serviceAccountPath = "mykey.json"; 
	
	putenv('GOOGLE_APPLICATION_CREDENTIALS=../../mykey.json');
    //$client->useApplicationDefaultCredentials();
	

    $config = [
        'keyFilePath' => $serviceAccountPath,
        'projectId' => $projectId,
    ];



//Get all from query string just like the old way from premium_tri.php on ispraak

$mykey=$_GET['mykey'];
$mylang=$_GET['lang'];
$btext=$_GET['btext'];

//Convert to Proper Language Code

$language = "error";

if ($mylang == "fr")
{ $language = "fr-FR"; }

if ($mylang == "es")
{ $language = "es-ES"; }

if ($mylang == "de")
{ $language = "de-DE"; }

if ($mylang == "en")
{ $language = "en-US"; }

if ($mylang == "it")
{ $language = "it-IT"; }

if ($mylang == "zh")
{ $language = "cmn-CN"; }

if ($mylang == "ru")
{ $language = "ru-RU"; }

if ($mylang == "ja")
{ $language = "ja-JP"; }

if ($mylang == "ko")
{ $language = "ko-KR"; }

if ($mylang == "pt")
{ $language = "pt-BR"; }

if ($mylang == "tr")
{ $language = "tr-TR"; }

if ($mylang == "ar")
{ $language = "ar-XA"; }

if ($mylang == "el")
{ $language = "el-GR"; }

if ($mylang == "nl")
{ $language = "nl-NL"; }

if ($mylang == "pl")
{ $language = "pl-PL"; }

if ($mylang == "cs")
{ $language = "cs-CZ"; }

//maybe not a voice.... 
if ($mylang == "ca")
{ $language = "ca-ES"; }

if ($mylang == "sv")
{ $language = "sv-SE"; }

if ($mylang == "hi")
{ $language = "hi-IN"; }



//I think Google has ALL for the languages we need 
//$option = "all";
//but on the old page this had portuguese flag, icons for Cantonese, male Arabic only, etc. 
//some languages only had female voices 




$fullpath = "audio_saves/".$mykey.".mp3";
//echo file_put_contents($fullpath, $result) . '';
 

//did this not save? 

// instantiates a client
$client = new TextToSpeechClient($config);

// sets text to be synthesised
$synthesisInputText = (new SynthesisInput())
    ->setText($btext);

//$btext was this : 'This is going to blow your mind!'

// build the voice request, select the language code ("en-US") and the ssml
// voice gender
$voice = (new VoiceSelectionParams())
    ->setLanguageCode($language)
    ->setSsmlGender(SsmlVoiceGender::FEMALE);


//voice was this :     ->setLanguageCode('en-US')


// Effects profile
$effectsProfileId = "telephony-class-application";

// select the type of audio file you want returned
$audioConfig = (new AudioConfig())
    ->setAudioEncoding(AudioEncoding::MP3)
    ->setEffectsProfileId(array($effectsProfileId));

// perform text-to-speech request on the text input with selected voice
// parameters and audio file type
$response = $client->synthesizeSpeech($synthesisInputText, $voice, $audioConfig);
$audioContent = $response->getAudioContent();

// the response's audioContent is binary
//file_put_contents('output.mp3', $audioContent);
file_put_contents($fullpath, $audioContent);


echo "
<audio controls>
  <source src=\"$fullpath\" type=\"audio/mpeg\">
Your browser does not support the audio element.
</audio>";

//echo 'Audio content written to "output.mp3"' . PHP_EOL;

?>

