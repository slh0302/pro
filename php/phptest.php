<?php
/**
 * Created by PhpStorm.
 * User: Su
 * Date: 2017/3/5
 * Time: 15:48
 */
header("Content-type: text/html; charset=utf-8");
$map_array = array();
if($myfile = fopen("../run/runResult/map.txt", "r") or die("Unable to open file!")){

    $time = fgets($myfile);
    while(!feof($myfile)) {
        $temp_array = array();
        $title = fgets($myfile);
        $numSpace = fgets($myfile);
        $point = fgets($myfile);

        $file_num = intval($numSpace);
        $temp_array["title"] = str_replace("\n","",$title);
        $temp_array["content"] = str_replace("\n","",fgets($myfile));
        $temp_array["point"] = str_replace("\n","",$point);
        $temp_array['url'] = array();
        array_push($temp_array['url'], $temp_array["content"]);
        for( $i=1 ;$i<$file_num; $i++){
            $url_temp = str_replace("\n","",fgets($myfile));
            if($url_temp != ""){
                array_push($temp_array['url'], $url_temp);
            }
        }
        array_push($map_array, $temp_array );
    }
}
echo '<br/>';
foreach($map_array as $ka=>$va){
    echo $ka."=>".$va." : <br />";
    foreach($va as $k=>$v) {
        echo "--------" . $k."=>".$v."<br />";
        if($k == 'url'){
            foreach($v as $ku=>$vu){
                echo "----------------".$ku."=>".$vu."<br />";
            }
        }


    }

}
?>
