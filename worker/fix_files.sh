#! /bin/bash

cp $1 $1.orig
sed -e s/\&\#\1\0\;/\ /g $1.orig > $1
