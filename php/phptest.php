<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/2
 * Time: 22:41
 */

$dir = "../resultFile/";
$file = scandir($dir);
$length = count($file);
if( $length > 2 ){
    $newFile=array_slice($file,2,count($file));
}


print_r($newFile);
