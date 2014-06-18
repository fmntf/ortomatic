#!/bin/bash

kill `ps aux |grep blink\-led\.php |grep -v grep |awk '{print $2}'`
echo 0 > /sys/class/gpio/gpio25/value
