#!/usr/bin/env python3
# Coding: utf-8
# =============================================================================
# Author: Christopher Tyler
# Email: chris@totalhighspeed.net
# Date created: 21JUN2021
# Date last modified: 21JUN2021
# Python Version: 3
# Program Version: 0.0.0
# =============================================================================
# Documentation:
# =============================================================================
# Imports:
#import time
import wiringpi
import time
# =============================================================================
# Codebase:

# Set the IO function for wiringPi
wiringpi.wiringPiSetupGpio()

wiringpi.pinMode(0, 1) # Set pin 0 to OUTPUT
wiringpi.pinMode(7, 1) # Set pin 7 to OUTPUT
wiringpi.pinMode(29, 1) # Set pin 29 to OUTPUT
wiringpi.pinMode(25, 0) # Set pin 25 to INPUT
wiringpi.pinMode(27, 0) # Set pin 25 to INPUT
wiringpi.pinMode(24, 0) # Set pin 25 to INPUT
wiringpi.pinMode(23, 0) # Set pin 25 to INPUT
wiringpi.pinMode(26, 0) # Set pin 25 to INPUT
wiringpi.pinMode(22, 0) # Set pin 25 to INPUT

#values = arr()
values[0] = wiringpi.digitalRead(0) # Read the pins and put them into an array
values[7] = wiringpi.digitalRead(7)
values[29] = wiringpi.digitalRead(29)
values[25] = wiringpi.digitalRead(25)
values[27] = wiringpi.digitalRead(27)
values[24] = wiringpi.digitalRead(24)
values[23] = wiringpi.digitalRead(23)
values[26] = wiringpi.digitalRead(26)
values[22] = wiringpi.digitalRead(22)

for value in values:
    print(value, end = ' ')
