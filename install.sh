#!/bin/bash
if [ `whoami` == root ]; then
    echo "Please DO NOT run this script as root or using sudo, it will use sudo"
    echo "as the pi user where needed."
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
    sudo passwd pi
    sudo apt update
    sudo apt upgrade -y
    # Start installation of required packages
    sudo apt-get install apache2 snmpd python3-pip php sqlite3 php7.3-sqlite3 php7.3-mysql php-db ufw composer -y
    sudo pip3 install --upgrade setuptools
    sudo pip3 install --upgrade adafruit-python-shell
    sudo pip3 install adafruit-blinka
    sudo pip3 install adafruit-circuitpython-am2320
    # Install wiringPi for Python
    sudo pip3 install wiringpi
    # Install wiringPi for CLI from forked repository
    git clone https://github.com/eitch/wiringPi.git
    cd wiringPi/
    ./build
    # Create a symbolic link to the www directory
    sudo ln -s /home/pi/AlarmPiHat /var/www/html
    # Enable Apache
    sudo systemctl enable --now apache2
    echo
    echo "In raspi-config click 'Interfacing Options' and 'I2C' to tell the RasPi"
    echo "to enable the I2C interface. Then select 'Finish'"
    echo
    read -n 1 -s -r -p "Press any key to launch raspi-config..."
    sudo raspi-config
    # Make sure that i2c is visible and set permissions so that it is readable
    ls /dev/i2c*
    sudo chmod 666 /dev/i2c*
    # Set up some basic firewalling
    sudo ufw default deny incoming
    sudo ufw default allow outgoing
    sudo ufw allow ssh
    sudo ufw allow http
    sudo systemctl enable --now ufw
    # Copy files needed for proper operation
    sudo cp includes/snmpd.conf /etc/snmp/snmpd.conf
    sudo cp includes/rc.local /etc/rc.local
    # Setup poller with cron (OBSOLETE)
    #sudo crontab -l -u root | cat - includes/AlarmPiHat.cron | sudo crontab -u root -
    # Make the SQLite3 database writeable by EVERYONE
    sudo chmod 777 AlarmPiHat/db
    sudo chmod 777 AlarmPiHat/db/config.db
    # Install PHP Mailer
    sudo cd /home/pi/AlarmPiHat
    composer require phpmailer/phpmailer
    # Make the poller executable
    sudo chmod a+x /home/pi/AlarmPiHat/poller.py
    # Set up the poller to run as a daemon service
    sudo chmod a+x /home/pi/AlarmPiHat/includes/alarmpihat
    sudo cp /home/pi/AlarmPiHat/includes/alarmpihat /etc/init.d
    sudo update-rc.d alarmpihat defaults
    # Reboot the Pi
    read -n 1 -s -r -p "Press any key to REBOOT..."
    sudo reboot
fi
echo "Install script completed."
echo
