#!/bin/bash

cd /home/slh/Vechile_Search/libfaster_rcnn_cpp
#./bin/DoIndex -c /home/slh/data_wendeng_10800000_model_7000 1080000  $1 1 1 128 GPU 2
./bin/DoIndex -s /home/slh/list.file 10000000 $1 1 1 128 GPU 1

# 1080000 test
#./bin/DoIndex -c /home/slh/data_model_1200_1080000 1080000  $1 1 1 128 GPU 1

#/home/saltedfish/runtest  $1
