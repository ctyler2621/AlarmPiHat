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

# Button Map for Dry contacts from top to bottom
# 1 = D26
# 2 = D16
# 3 = D19
# 4 = D13
# 5 = D12
# 6 = D6

# Define the pins for the dry contacts and read their current values
contact1 = digitalio.DigitalInOut(board.D26)
contact1.switch_to_input(pull=digitalio.Pull.DOWN)
if contact1.value:
        contact1 = 1
else:
        contact1 = 0
contact2 = digitalio.DigitalInOut(board.D16)
contact2.switch_to_input(pull=digitalio.Pull.DOWN)
if contact2.value:
    contact2 = 1
else:
    contact2 = 0
contact3 = digitalio.DigitalInOut(board.D19)
contact3.switch_to_input(pull=digitalio.Pull.DOWN)
if contact3.value:
    contact3 = 1
else:
    contact3 = 0
contact4 = digitalio.DigitalInOut(board.D13)
contact4.switch_to_input(pull=digitalio.Pull.DOWN)
if contact4.value:
    contact4 = 1
else:
    contact4 = 0
contact5 = digitalio.DigitalInOut(board.D12)
contact5.switch_to_input(pull=digitalio.Pull.DOWN)
if contact5.value:
    contact5 = 1
else:
    contact5 = 0
contact6 = digitalio.DigitalInOut(board.D6)
contact6.switch_to_input(pull=digitalio.Pull.DOWN)
if contact6.value:
        contact6=1
else:
        contact6=0

# Print to STDIN
print("%s,%s,%s,%s,%s,%s" % (contact1,contact2,contact3,contact4,contact5,contact6))
