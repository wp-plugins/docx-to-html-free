<?php
//Start by including all the needed functions for the script to continue
preg_match('|^(.*?/)(wp-content)/|i',str_replace('\\','/',__FILE__),$_m);
require_once $_m[1].'wp-load.php';

//get the currently logged in user's cookies
if(is_ssl() && empty($_COOKIE[SECURE_AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']))
    $_COOKIE[SECURE_AUTH_COOKIE] = $_REQUEST['auth_cookie'];
elseif(empty($_COOKIE[AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']))
    $_COOKIE[AUTH_COOKIE] = $_REQUEST['auth_cookie'];
if(empty($_COOKIE[LOGGED_IN_COOKIE]) && !empty($_REQUEST['logged_in_cookie']))
    $_COOKIE[LOGGED_IN_COOKIE] = $_REQUEST['logged_in_cookie'];
unset($current_user);

//include the admin section for some other functions
require_once(ABSPATH.'wp-admin/admin.php');

//make sure that the current user may publish posts (if not, terminate script)
if(!current_user_can('publish_posts')){
    $result = "1. You are not authorised to upload files.";
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}

//Get the current session id of the user, and start the session
if(isset($_POST["PHPSESSID"])){
    session_id($_POST["PHPSESSID"]);
}elseif(isset($_GET["PHPSESSID"])){
    session_id($_GET["PHPSESSID"]);
}
session_start();

//get the max size that POST data may be and see it it were exceeded, if it did, fail with error message
$POST_MAX_SIZE = ini_get('post_max_size');
$unit = strtoupper(substr($POST_MAX_SIZE,-1));
$multiplier = $unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1));

if((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
    $result = "2. POST exceeded maximum allowed size. Post size is: ".$_SERVER['CONTENT_LENGTH']." - Maximum allowed is: ".$multiplier*(int)$POST_MAX_SIZE;
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}
//The allowed extensions for this script
$extension_whitelist = array('docx','zip');
//define an array containing the possible errors when uploading a file
$uploadErrors = array(
    0=>"There is no error, the file uploaded with success.",
    1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
    2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
    3=>"The uploaded file was only partially uploaded.",
    4=>"No file was uploaded.",
    6=>"Missing a temporary folder."
);
//Test the following: a file is uploaded; no upload error have occured; the uploaded file is found; the file have a name
if(!isset($_FILES['docxhtml_file'])){
    $result = "3. No upload found in \$_FILES for 'docxhtml_file'";
    update_option( 'docxhtml_last_result', $result );
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}elseif(isset($_FILES['docxhtml_file']["error"]) && $_FILES['docxhtml_file']["error"] != 0){
    $returndata = $uploadErrors[$_FILES['docxhtml_file']["error"]];
    $result = "4. ".$returndatadata;
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}elseif(!isset($_FILES['docxhtml_file']["tmp_name"]) || !@is_uploaded_file($_FILES['docxhtml_file']["tmp_name"])){
    $result = "5. Upload failed is_uploaded_file test.";
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}elseif(!isset($_FILES['docxhtml_file']['name'])){
    $result = "6. File has no name.";
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}

//check if the file is in the correct size limitations
$file_size = @filesize($_FILES['docxhtml_file']["tmp_name"]);
$file_name = $_FILES['docxhtml_file']['name'];
if(!$file_size){
    $result = "7. File exceeds the maximum allowed size.";
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result", true, 302);
    exit(0);
}

if($file_size <= 0){
    $result = "8. File size outside allowed lower bound.";
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result", true, 302);
    exit(0);
}
//check if the file have the correct extension
$path_info = pathinfo($_FILES['docxhtml_file']['name']);
$file_extension = $path_info["extension"];
$is_valid_extension = false;
foreach($extension_whitelist as $extension){
    if(strcasecmp($file_extension,$extension) == 0){
        $is_valid_extension = true;
        break;
    }
}
if(!$is_valid_extension){
    $result = "9. Invalid file extension.";
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}

//
//THIS IS WHERE THE PARSE FUNCTION WILL BE CALLED FROM
//

//initiate the class, define some variables and start the proccess
require("class.DOCX-HTML.php");
$extract = new DOCXtoHTML();
$extract->docxPath = $_FILES['docxhtml_file']['tmp_name'];
$extract->content_folder = strtolower(str_replace(".".$path_info['extension'],"",str_replace(" ","-",$path_info['basename'])));
$extract->imagePathPrefix = plugins_url()."/docxhtml-free/";
$extract->Init();

//handle the output of the class and define variables needed for the WP post
$post_data = $extract->output;
$post_title = $_POST['docxhtml_post_title'];
$post_publish = get_option('docxhtml_publish');
$post_cat1 = $_POST['docxhtml_post_cat1'];
//now add the post to WP.
// Create post object
$my_post = array(
    'post_title' => $post_title,
    'post_name' => $post_name,
    'post_content' => $post_data,
    'post_status' => $post_publish,
    'post_category' => array($post_cat1)
);

// Insert the post into the database
$post = wp_insert_post($my_post);
if($post == 0){
    $result = "10.The post could not be inserted. An unknown error occured.";
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}
if($extract->error != NULL) {
    $result = $extract->error;
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
} else {
    $returndata = $extract->time;
    $result = "Post successfuly inserted. Operation took ".$returndata." seconds.";
    update_option('docxhtml_last_result',$result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}

//
//END THE CALL TO PARSE
//