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
import sqlite3
import smtplib, ssl
import board
import adafruit_am2320

# =============================================================================
# Codebase:
# Set the IO function for wiringPi to use the BCM pinout
wiringpi.wiringPiSetupGpio()

def getData():
    # Initialize the result variable as a dictionary
    result = {"contact1_alarm":0,"contact2_alarm":0,"contact_alarm3_alarm":0,"contact4_alarm":0,"contact5_alarm":0,"contact6_alarm":0,"relay1":0,"relay2":0,"LED":0,"Temp":0,"Humid":0}

    # Get temperature
    try:
        i2c = board.I2C()
        sensor = adafruit_am2320.AM2320(i2c)
        celsius = '{0}'.format(sensor.temperature)
        celsius = float(celsius)
        fahrenheit = round((celsius * 1.8) + 32 ,2)
        result.update({"Temp":fahrenheit})
    except:
        result.update({"Temp":"NaN"})

    # Get humidity
    try:
        i2c = board.I2C()
        sensor = adafruit_am2320.AM2320(i2c)
        #time.sleep(1) # Just to make sure that we aren't reading from the sensor too quickly
        humid = '{0}'.format(sensor.relative_humidity)
        result.update({"Humid":humid})
    except:
        result.update({"Humid":"NaN"})

    # Get the contact, LED and relay status from the device

    # Create a dictionary for values using the BCM numbering
    values_out = {"relay1":17,"relay2":4,"LED":21}
    values_in  = {"contact1":26,"contact2":16,"contact3":19,"contact4":13,"contact5":12,"contact6":6}

    # Set input pins as inputs and put internal resistors into pulldown mode
    for key, input in values_in.items():
        wiringpi.pinMode(input, 0)                        # Set BCM pin to INPUT
        wiringpi.pullUpDnControl(input, 1)                # Put the BCM pin in pull down resistor mode
        result.update({key:wiringpi.digitalRead(input)})  # Read the BCM pin

    # Set output pins as ouputs
    for key, output in values_out.items():
        wiringpi.pinMode(output, 1)                       # Set pin to OUTPUT
        result.update({key:wiringpi.digitalRead(output)}) # Read the BCM pin

    # Print the result for the end user just in case they are running it from the command line
    for key, value in result.items():
        if key == "LED" or key == "Humid" or key == "Temp":
            print(key,"\t:\t",value)
        else:
            print(key,":\t",value)
    print()

    # Return the result as a dictionary for ease of use later in the program
    return(result)

def writeDb(result):
    # TODO: This section probably doesn't work just yet as the sqlite tables are
    # named differently than what is in the result variable, so one will have to
    # change.

    # Write resulting data to the SQLite database on the ramdisk, everything will
    # reference the database so this shouldn't cause any issues with SNMP, etc.
    print("Write data to the Database")
    con = sqlite3.connect('ramdisk/config.db') # Connect to the database
    cur = con.cursor()                         # Init the cursor

    for key, value in result.items():
        if 'contact' in key:
            if value == 1:
                sql = "UPDATE config SET %s=datetime('now','localtime') WHERE 1" % (key)
                cur.execute(sql)
            else:
                cur.execute("UPDATE config SET %s=NULL WHERE 1" % key)
            con.commit()                               # Commit the changes to the database
            con.close()                                # Close the database connection

def notifier(result):
    # If any value in the results is in an active state, send a notification
    # Base the time between notificaitons on the timer values in the database
    # Check the last notification datetime with current datetime and if x seconds
    # have not passed do not send the notification.
    print("Send notification via email if necessary")
    smtp_server = "mail.totalhighspeed.net"
    port = 587  # For starttls
    sender_email = "chris@totalhighspeed.net"
    receiver_email = "chris@totalhighspeed.net"
    password = input("Type your password and press enter: ")

    # Create a secure SSL context
    context = ssl.create_default_context()

    # Try to log in to server and send email
    try:
        server = smtplib.SMTP(smtp_server,port)
        server.ehlo() # Can be omitted
        server.starttls(context=context) # Secure the connection
        server.ehlo() # Can be omitted
        server.login(sender_email, password)
        # TODO: Send email here
        message = """\
Subject: Hi there

This message is sent from Python."""
    except Exception as e:
        server.sendmail(sender_email, receiver_email, message)

        # Print any error messages to stdout
        print(e)
    finally:
        server.quit()

# Main code section
try:
    runcounter = 1
    while True:
        print("Run Count :\t",runcounter)
        # Run a continuous loop and get the data every x seconds
        wiringpi.pinMode(21, 1)     # Set the LED BCM pin to output
        wiringpi.digitalWrite(21,1) # Turn on the LED
        sleep(0.1)                  # Once the program takes a bit longer to run this can be removed
        result = getData()          # Get the data
        writeDb(result)            # Write the data to the database
        #notifier(result)           # Send notificaiton email if necessary
        wiringpi.digitalWrite(21,0) #Turn off the LED
        sleep(4.9)                  # Wait for x seconds
        runcounter += 1             # Increment the runcounter
except KeyboardInterrupt:
    exit()                  # Exit the program if CTRL-C is pressed
