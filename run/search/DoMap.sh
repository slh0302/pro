#!/bin/bash
cd /home/slh/Vechile_Search/libfaster_rcnn_cpp/
./bin/DoIndex -c /home/slh/data/demo/data_binary_map_371 371 $1 1 0 128 GPU 15
# /home/slh/faiss_index/bin/faissSystemMap /home/slh/faiss_index/index_store/index_map.faissindex 10 512 $1
