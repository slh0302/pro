#!/bin/bash

#cd /home/slh/Vechile_Search/libfaster_rcnn_cpp
#./bin/DoIndex -c /home/slh/data_wendeng_10800000_model_7000 1080000  $1 1 1 128 GPU 2
#./bin/DoIndex -s /home/slh/list.file 10000000 $1 1 1 128 GPU 1

# 1080000 test
# TODO need to change
# /home/slh/data/data_model_120000_2000000_0 2000000 change
#./bin/DoIndex -p /home/slh/data/data_person_cuhk03_78978 78978  $1 1 1 128 GPU 12/home/slh/faiss_index/bin/faissSystem /home/slh/faiss_index/index_store/index_person.faissindex 12 512 $1
/home/slh/faiss_index/bin/faissSystem /home/slh/faiss_index/index_store/index_person.faissindex 14 512 $1
