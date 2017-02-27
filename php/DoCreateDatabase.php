<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/2/26
 * Time: 17:53
 */

// Done

header('Content-Type: application/json');

$mysqli = new mysqli('127.0.0.1', 'root', 'root', 'test');

if (mysqli_connect_errno()) {
    echo json_encode(array('mysqli' => 'Failed to connect to MySQL: ' . mysqli_connect_error()));
    exit;
}
$page = 0 ;
$pageSize = 3;

if(!is_null($_GET["database"])) {
    $database = $_GET["database"];
}

if(!is_null($_GET["filename"])) {
    $fileLocation = $_GET["filename"];
}

$mysqli->query("Create Table ". $database . " ( id int(11) NOT NULL AUTO_INCREMENT, ".
    "`name` varchar(45) DEFAULT NULL ,  Location varchar(45) DEFAULT NULL, PRIMARY KEY (id))");

$str="Insert into db_file (`database`, fileLocation) VALUES (". "'" . $database . "' , '" .$fileLocation . "' );";
$mysqli->query($str);



/// 计算

$results=exec("../run/makefilelist.exe ". $fileLocation . " " . $database);

if ( $results == "SUCCESS"){
    $myfile = fopen("result.txt", "r") or die("Unable to open file!");
    echo fread($myfile,filesize("webdictionary.txt"));
    fclose($myfile);
}
