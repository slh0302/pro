<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/5
 * Time: 15:48
 */
header('Content-Type: application/json');

$mysqli = new mysqli('127.0.0.1', 'root', 'root', 'test');


if (mysqli_connect_errno()) {
    echo json_encode(array('mysqli' => 'Failed to connect to MySQL: ' . mysqli_connect_error()));
    exit;
}

$str="Select database from db_file ;";
$mysql_result=$mysqli->query($str);
$arr=array();
$i=0;
while ($row = $mysql_result->fetch_assoc()){
    $arr[$i] = $row[0];
    $i++;
}

if($i >0) {
    $result_arr = array("data" => $arr, "msg" => "SUCCESS");
}else{
    $result_arr = array("data" => $arr, "msg" => "FAILs");
}
mysqli_close($mysqli);
print_r($arr);
//echo json_encode($result_arr);


?>