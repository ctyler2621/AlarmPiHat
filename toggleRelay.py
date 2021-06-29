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
relay_num = int(str(sys.argv[1]))  # Denotes the relay
toggle_time = int(str(sys.argv[2]))  # Denotes the time to toggle

# Print some output so we know what is happening
print("Toggle relay %s for %s seconds" % (relay_num,toggle_time))

# Define the relay pins
relay1 = digitalio.DigitalInOut(board.D4)
#relay1.direction = digitalio.Direction.OUTPUT
relay2 = digitalio.DigitalInOut(board.D17)
#relay2.direction = digitalio.Direction.OUTPUT

relay1_status = relay1.value
relay2_status = relay2.value

#print("Relay Status: %s %s" %(relay1_status,relay2_status))

# Read the current state of the relays
if relay_num == 1:
	relay1.direction = digitalio.Direction.OUTPUT
	if relay1_status == True:
		relay1.value = False
		time.sleep(toggle_time)
		relay1.value = True
	else:
		relay1.value = True
		time.sleep(toggle_time)
		relay1.value = False

if relay_num == 2:
	relay2.direction = digitalio.Direction.OUTPUT
	if relay2_status == True:
		relay2.value = False
		time.sleep(toggle_time)
		relay2.value = True
	else:
		relay2.value = True
		time.sleep(toggle_time)
		relay2.value = False
