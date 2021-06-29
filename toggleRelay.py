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
import time
import board
import digitalio
# =============================================================================
# Codebase:
# Map for Relays
# Relay 1 = D4
# Relay 2 = D17

# Get input from STDIN
relay_num = str(sys.argv[1])  # Denotes the relay
toggle_time = str(sys.argv[2])  # Denotes the time to toggle

# Define the relay pins
relay1 = digitalio.DigitalInOut(board.D4)
relay1.direction = digitalio.Direction.OUTPUT
relay2 = digitalio.DigitalInOut(board.D17)
relay2.direction = digitalio.Direction.OUTPUT

# Read the current state of the relays
if relay_num == 1:
	if relay1.value:
		relay1.value = 0
		sleep(toggle_time)
		relay1.value = 1
	else:
		relay1.value = 1
		sleep(toggle_time)
		relay1.value = 0

if relay_num == 2:
	if relay2.value:
		relay2.value = 0
		sleep(toggle_time)
		relay2.value = 1
	else:
		relay2.value = 1
		sleep(toggle_time)
		relay2.value = 0
