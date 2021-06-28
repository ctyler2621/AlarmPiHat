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
# =============================================================================
# Codebase:

# Map for Relays
# Relay 1 = D4
# Relay 2 = D17

# Define the pins for the relays
relay1 = digitalio.DigitalInOut(board.D4)
relay2 = digitalio.DigitalInOut(board.D17)

# Read the current state of the relays
if relay1.value:
        relay1_state = 1
else:
        relay1_state = 0

if relay2.value:
        relay2_state = 1
else:
        relay2_state = 0

# Output to STDIN
print("%s,%s" % (relay1_state,relay2_state))
