#!/bin/bash

# cd /home/slh/Vechile_Search/libfaster_rcnn_cpp
#./bin/DoIndex -c /home/slh/data_wendeng_10800000_model_7000 1080000  $1 1 1 128 GPU 2
#./bin/DoIndex -s /home/slh/list.file 10000000 $1 1 1 128 GPU 1

# 1080000 test
# ./bin/DoIndex -c /home/slh/data/data_model_120000_2000000_0 2000000  $1 1 1 128 GPU 1
/home/slh/faiss_index/bin/faissSystemCar /home/slh/faiss_index/index_store/index_car.faissindex 12 512 $1

#/home/saltedfish/runtest  $1
