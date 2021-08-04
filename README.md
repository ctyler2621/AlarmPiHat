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

## Schemaitc and Gerber files for the PCB are located in the includes folder

## Hardware Revision History

### PCB v2.2:
|GPIO|PIN|DESCRIPTION|
---|---|---
| 0  | 11 | Relay 2   |
| 7  | 7  | Relay 1   |
| 25 | 37 | Contact 1 |
| 27 | 36 | Contact 2 |
| 24 | 35 | Contact 3 |
| 23 | 33 | Contact 4 |
| 26 | 32 | Contact 5 |
| 22 | 31 | Contact 6 |
| 29 | 40 | PWR LED   |

### PCB v2.2 Changelog:
1. Changed the relays from 5v coils to 3v coils
2. Added silkscreen for the dry contacts and relays
3. Changed orientation of the temperature sensor

### PCB v2.2 Bill of Materials:
| MOUSER PART # | MFGR PART # | QTY | DESCRIPTION |
---|---|---|---
|651-1725753|1725753|1|Fixed Terminal Blocks 12P 2.54mm 90DEG|
|485-2243|2243|1|Adafruit Accessories GPIO Header for Raspberry Pi HAT - 2x20 Short Female Header|
|652-CHP0805FX1002ELF|CHP0805-FX-1002ELF|6|Thick Film Resistors - SMD 10K OHM 1% 1/2W 100PPM|
|651-1725669|1725669|1|Fixed Terminal Blocks 3P 2.54mm 90DEG|
|653-G5LE-1-DC3|G5LE-1-DC3|2|General Purpose Relays SPDT 3VDC Flux Protected|
|603-RT0805FRE10220RL|RT0805FRE10220RL|1|Thin Film Resistors - SMD 220 ohm 1% 50 ppm Thin Film|
|604-APT3216VBC/D|APT3216VBC/D|1|	Standard LEDs - SMD 3.2X1.6MM BLUE SMD LED|
|833-MMBD4148CA-TP|MMBD4148CA-TP|3|Diodes - General Purpose, Power, Switching 75V 600mA 4pF|

### PCB v3.0:
|GPIO|PIN|BCM|DESCRIPTION|
---|---|---|---
|  4 | 16 | 23 | Relay 1   |
|  3 | 15 | 22 | Relay 2   |
| 27 | 36 | 16 | Contact 1 |
|  0 | 11 | 17 | Contact 2 |
|  1 | 12 | 18 | Contact 3 |
| 24 | 35 | 19 | Contact 4 |
| 28 | 38 | 20 | Contact 5 |
| 29 | 40 | 21 | Contact 6 |
| 22 | 31 |  6 | PWR LED   |

### PCB v3.0 Changelog:
1. Added output for a fan
2. Added transistors for proper control of the relays
3. Modified the silkscreen for the relays and dry contacts
4. Removed pulldown resistors as they are not needed on the Rasberry Pi
5. Moved pretty much everything around to better situate everything on the PCB

### PCB v3.0 Bill of Materials:
| MOUSER PART # | MFGR PART # | QTY | DESCRIPTION |
---|---|---|---
|651-1725753|1725753|1|Fixed Terminal Blocks 12P 2.54mm 90DEG|
|485-2243|2243|1|Adafruit Accessories GPIO Header for Raspberry Pi HAT - 2x20 Short Female Header|
|651-1725669|1725669|1|Fixed Terminal Blocks 3P 2.54mm 90DEG|
|653-G5LE-1-DC3|G5LE-1-DC3|2|General Purpose Relays SPDT 3VDC Flux Protected|
|603-RT0805FRE10220RL|RT0805FRE10220RL|1|Thin Film Resistors - SMD 220 ohm 1% 50 ppm Thin Film|
|604-APT3216VBC/D|APT3216VBC/D|1|	Standard LEDs - SMD 3.2X1.6MM BLUE SMD LED|
|833-MMBD4148CA-TP|MMBD4148CA-TP|3|Diodes - General Purpose, Power, Switching 75V 600mA 4pF|
|512-BC33725BU|BC33725BU|2|Bipolar Transistors - BJT NPN 45V 800mA HFE/400|
|667-ERA-6AED102V|ERA-6AED102V|2|Thin Film Resistors - SMD 0805 1000ohms 25ppm 0.5% AEC-Q200|

# Installation
Installation is simple, install raspbian lite on an SD card. The  install is sub
2GB so pretty much any size of SD card should work just fine. Pop the SD into a
Raspberry PI (preferably version 3 or better), clone the git repo and then just
run the install.sh script.

```
apt install git
git clone https://github.com/ctyler2621/AlarmPiHat.git
cd /home/pi/AlarmPiHat
./install.sh
```

## Here is a rundown of what the install.sh script does:
 1. Change the pi user Password
 2. Update the underlying OS
 3. Install required packages
 4. Setup Apache2 with SQLite and PDO
 5. Create a symbolic link to /var/www/html for AlarmPiHat
 6. Install some Adafruit packages
 7. Enable I2C and change permissions to the I2C bus to 666
 8. Reboot the Raspberry Pi

# Accessing the Alarm PiHat through the web
Just go to the IP address of your Raspberry Pi and then /AlarmPiHat/ for
example:

http://192.168.0.24/AlarmPiHat/
