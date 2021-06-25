# Alarm PiHat
This is a pretty simple little project to create a custom PiHat PCB that has
six dry contact inputs, two relays for things like sirens or door latches, and
and I2C temperature/humidity sensor. I wasn't able to find anything premade for
a price that I was willing to pay that did what I needed it to do, so I made
one.

There isn't anything here that would likely be usefull for anyone outside of
myself, my code is horrible, and my electronics design capabilities are limited.
If you find anything of use here, great. If you can make improvements to the
code or the PCB, well that's also great.

## Board and Schematics will be made available here once finalized.

## BOM for the PCB (Everything available from Mouser.com)
  - Temperature/Humidity Sensor: Adafruit AM2320 Part#:3721 (1)
  - Resistors: Generic 0805 10k (6)
  - Diodes: Generic SOT23 basic diodes (3)
  - Contact headers: 12P 2.5mm 90DEG Part#:1725453 (1)
  - RasPi Header: Adafruit 2x20P Part#:2243 (1)
  - Relay headers: 3P 2.5mm 90 DEG Part#:1725669 (2)
  - Relays: PCB Power Relay Part#:G2RL-1-E-HA DC5 (2)

# Installation
Installation is simple, install raspbian lite on an SD card. The  install is sub
2GB so pretty much any size of SD card shoudl work just fine. Pop the SD into a
Raspberry PI (preferably version 3 or better), clone the git repo and then just
run the install.sh script.

```
apt install git
git clone https://github.com/ctyler2621/AlarmPiHat.git
cd /home/pi/AlarmPiHat
./install.sh
```

### Here is a rundown of what the install.sh script does:
 1. Change the pi user Password
 2. Update the underlying OS
 3. Install required packages
 4. Setup Apache2 with SQLite and PDO
 5. Create a symbolic link to /var/www/html for AlarmPiHat
 6. Install some Adafruit packages
 7. Enable I2C and change permissions to the I2C bus to 666
 8. Reboot the Raspberry Pi

# Accessing the Alram PiHat through the web
Just go to the IP address of your Raspberry Pi and then /AlarmPiHat/ for
example:

http://192.168.0.24/AlarmPiHat/
