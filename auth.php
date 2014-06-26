<?php
Header("content-type: application/x-javascript");
require_once('../../config.php'); //moodle config
global $USER , $CFG ;

if(!$USER->id){
 	echo "exit;";
}
function vmchat_curl_request($url, $post_data)
{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 'content-type: text/plain;');
        curl_setopt($ch, CURLOPT_TRANSFERTEXT, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXY, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, 1);

        $result = @curl_exec($ch);
		if($result === false){
		    echo 'Curl error: ' . curl_error($ch);
			exit;
		}
        curl_close($ch);

        return $result;
}



if(!isset($_COOKIE['auth_user']) || !isset($_COOKIE['auth_pass']) || !isset($_COOKIE['path'])){
	$result= $DB->get_field('config_plugins', 'value', array ('plugin' => 'local_getkey', 'name' => 'keyvalue'), $strictness=IGNORE_MISSING);
	$licen = $result;
	//send auth detail to server 
	$authusername = substr(str_shuffle(MD5(microtime())), 0, 12);
	$authpassword = substr(str_shuffle(MD5(microtime())), 0, 12);
	
 
	$post_data = array('authuser'=> $authusername,'authpass' => $authpassword, 'licensekey' => $licen);
	$post_data = json_encode($post_data);
	
	$rid = vmchat_curl_request("https://c.vidya.io", $post_data); // REMOVE HTTP

	if(empty($rid) or strlen($rid) > 32){
 		echo "Chat server is unavailable!";
 		echo "alert('Chat server is unavailable!');";
 		exit;
 	}
 	if($rid =='Rejected - Key Not Active'){
	  	echo "alert('VmChat license key is not valid');exit;";
	  	exit;
	}
	
  
  	setcookie('auth_user',$authusername,0,'/');
  	setcookie('auth_pass',$authpassword,0,'/');
  	setcookie('path',$rid,0,'/');
  	
  	$result= $DB->get_field('config_plugins', 'value', array ('plugin' => 'local_getkey', 'name' => 'tokencode'), $strictness=IGNORE_MISSING);
  	setcookie('tk',$result,0,'/');
}


if($USER->id){
 	//moodle user pic url
 	$userpicture = moodle_url::make_pluginfile_url(context_user::instance($USER->id)->id, 'user', 'icon', NULL, '/', 'f2');
 	$src= $userpicture->out(false);
 	echo "imageurl='".$src."';";
	echo "sid ='".$USER->sesskey."';";
 	
 
  	echo "auth_user='".$_COOKIE['auth_user']."';"; 
  	echo "auth_pass='".$_COOKIE['auth_pass']."';";
  	echo "path='".$_COOKIE['path']."';";
  	echo "tk='".$_COOKIE['tk']."';";  	
 
  	echo "id='".$USER->id."';"; 
  	echo "fname='".$USER->firstname."';";
  	echo "lname='".$USER->lastname."';"; 
}
 
	 

?>

