<?php
/**
 * Recursive directory and file listing function
 * @param String $dir The directory containing the files to list
 * @return Array Multidimensional Array containing all real files and folders inside.
 */
function getDirectoryTree( $outerDir ){
    $dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
    $dir_array = Array();
    foreach( $dirs as $d ){
        if( is_dir($outerDir."/".$d) ) $dir_array[ $d ] =  getDirectoryTree( $outerDir."/".$d );
        else $dir_array[ $d ] = $d;
    }
    return $dir_array;
} 
function dirList($directory,$mainid = ""){
    $files = getDirectoryTree($directory);
    $return = "";
    if(is_array($files)){
        $return .= "<ul".(empty($mainid)? "":" id='$mainid'")." class='filetree'>";
        foreach($files as $key => $value){
            if($value != $key && is_array($value)){
                //this ia a folder
                $return .= "<li class='closed'><span class='folder'>$key</span><ul>";
                foreach($value as $k => $v){
                    $return .= "<li><span class='file'>$k</span></li>";
                }
                $return .= "</ul></li>";
            }
        }
        $return .= "</ul>";
    }
    return $return;
}
/**
 * This function will remove files and directories recursivly
 * @param String $dir The path to the folder to be removed
 */
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir"){
                    $this->rrmdir($dir."/".$object);
                } else {
                    unlink($dir."/".$object);
                }
            }
        }
        reset($objects);
        rmdir($dir);
    }
 }