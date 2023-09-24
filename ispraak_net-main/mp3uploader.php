<?
session_start();
//Continues Session from previous PHP page

//Comment the below off to turn off error warnings
error_reporting(0);

//Get database variables: this path is  confirmed 
include_once("../../config_ispraak.php");



//echo "Type: " . $_FILES["file"]["type"] . "<br />";

if ((($_FILES["file"]["type"] == "audio/mp3")
|| ($_FILES["file"]["type"] == "audio/mpeg")
|| ($_FILES["file"]["type"] == "audio/mp4m"))
&& ($_FILES["file"]["size"] < 1999999))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    //echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    //echo "Type: " . $_FILES["file"]["type"] . "<br />";
    //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";



$myfilename = $_FILES["file"]["name"];
$myfile_basename = substr($myfilename, 0, strripos($myfilename, '.'));
$myfile_ext = substr($myfilename, strripos($myfilename, '.')); // strip name
$newfilename = md5(time() . $file_basename) . $myfile_ext;
$_SESSION['mp3link']= $newfilename;

//echo "$newfilename";

//    if (file_exists("uploadmp3/" . $_FILES["file"]["name"]))
    if (file_exists("uploadmp3/" . $newfilename))
      {
	  echo "<hr><h3>";
      echo $_FILES["file"]["name"] . " already exists. Please give your file a unique name and re-upload. </h3> Press Back to Continue<hr>";
      }
    else
      {
	  
	  move_uploaded_file($_FILES["file"]["tmp_name"], "uploadmp3/" . $newfilename);
	  
      /*move_uploaded_file($_FILES["file"]["tmp_name"],
      "uploadmp3/" . $_FILES["file"]["name"]);
	  */
	  
      //echo "Stored in: " . "uploadmp3/" . $_FILES["file"]["name"];
	  //echo "<hr><h3>Your file has been successfully uploaded.</h3>";
	  //echo "<a href=\"save_mp3.php\">Continue...</a><hr>";
	  
	  //$mylink = $_FILES["file"]["name"];
	  //$_SESSION['mp3link']= $mylink;  
	  
	  //echo "Filename: $mylink ";
	  
	  
	  $_SESSION['mp3link']= $newfilename;
	  $mylink = $_SESSION['mp3link'];

	  //echo "Filename: $mylink ";
	  
	  header( 'Location: edit.php' ) ;
	  
      }
    }
  }
else
  {
  
  
  	  
$_SESSION['mp3link']= "1"; 
 
echo "$ispraak_header <form id=\"form_1007732\" class=\"ispraak_form\"  method=\"post\" action=\"makeit.php\">
<div class=\"form_description\">$ispraak_logo Zut alors! Sorry, an invalid file was detected.<br>Files must be in the MP3 format and no larger than 2mb. 
 <br><br></div>

<br><br><center>

	<a href=\"edit.php\">Continue with text-to-speech in lieu of file upload</a>
		
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

  }
?>