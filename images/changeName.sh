#!/bin/bash
cd choose/
temp=0
for dir in $(ls ./)
do 
    temp=$(($temp+1))
    #echo $dir
    mv $dir "$temp.jpg"
done
