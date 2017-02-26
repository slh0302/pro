<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/2/23
 * Time: 16:28
 */


header('Content-Type: application/json');

$mysqli = new mysqli('127.0.0.1', 'root', 'root', 'test');

if (mysqli_connect_errno()) {
    echo json_encode(array('mysqli' => 'Failed to connect to MySQL: ' . mysqli_connect_error()));
    exit;
}
$page = 0 ;
$pageSize = 3;

if(!is_null($_GET["name"])) {
    $name = $_GET["name"];
}
//if(!is_null($_GET["page"])) {
//    $page = $_GET["page"];
//}
//
//if(!is_null($_GET["pageSize"])) {
//    $pageSize = $_GET["pageSize"];
//}
$arr=array();
$i=0;
//$result=$mysqli->query("update users set nickname=21 where id =2");

$result=$mysqli->query("select * from ". $name ." limit " . 0 ." , " . 1000);
//printf("Affected rows (UPDATE): %d\n", $mysqli->affected_rows);
// fetch_row
while($row = $result->fetch_assoc()) {
    $count=count($row);//不能在循环语句中，由于每次删除row数组长度都减小
    for($i=0;$i<$count;$i++){
        unset($row[$i]);//删除冗余数据
    }
    array_push($arr,$row);
}

mysqli_close($mysqli);
echo json_encode($arr,JSON_UNESCAPED_UNICODE);
