#!/bin/bash

cd /var/www

rm -rf html/*

cd html

find . -type f -name ".*" -delete