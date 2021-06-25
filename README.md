# Alarm PiHat

## Board characteristics:
  * Two relays with NO and NC connections
  * 6 dry contact connections
  * I2C temperature and humidity sensor

## Just a quick note on extending SNMP
  + Extend a shell script in etc/snmpd.conf using the following format: extend <OID> <name> path/to/script <arguments>
  + Restart snmpd.

Recipes for Extending Net-SNMP: https://vincent.bernat.ch/en/blog/2012-extending-netsnmp
How to write an MIB module: https://net-snmp.sourceforge.io/tutorial/tutorial-4/agent/01-intro.html

So you would call the script to run in order to populate the SNMP value at the given OID. For example if you wanted to
read in the temperature, you would call a python script to get the temperature that returns nothing but the integer
value.

Configuring Apache2 to run Python Scripts: https://www.linux.com/training-tutorials/configuring-apache2-run-python-scripts/

# Installation
 1. Install raspbian lite on an SD card
 2. Change the pi user Password
 3. sudo apt update
 4. sudo apt upgrade
 5. sudo apt-get install python3-pip
 6. sudo pip3 install --upgrade setuptools
 7. sudo pip3 install --upgrade adafruit-python-shell
 8. sudo pip3 install adafruit-blinka
 9. sudo pip3 install adafruit-circuitpython-am2320
 10. sudo raspi-config then'Interfacing Options' and 'I2C' to tell the RasPi to enable the I2C interface. Then select 'Finish'
 11. sudo reboot
 12. ls /dev/i2c* to make sure that the i2c is visible
 13. sudo apt install git
 14. git clone https://github.com/ctyler2621/AlarmPiHat.git
