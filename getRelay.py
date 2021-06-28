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
        relay1 = 1
else:
        relay1 = 0

if relay2.value:
        relay2 = 1
else:
        relay2 = 0

# Output to STDIN
print("%s,%s" % (relay1,relay2))
