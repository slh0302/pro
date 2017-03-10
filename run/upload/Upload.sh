#!/bin/bash

# DoIndex -i: Create database
file_list_path=$1
pic_src_path=$2
pic_list_count=$3
result_database_path=$4
cd /home/slh/Vechile_Search/libfaster_rcnn_cpp/
$te="./bin/DoIndex -i $file_list_path $pic_src_path $pic_list_count 250 $result_database_path GPU 0"
echo $te
./bin/DoIndex -i $file_list_path $pic_src_path $pic_list_count 250 $result_database_path GPU 0

