<?php
/**
 * Facebook PHP SDK Usage v2.2014
 * @author	Ind (@_thinkholic)
 *
 * Last updated: 29/08/2014
**/

# App configuration
$fbconfig['appid' ] = "<app_id>";
$fbconfig['secret'] = "<app_secret>";
$fbconfig['baseUrl'] = "<canvas URL>";
$fbconfig['appBaseUrl'] = "<facebook app URL>";

# Is First Time User..
if (isset($_GET['code']))
{
	// redirect them to the application page after the app authiorization.
	header("Location: " . $fbconfig['appBaseUrl']);
	exit;
}

$user = null; //facebook user uid

try
{
	include_once "php-sdk/facebook.php";
}
catch(Exception $obj)
{
	print '<pre>';
	print_r($obj);
	print '</pre>';
}

# Create FB Object
$facebook = new Facebook(array(
	'appId' => $fbconfig['appid'],
	'secret' => $fbconfig['secret'],
	'cookie' => true,
));

# Get User
$user = $facebook->getUser();

# Application Scopes
$loginUrl = $facebook->getLoginUrl(
	array(
		'scope' => 'email,user_likes'
	)
);

# User Information
if ($user) 
{
	try 
	{
		$user_profile = $facebook->api('/me'); // User Profile
		$access_token = $facebook->getAccessToken(); // Access Token
		
		// Get Basic User Info
		$fb_user_id = $user_profile['id'];
		$fb_user_name = $user_profile['username'];
		$fb_first_name = $user_profile['first_name'];
		$fb_email =  $user_profile['email'];
		
		// Get Friends List
		$friends = $facebook->api('/me/friends');
		
		// Get Signed Request
		$signed_request = $facebook->getSignedRequest();
		
		// Get User Info according to the Signed request
		$country = $signed_request["user"]["country"];
		$locale = $signed_request["user"]["locale"];

	} 
	catch (FacebookApiException $e) 
	{
		// Error logging
		d($e); // d is a debug function
		$user = null;
	}
}

if ( !$user )
{
	print "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
	exit;
}

# Debug function
function d($e)
{
	print '<pre>';
	print_r($e);
	print '</pre>';
}

//EOF.