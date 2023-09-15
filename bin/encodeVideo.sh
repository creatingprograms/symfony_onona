#!/bin/sh 

if [ ! -f $2.webm ]
then
ffmpeg -i $1 -ab 128k -vcodec libvpx -r 25 -acodec libvorbis -vb 1000k -threads 4 $2.webm
chmod 666 $2.webm
fi

if [ ! -f $2.mp4 ]
then
ffmpeg -i $1 -ab 128k -vcodec libx264 -acodec aac -strict experimental -vb 1000k -threads 4 $2.mp4
chmod 666 $2.mp4
fi
