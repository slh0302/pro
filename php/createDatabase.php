<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/2/26
 * Time: 17:53
 */

// Done
function my_exec($cmd, $input='')
{
    $proc = proc_open($cmd, array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
    fwrite($pipes[0], $input);
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    $rtn = proc_close($proc);
    return array('stdout' => $stdout,
        'stderr' => $stderr,
        'return' => $rtn
    );
}


header('Content-Type: application/json');
$input = filter_input_array(INPUT_POST);
$mysqli = new mysqli('127.0.0.1', 'root', 'root', 'test');

if (mysqli_connect_errno()) {
    echo json_encode(array('mysqli' => 'Failed to connect to MySQL: ' . mysqli_connect_error()));
    exit;
}
$page = 0 ;
$pageSize = 3;
$filename="";
$results="";

if(!is_null($input["database"])) {
    $database = $input["database"];
}

if(!is_null($input["filename"])) {
    $filename = $input["filename"];
}
//echo $database, $filename;
$str="Create Table ". $database . " ( id int(11) NOT NULL AUTO_INCREMENT, ". "`name` varchar(20) DEFAULT NULL ,  Location varchar(80) DEFAULT NULL, PRIMARY KEY (id))";
//echo $str;
$mysqli->query($str);

// insert data into database
// Cpp write by su and os define judge
// os judge
//linux : g++ LoadToDatabase.cpp -o LoadToDatabase `mysql_config --cflags --libs`
$os_info=php_uname('a');
$os=explode(' ',$os_info);
$fileLocation_w=dirname(__FILE__) . "\\FileList\\" . basename($filename);
$fileLocation_l=dirname(__FILE__) . "/FileList/" . basename($filename);
$str_windows="../run/upload/windows/makefilelist.exe   ". $fileLocation_w . " " . $database;
$str_linux="../run/upload/linux/LoadToDatabase ". $fileLocation_l . " " . $database;
if($os[0] == "Windows"){
  //  echo $str_windows;
    $results=my_exec($str_windows);
}else if($os[0] == "Linux"){
   // echo "test linux";
    $results=exec($str_linux);
}
//cout << "SUCCESS "<<num<< " "<<fileout<<" "<<filepath;
$array=explode(' ',$results);
if($array[0] == "SUCCESS"){
    $count=$array[1];
    // create information
    $str="Insert into db_file (`database`, fileLocation,`count`,`status`) VALUES (". "'" . $database . "' , '" .$fileLocation_l . "', '".$count."', 'ready');";
    $mysqli->query($str);
    // program by data
	$text="../run/upload/Upload  ". $array[2] . "  " . $array[3] ."  " . $count . " /home/slh/database/".basename($filename)."_base " . $database ." > /home/slh/log/upload.log &";
    exec($text);
    $returndata=array(
                    "msg"=>"SUCCESS",
                    "filelist"=>$fileLocation_l,
					"re"=>$array,
					"text"=>$text
                );
    //handle process
    echo json_encode($returndata);
}else{
    $returndata=array(
        "msg"=>"FAIL"
    );
    echo json_encode($returndata);
}

?>
