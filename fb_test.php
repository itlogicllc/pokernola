<?php
/* INCLUSION OF LIBRARY FILEs*/
require_once( 'scripts/Facebook/FacebookSession.php');
require_once( 'scripts/Facebook/FacebookRequest.php' );
require_once( 'scripts/Facebook/FacebookResponse.php' );
require_once( 'scripts/Facebook/FacebookSDKException.php' );
require_once( 'scripts/Facebook/FacebookRequestException.php' );
require_once( 'scripts/Facebook/FacebookRedirectLoginHelper.php');
require_once( 'scripts/Facebook/FacebookAuthorizationException.php' );
require_once( 'scripts/Facebook/GraphObject.php' );
require_once( 'scripts/Facebook/GraphUser.php' );
require_once( 'scripts/Facebook/GraphSessionInfo.php' );
require_once( 'scripts/Facebook/Entities/AccessToken.php');
require_once( 'scripts/Facebook/HttpClients/FacebookCurl.php' );
require_once( 'scripts/Facebook/HttpClients/FacebookHttpable.php');
require_once( 'scripts/Facebook/HttpClients/FacebookCurlHttpClient.php');

/* USE NAMESPACES */
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;
use Facebook\FacebookHttpable;
use Facebook\FacebookCurlHttpClient;
use Facebook\FacebookCurl;

/*PROCESS*/

//1.Stat Session
 session_start();

//check if users wants to logout
 if(isset($_REQUEST['logout'])){
	unset($_SESSION['fb_token']);
 }

//2.Use app id,secret and redirect url 
$app_id = '1634091846817441';
$app_secret = 'bfce3ec0fd5dcc19c66dd38475173926';
$redirect_url='http://pokernola.com/fb_test.php';

//3.Initialize application, create helper object and get fb sess
FacebookSession::setDefaultApplication($app_id, $app_secret);
$helper = new FacebookRedirectLoginHelper($redirect_url);
$sess = $helper->getSessionFromRedirect();

//check if facebook session exists
if(isset($_SESSION['fb_token'])){
	$sess = new FacebookSession($_SESSION['fb_token']);
}

//logout
$logout = 'http://pokernola.com/fb_test.php?logout=true';

//4. if fb sess exists echo name 
if(isset($sess)){
	//store the token in the php session
	$_SESSION['fb_token']=$sess->getToken();
	
	//create request object,execute and capture response
	$request = new FacebookRequest($sess,'GET','/me');
	
	// from response get graph object
	$response = $request->execute();
	$graph = $response->getGraphObject(GraphUser::classname());
	
	$all = $graph->asArray();
	var_dump($all);
	// use graph object methods to get user details
	$name = $graph->getName();
	$id = $graph->getId();
	$image = 'https://graph.facebook.com/'.$id.'/picture?width=300';
	$email = $graph->getProperty('email');
	echo "hi $name <br>";
	echo "your email is $email <br><Br>";
	echo "<img src='$image' /><br><br>";
	echo "<a href='".$logout."'><button>Logout</button></a>";
}else{
	//else echo login
	echo '<a href="'.$helper->getLoginUrl(array('email')).'" >Login with facebook</a>';
}

?>