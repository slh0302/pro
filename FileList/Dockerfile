# Pull base image
FROM centos:6

MAINTAINER su "slh@bupt.edu.cn"

RUN yum -y install wget

#RUN cd /tmp && wget ftp://surfer.nmr.mgh.harvard.edu/pub/dist/freesurfer/5.3.0/freesurfer-Linux-centos6_x86_64-stable-pub-v5.3.0.tar.gz | tar -C /usr/local -xzvf 
ADD freesurfer-Linux-centos6_x86_64-stable-pub-v5.3.0.tar.gz /tmp/
RUN tar -C /usr/local/ -xzvf /tmp/freesurfer-Linux-centos6_x86_64-stable-pub-v5.3.0.tar.gz

ENV FREESURFER_HOME /usr/local/freesurfer

RUN source $FREESURFER_HOME/SetUpFreeSurfer.sh

ADD license.txt $FREESURFER_HOME

ENTERPOINT echo "freesure done"

