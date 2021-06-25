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
import board
import adafruit_am2320
# =============================================================================
# Codebase:
i2c = board.I2C()
sensor = adafruit_am2320.AM2320(i2c)
celsius = '{0}'.format(sensor.temperature)
celsius = float(celsius)
fahrenheit = (celsius * 1.8) + 32
time.sleep(1) # Just to make sure that we aren't reading from the sensor too quickly
print('%0.1fC %0.1fF' %(celsius,fahrenheit))

#from random import *
#rando = uniform(70,71)
#print("{:.2f}".format(rando))
