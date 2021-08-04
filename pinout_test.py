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
import wiringpi
import time
# =============================================================================
# Codebase:

# Set the IO function for wiringPi
wiringpi.wiringPiSetupGpio()

# Create a list for values
values = [0 for a in range(40)]

count = 1
for value in values:
  if count != 28: # Pin 28 on the RasPi 4 locks up the device
    wiringpi.pullUpDnControl(count, 2) # Put the pin in pull down mode
    wiringpi.pinMode(count, 0) # Set pin to INPUT
    print("Pin:",count,"   Value:",wiringpi.digitalRead(count))
    # Now set all pins to output and cycle them high and low for 1 second
    wiringpi.pinMode(count, 1) # Set pin to OUTPUT
    print("Pin", count, "going HIGH")
    wiringpi.digitalWrite(count, 1) # Set pin HIGH
    time.sleep(1)
    print("Pin", count, "going LOW")
    wiringpi.digitalWrite(count, 0) # Set pin LOW
    time.sleep(1)
    print("Reset pin to input")
    wiringpi.pinMode(count, 0) # Set pin back to INPUT
  count += 1
