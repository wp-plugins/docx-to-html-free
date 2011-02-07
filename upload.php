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

/**
 * Function to write to the logs file
 * @param String $result The result to be logged
 * @param String $file The filename to be logged
 * @param String $size The filesize to be logged
 */
function write_log($result,$file = "no-file",$size = "no-size"){
    $open = fopen("docxhtml-log.csv", "a");
    $string = "\n".str_replace("T"," ",str_replace(date("P"),"",date("c"))).",$result,$file,$size Bytes,PHP ".phpversion();
    $write = fwrite($open, $string);
    $close = fclose($open);
}

//make sure that the current user may publish posts (if not, terminate script)
if(!current_user_can('publish_posts')){
    $result = "1. You are not authorised to upload files.";
    update_option('docxhtml_last_result',$result);
    write_log($result);
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
    write_log($result);
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
    write_log($result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}elseif(isset($_FILES['docxhtml_file']["error"]) && $_FILES['docxhtml_file']["error"] != 0){
    $returndata = $uploadErrors[$_FILES['docxhtml_file']["error"]];
    $result = "4. ".$returndata;
    update_option('docxhtml_last_result',$result);
    write_log($result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}elseif(!isset($_FILES['docxhtml_file']["tmp_name"]) || !@is_uploaded_file($_FILES['docxhtml_file']["tmp_name"])){
    $result = "5. Upload failed is_uploaded_file test.";
    update_option('docxhtml_last_result',$result);
    write_log($result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}elseif(!isset($_FILES['docxhtml_file']['name'])){
    $result = "6. File has no name.";
    update_option('docxhtml_last_result',$result);
    write_log($result);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}

//check if the file is in the correct size limitations
$file_size = @filesize($_FILES['docxhtml_file']["tmp_name"]);
$file_name = $_FILES['docxhtml_file']['name'];
if(!$file_size){
    $result = "7. File exceeds the maximum allowed size.";
    update_option('docxhtml_last_result',$result);
    write_log($result,$file_name);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result", true, 302);
    exit(0);
}

if($file_size <= 0){
    $result = "8. File size outside allowed lower bound.";
    update_option('docxhtml_last_result',$result);
    write_log($result,$file_name);
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
    write_log($result,$file_name,$file_size);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}

//
//THIS IS WHERE THE PARSE CLASS WILL BE CALLED FROM
//

//initiate the class, define some variables and start the proccess
require("class.DOCX-HTML.php");
$extract = new DOCXtoHTML();
$extract->docxPath = $_FILES['docxhtml_file']['tmp_name'];
$extract->content_folder = strtolower(str_replace(".".$path_info['extension'],"",str_replace(" ","-",$path_info['basename'])));
$extract->image_max_width = get_option('docxhtml_max_image_width');
$extract->imagePathPrefix = plugins_url();
$extract->keepOriginalImage = ($_POST['docxhtml_original_images']=="true") ? true:false;
$extract->Init();

//handle the output of the class and define variables needed for the WP post
$post_data = $extract->output;
$post_title = $_POST['docxhtml_post_title'];
$post_name = $_POST['docxhtml_post_name'] ? $_POST['docxhtml_post_name']:"";
$post_date = $_POST['docxhtml_post_date'];
if($post_date == "YYYY-mm-dd HH:ii:ss"){
    $post_date = "";
}
$post_id = $_POST['docxhtml_post_id'];
$post_tags = $_POST['docxhtml_post_tags'];
$post_comments = $_POST['docxhtml_post_comments'] == "true" ? "open":"closed";
$post_pings = $_POST['docxhtml_post_pings'] == "true" ? "open":"closed";
$post_status = $_POST['docxhtml_post_status'];
$post_type = $_POST['docxhtml_post_type'];
$post_cat1 = $_POST['docxhtml_post_cat1'];
$post_cat2 = $_POST['docxhtml_post_cat2'] == "-1" ? NULL:$_POST['docxhtml_post_cat2'];
// Create post object
$my_post = array(
    'ID' => $post_id,
    'post_title' => $post_title,
    'post_name' => $post_name,
    'post_date' => $post_date,
    'tags_input' => $post_tags,
    'comment_status' => $post_comments,
    'ping_status' => $post_pings,
    'post_content' => $post_data,
    'post_status' => $post_status,
    'post_type' => $post_type,
    'post_category' => array($post_cat1,$post_cat2)
);

// Insert the post into the database
$post = wp_insert_post($my_post);
if($post == 0){
    $result = "10.The post could not be inserted. An unknown error occured.";
    update_option('docxhtml_last_result',$result);
    write_log($result,$file_name,$file_size);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}
if($extract->error != NULL) {
    $result = $extract->error;
    update_option('docxhtml_last_result',$result);
    write_log($result,$file_name,$file_size);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
} else {
    $returndata = $extract->time;
    $result = "Post successfuly inserted. Operation took ".$returndata." seconds.";
    update_option('docxhtml_last_result',$result);
    write_log($result,$file_name,$file_size);
    header("Location:".dirname($_SERVER['HTTP_REFERER'])."/admin.php?page=docxhtml_result",true,302);
    exit(0);
}
//
//END THE CALL TO PARSE
//