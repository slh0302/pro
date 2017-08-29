<?php
/**
 * Created by PhpStorm.
 * User: slh
 * Date: 17-8-24
 * Time: 上午2:43
 */

header("Content-type: text/html; charset=utf-8");
//error_reporting(E_ALL);
echo "<h2>tcp/ip connection </h2>\n";
$service_port = 18000;
$address = 'localhost';
echo '</br>';
$socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname("tcp"));
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK. \n";
}
echo '</br>';
echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
echo '</br>';
if($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK \n";
}
echo '</br>';
$in = "/home/slh/pro/searchFile/20170916031252.jpg 1 512";
echo "sending http head request ...";
echo '</br>';
socket_write($socket, $in, strlen($in));




//echo "Reading response:\n\n";
//while ($out = socket_read($socket, 8192)) {
//    echo $out;
//}
echo $out;
echo '</br>';
echo "closeing socket..";
socket_close($socket);
echo "ok .\n\n";

?>