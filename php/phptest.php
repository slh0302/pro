<?php
$dir = "../searchFile/";
// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if(filetype($dir . $file) != "dir"){
                echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
            }
        }
        closedir($dh);
    }
}
?>