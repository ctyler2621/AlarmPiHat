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
#rom ... import ...
# =============================================================================
# Codebase:
from random import *

arr = []
for x in range(0,2):
    arr.insert(x,randint(0,1))

print ("%s,%s" % (arr[0],arr[1]))

#print("0,1")  # This is what the output should look like
