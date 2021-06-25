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
    sudo apt upgrade -y
    sudo apt-get install apache2 snmpd python3-pip php sqlite3 php7.3-sqlite3 php7.3-mysql php-db ufw -y
    sudo ln -s /home/pi/AlarmPiHat /var/www/html
    sudo systemctl enable --now apache2
    sudo pip3 install --upgrade setuptools
    sudo pip3 install --upgrade adafruit-python-shell
    sudo pip3 install adafruit-blinka
    sudo pip3 install adafruit-circuitpython-am2320
    echo "In raspi-config click 'Interfacing Options' and 'I2C' to tell the RasPi"
    echo "to enable the I2C interface. Then select 'Finish'"
    read -n 1 -s -r -p "Press any key to launch raspi-config..."
    sudo raspi-config
    ls /dev/i2c* to make sure that the i2c is visible
    sudo chmod 666 /dev/i2c*
    sudo ufw default deny incoming
    sudo ufw default allow outgoing
    sudo ufw allow ssh
    sudo ufw allow http
    sudo systemctl enable --now ufw
    sudo cp includes/snmpd.conf /etc/snmp/snmpd.conf
    sudo cp includes/rc.local /etc/rc.local
    read -n 1 -s -r -p "Press any key to REBOOT..."
    sudo reboot
fi
echo "Install script completed."
echo
