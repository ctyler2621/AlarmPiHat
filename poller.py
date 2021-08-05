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
from time import sleep
# =============================================================================
# Codebase:

def getData():
    # Get the contact, LED and relay status from the device

    # Set the IO function for wiringPi to use the BCM pinout
    wiringpi.wiringPiSetupGpio()

    # Create a list for values using the BCM numbering
    values_out = {"relay1":17,"relay2":4,"LED":21}
    values_in  = {"con1":26,"con2":16,"con3":19,"con4":13,"con5":12,"con6":6}

    #Initialize the result variable as a list
    result = {"con1":0,"con2":0,"con3":0,"con4":0,"con5":0,"con6":0,"relay1":0,"relay2":0,"LED":0}

    # Set input pins as inputs and put internal resistors into pulldown mode
    for key, input in values_in.items():
        wiringpi.pinMode(input, 0)         # Set pin to INPUT
        wiringpi.pullUpDnControl(input, 1) # Put the pin in pull down mode
        result.update({key:wiringpi.digitalRead(input)})

    # Set output pins as ouputs
    for key, output in values_out:
        wiringpi.pinMode(output, 1)        # Set pin to OUTPUT
        result.update({key:wiringpi.digitalRead(output)})

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
try:
    while True:
        # Run a continuous loop and get the data every 5 seconds
        result = getData()
        #writeDb(result)
        #notifier(result)
        sleep(1)
        result.clear()
except KeyboardInterrupt:
    exit()
