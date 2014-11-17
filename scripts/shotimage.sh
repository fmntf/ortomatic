#!/bin/bash

if [ -f "/etc/udoo-config.conf" ]
then
        WEBCAM="/dev/v4l/by-id/usb-Vimicro_Vimicro_USB_Camera__Altair_-video-index0"
else
        WEBCAM="/dev/video0"
fi

fswebcam -d $WEBCAM -r 640x480 --no-banner ../public/webcam.jpg
