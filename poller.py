#!/usr/bin/env python3
# Coding: utf-8
# =============================================================================
# Author: Christopher Tyler
# Email: chris@totalhighspeed.net
# Date created: 05AUG2021
# Python Version: 3
# Program Version: 0.1.0
# =============================================================================
# Documentation:
# This is for AlarmPiHat hardware v3.0
# =============================================================================
# Imports:
import wiringpi
import time
# =============================================================================
# Codebase:

# Set the IO function for wiringPi
wiringpi.wiringPiSetupGpio()

def getData():
    # Get the contact, LED and relay status from the device
    # Create a list for values
    values_out = [4,3]
    values_in  = [27,0,1,24,28,29,22]

    #Initialize the result variable as a list
    result = []

    counter = 0
    # Set input pins as inputs and put internal resistors into pulldown mode
    for input in values_in:
        wiringpi.pinMode(input, 0)         # Set pin to INPUT
        wiringpi.pullUpDnControl(input, 2) # Put the pin in pull down mode
        result.append(wiringpi.digitalRead(input))
        print(counter)
        counter += 1

    # Set output pins as ouputs
    for output in values_out:
        wiringpi.pinMode(output, 1)        # Set pin to OUTPUT
        result.append(wiringpi.digitalRead(output))
        counter += 1

    # Print some output for debugging from the command line
    print(result)

    # Return the result as a list
    return(result)

def writeDb(result):
    # Write resulting data to the SQLite database on the ramdisk, everything will
    # reference the database so this shouldn't cause any issues with SNMP, etc.
    print("Write to Database")

def notifier(result):
    # If any value in the results is in an active state, send a notification
    # Base the time between notificaitons on the timer values in the database
    # Check the last notification datetime with current datetime and if x seconds
    # have not passed do not send the notification.
    print("Notifier")
# Main code section
while 1:
    # Run a continuous loop and get the data every 5 seconds
    result = getData()
    writeDb(result)
    notifier(result)
    time.sleep(5)
