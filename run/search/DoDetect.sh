#!/bin/bash
export PYTHONPATH=$PYTHONPATH:/home/xieqiang/Documents/Code/Detection/py-faster-rcnn-master/lib
export PYTHONPATH=$PYTHONPATH:/home/xieqiang/Documents/Code/Detection/py-faster-rcnn-master/caffe-fast-rcnn/python
cd /home/slh/Vechile_Search/libfaster_rcnn_cpp
./bin/DoIndex -d $1 GPU 0
#/home/slh/data_model_1200_1080000 1080000  $1 1 1 128 GPU 0


