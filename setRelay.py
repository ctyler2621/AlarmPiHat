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

relay1 = digitalio.DigitalInOut(board.D4)
relay1.direction = digitalio.Direction.OUTPUT
relay2 = digitalio.DigitalInOut(board.D17)
relay2.direction = digitalio.Direction.OUTPUT

relay1.value = True
relay1.value = False
