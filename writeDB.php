#!/usr/bin/env php
<?php
/* This script is used by the settings.php page to copy the database from the
ramdisk to the SD card on the RaspberryPi.*/
exec("cp /home/pi/AlarmPiHat/ramdisk/config.db /home/pi/AlarmPiHat/db/config.db");
?>
