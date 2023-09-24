<?php

// Turn off all error reporting
error_reporting(0);

session_start(); 

include_once("../../config_lrc.php");

$admin_username=$_POST['uname']; //email
$admin_password=$_POST['adminpw']; //email


//Updated mysqli commands

$msi_connect = mysqli_connect($mysqlserv,$username,$password,$database);

if (mysqli_connect_errno())
  	{
  		echo "Unable to connect to the LRC's database. Please try again later.";
  		//echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
else
{
//database connection is fine show everything else

//There is no native function for mysqli_result, so we created one in the config file
//
//$premium_result = mysqli_query($msi_connect, "SELECT * FROM slupe_premium WHERE code='$icode'");  
//$required=mysqli_result($premium_result,0,"required");	
//$fullname=mysqli_result($premium_result,0,"fullname");	

mysqli_close($msi_connect);

$copyright_year = date("Y");


if ($admin_username == "" || $admin_password == "")
{

$_SESSION['verboten'] = "logged_out"; 

echo "<html><head><title>LRC Database</title><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><link rel=\"stylesheet\" type=\"text/css\" href=\"ispraak.css?8\">
</head><body><div id=\"headerBar\"><img src=\"sslogo2.png\" align=left height=50><img src=\"jw2.png\" align=right height=50></div>
<div class=\"dontainer\">


<form name=\"myform\" action=\"login.php\" accept-charset=\"utf-8\" method=\"post\">


<div class=\"dq2\">Backup iSpraak Database : Please provide login credentials</div> 
<label for=\"username\"><input type=\"text\" name=\"uname\" placeholder=\"username\"></label>

<label for=\"password\"><input type=\"password\" name=\"adminpw\" placeholder=\"password\"></label></div>
<br>
  <center><input type=\"submit\" class=\"button\" value=\"Login\"/></center>
</form>

</div>
<div id=\"footerBar\">© D. Nickolai - $copyright_year</div>

</body>
</html>";
}
else
{
 	if ($admin_username == $auname && $admin_password == $apword)
	{
	
		$_SESSION['verboten'] = "logged_in"; 


	$logto = "slulanguages@gmail.com"; 
	$esubject = "APPROVED: LRC Library on slupe.org";
	$efrom = "help@slupe.org";
	$estudent_body = "Alert<br><br>Access has been logged for your domain www.slupe.org<br><br>Thanks for retaining this log.";
	mail($logto, $esubject, $estudent_body, "From: $efrom\nContent-Type: text/html; charset=iso-8859-1");

	
echo "<html><head><title>LRC Database</title><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><link rel=\"stylesheet\" type=\"text/css\" href=\"new_slupe2.css\">
</head><body><div id=\"headerBar\"><img src=\"sslogo2.png\" align=left height=50><img src=\"jw2.png\" align=right height=50></div>
<div class=\"dontainer\">


<form name=\"myform\" action=\"login2.php\" accept-charset=\"utf-8\" method=\"post\">


<div class=\"dq2\">Welcome! Please select an option: </div> 
<br>


  <select style=\"font-size:130%;\" name=\"lang\" id=\"lang\">
    <option value=\"Standard\">Connect to Catalog</option>
    <option value=\"Debug\" disabled=\"disabled\" >Maintenance Only</option>
  </select>

<br><br><br>
  <center><input type=\"submit\" class=\"button\" value=\"Continue\"/></center>
</form>

</div>
<div id=\"footerBar\">© D. Nickolai - $copyright_year</div>

</body>
</html>"; 
	}
	else
	{
	
	
		$logto = "slulanguages@gmail.com"; 
	$esubject = "DENIED: LRC Library on slupe.org";
	$efrom = "help@slupe.org";
	$estudent_body = "Alert<br><br>Access has been denied for your domain www.slupe.org<br><br>$admin_username $admin_password<br><br>Thanks for retaining this log.";
	mail($logto, $esubject, $estudent_body, "From: $efrom\nContent-Type: text/html; charset=iso-8859-1");

	
	
	
		echo "<html><head><title>LRC</title><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"><link rel=\"stylesheet\" type=\"text/css\" href=\"new_slupe2.css\">
</head><body><div id=\"headerBar\"><img src=\"sslogo2.png\" align=left height=50><img src=\"jw2.png\" align=right height=50></div>
<div class=\"dontainer\">


<form name=\"myform\" action=\"login.php\" accept-charset=\"utf-8\" method=\"post\">


<div class=\"dq2\">Nope, not even close. </div> 
<br>
  <center><input type=\"submit\" class=\"button\" value=\"Go Back\"/></center>
</form>

</div>
<div id=\"footerBar\">© D. Nickolai - $copyright_year</div>

</body>
</html>";
	}

}

//database connection is fine show everything else

}

?>
