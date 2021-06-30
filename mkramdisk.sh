#!/bin/bash
# Make a ram disk to spare the SD card
if [[ ! -d "/home/pi/AlarmPiHat/ramdisk" ]]; then
  printf "[AlarmPiHat] Create ramdisk directory"
  sudo mkdir /home/pi/AlarmPiHat/ramdisk
fi
printf "[AlarmPiHat] Mounting Ram Disk"
sudo mount -t tmpfs -o size=100m ramdisk /home/pi/AlarmPiHat/ramdisk
sudo chmod 777 /home/pi/AlarmPiHat/ramdisk

# copy the AlarmPiHat database to the ram disk
printf "[AlarmPiHat] Copy AlarmPiHat database to ram disk"
sudo cp /home/pi/AlarmPiHat/db/config.db /home/pi/AlarmPiHat/ramdisk/config.db
sudo chmod 777 /home/pi/AlarmPiHat/ramdisk/config.db

# Make the poller executable
sudo chmod +x /home/pi/AlarmPiHat/poller.php
