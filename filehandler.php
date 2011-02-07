<?php
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
function getDirectoryTree( $outerDir ){
    $dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
    $dir_array = Array();
    foreach( $dirs as $d ){
        if( is_dir($outerDir."/".$d) ) $dir_array[ $d ] =  getDirectoryTree( $outerDir."/".$d );
        else $dir_array[ $d ] = $d;
    }
    return $dir_array;
}