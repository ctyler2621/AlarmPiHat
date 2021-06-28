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
import digitalio
from random import *
# =============================================================================
# Codebase:

# Map for Relays
# Relay 1 = D4
# Relay 2 = D17

relay1 = digitalio.DigitalInOut(board.D4)
relay1.direction = digitalio.Direction.OUTPUT
relay2 = digitalio.DigitalInOut(board.D17)
relay2.direction = digitalio.Direction.OUTPUT

relay1.value = True
relay1.value = False

from random import *

arr = []
for x in range(0,2):
    arr.insert(x,randint(0,1))

print ("%s,%s" % (arr[0],arr[1]))

#print("0,1")  # This is what the output should look like
