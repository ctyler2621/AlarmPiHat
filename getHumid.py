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
import board
import adafruit_am2320
# =============================================================================
# Codebase:
try:
    i2c = board.I2C()
    sensor = adafruit_am2320.AM2320(i2c)
    #time.sleep(1) # Just to make sure that we aren't reading from the sensor too quickly
    print('{0}'.format(sensor.relative_humidity))
except:
    print("NaN")
