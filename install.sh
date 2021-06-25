#!/bin/bash
if [ `whoami` != root ]; then
    echo Please run this script as root or using sudo
    exit
else
    clear
    echo
    echo "This will install everything that you need to get up and running with"
    echo "the Alarm PiHat. It is interactive to some extent and will require"
    echo "some user input for things like saying Yes to install packages, etc."
    echo
    echo "This script assumes that you have rasbian or raspbian lite installed"
    echo "and does not support using any other linux distribution. It also makes"
    echo "the assumption that this is a fresh install of raspbian."
    echo
    read -n 1 -s -r -p "Press any key to change password for pi user..."
    passwd pi
    sudo apt update
    sudo apt upgrade
    sudo apt-get install python3-pip
    sudo pip3 install --upgrade setuptools
    sudo pip3 install --upgrade adafruit-python-shell
    sudo pip3 install adafruit-blinka
    sudo pip3 install adafruit-circuitpython-am2320
    echo "In raspi-config click 'Interfacing Options' and 'I2C' to tell the RasPi"
    echo "to enable the I2C interface. Then select 'Finish'"
    read -n 1 -s -r -p "Press any key to launch raspi-config..."
    sudo raspi-config
    ls /dev/i2c* to make sure that the i2c is visible
    read -n 1 -s -r -p "Press any key to REBOOT..."
    sudo reboot
echo "Install script completed."
echo
