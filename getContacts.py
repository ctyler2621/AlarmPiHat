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
#from ... import ...
# =============================================================================
# Codebase:
from random import *

arr = []
for x in range(0,6):
    arr.insert(x,randint(0,1))

print ("%s,%s,%s,%s,%s,%s" % (arr[0],arr[1],arr[2],arr[3],arr[4],arr[5]))

#print("0,0,0,1,0,0")  # This is what the output should look like
