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
import sys
import board
import digitalio
# =============================================================================
# Codebase:

# Get input from STDIN
userin1 = str(sys.argv[1])
userin2 = str(sys.argv[2])

# Set the relay state based on the input
if userin1 == "0":   # OFF
	userin1 = False
elif userin1 == "1": # ON
	userin1 = True

if userin2 == "0":   # OFF
	userin2 = False
elif userin2 == "1": # ON
	userin = True

# Map for Relays
# Relay 1 = D4
# Relay 2 = D17

# Define the relay pins
relay1 = digitalio.DigitalInOut(board.D4)
relay1.direction = digitalio.Direction.OUTPUT
relay2 = digitalio.DigitalInOut(board.D17)
relay2.direction = digitalio.Direction.OUTPUT

# Set the pins to the appropriate values
relay1.value = userin1
relay2.value = userin2
