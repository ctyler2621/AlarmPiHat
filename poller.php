#!/usr/bin/env php
<?php
/*
Coding: utf-8
=============================================================================
Author: Christopher Tyler
Email: chris@totalhighspeed.net
Date created: 21JUN2021
Date last modified: 21JUN2021
PHP Version: 7.3
Program Version: 0.0.0
=============================================================================
Pinouts:
PCB v2.2
GPIO  BCM PIN DESCRIPTION
25    D26	37	Contact 1
27    D16	36	Contact 2
24    D19	35	Contact 3
23    D13	33	Contact 4
26    D12	32	Contact 5
22    D6	31	Contact 6

PCB v3.0
GPIO  PIN	DESCRIPTION
4     16	Relay 1
3     15	Relay 2
27    36	Contact 1
0     11	Contact 2
1     12	Contact 3
24    35	Contact 4
28    38	Contact 5
29    40	Contact 6
22    31	PWR LED
=============================================================================
NOTE: For whatever reason, GPIO pin 22 (Contact 6) is borked on the Raspberry Pi 4 and
will not read correctly, even if the internal resistor is set to pulldown mode. If not
set to pulldown mode, the pin always reads as high, and if set to pulldown it will
always read low, regarless if the pin is connected across the 3v rail or not.
I have not tried the PCB v3.0 pinouts yet so I don't know if they will work better or
not. Pin 22 is set as a relay output on v3.0 boards and I think that will work as one
would expect. However, now the other pins are questionable now so we'll see. Might have
to make one more hardware revision to get everything working properly.

TODO: Need to figure out how to send a recovery notification as well, right now it just stops sending emails
but there is no notification when a contact ceases to be in an alarm state.
*/
exec("gpio mode 29 out&&gpio write 29 off");
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set("America/Chicago");

//Load Composer's autoloader
require 'vendor/autoload.php';

function storealarm($counter,$contact_alarm){
  # Store the data only if there isn't a time stored already
  if($contact_alarm == NULL){
    print "Alarm data stored in database\r\n";
    $contact_name = 'contact'.$counter.'_alarm';
    $pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
    $stm = $pdo->query("UPDATE config SET $contact_name=datetime('now','localtime') WHERE 1");
    $stm->execute();
  }
}

function clearalarm($counter){
  // If there isn't currently and alarm then clear the contact(x)_alarm field in the database
  $contact_name = 'contact'.$counter.'_alarm';
  $notification_name = 'notification'.$counter;
  $pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
  $stm = $pdo->query("UPDATE config SET $contact_name=NULL, $notification_name=NULL WHERE 1");
  $stm->execute();
}

function checkalarm($contact,$notification_timer){
  // Get the mail and contact naming information from the database
  $pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
  $stm = $pdo->query("SELECT * FROM config");
  $stm->execute();
  $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $notification1 = $row['notification1'];
    $notification2 = $row['notification2'];
    $notification3 = $row['notification3'];
    $notification4 = $row['notification4'];
    $notification5 = $row['notification5'];
    $notification6 = $row['notification6'];
    $notificaiton_timer = $row['alert_timer'];
  }

  $alarm_time = $row['notification'.$contact];
  $alarm_start = new datetime($alarm_time);
  $rightnow = new datetime();
  $duration = $alarm_start->diff($rightnow);
  print "Notificaiton Contact ".$contact.": ".$duration->d." Days ".$duration->h." Hours ".$duration->i." Minutes\r\n";

  if(!empty($alarm_time)){
    if($duration->i >= $notification_timer){
      print "$notification_timer minutes or more have passed, sending message\r\n";
      $status = "send";
    } else {
      print "Not sending message, $notification_timer minutes have not passed since last alarm\r\n";
      $status = "no_send";
    }
  } else {
    print "No previous alarm state, sending message\r\n";
    $status = "send";
  }
  return $status;
}

function mailer($contact,$now,$notification_timer) {
  // Check to see if last notification was within the timer or not
  $alarm = checkalarm($contact,$notification_timer);

  // Get the mail and contact naming information from the database
  $pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
  $stm = $pdo->query("SELECT * FROM config");
  $stm->execute();
  $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $contact_1 = $row['contact_name_1'];
    $contact_2 = $row['contact_name_2'];
    $contact_3 = $row['contact_name_3'];
    $contact_4 = $row['contact_name_4'];
    $contact_5 = $row['contact_name_5'];
    $contact_6 = $row['contact_name_6'];
    $contact1_alarm = $row['contact1_alarm'];
    $contact2_alarm = $row['contact2_alarm'];
    $contact3_alarm = $row['contact3_alarm'];
    $contact4_alarm = $row['contact4_alarm'];
    $contact5_alarm = $row['contact5_alarm'];
    $contact6_alarm = $row['contact6_alarm'];
    $notification1 = $row['notification1'];
    $notification2 = $row['notification2'];
    $notification3 = $row['notification3'];
    $notification4 = $row['notification4'];
    $notification5 = $row['notification5'];
    $notification6 = $row['notification6'];
    $mailto = $row['email_to'];
    $mailfrom = $row['email_from'];
    $subject = $row['email_subject'];
    $smtp_server = $row['smtp_server'];
    $smtp_user = $row['smtp_user'];
    $smtp_pass = $row['smtp_pass'];
  }

  // Corrolate the name to the contact in alarm state
  $contact_name = $row['contact_name_'.$contact];
  $contact_alarm = $row['contact'.$contact.'_alarm'];
  $notification = $row['notification'.$contact];

  if($alarm == "send"){
    // Diff the time so we know how long the alarm has been active
    $alarm_start = new datetime($contact_alarm);
    $rightnow = new datetime();
    $duration = $alarm_start->diff($rightnow);

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    try {
      // Format the message
      $msg  = "Date     : ".$now->format("Y-M-d")."\r\n";
      $msg .= "Time     : ".$now->format("H:i:s")."\r\n";
      $msg .= "Contact  : ".$contact_name."\r\n";
      $msg .= "Duration : ".$duration->d." Days ".$duration->h." Hours ".$duration->i." Minutes\r\n";

      // Subject line
      $subject = $subject." - ".$contact_name." detected";

      //Server settings
      //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = $smtp_server;                           //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = $smtp_user;                             //SMTP username
      $mail->Password   = $smtp_pass;                             //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
      $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

      //Recipients
      $mail->setFrom($mailfrom, 'Alarm Monitor');
      //$mail->addAddress($mailto, 'User');                         //Add a recipient
      $mail->addAddress('chris@totalhighspeed.net','Chris Tyler');
      $mail->addReplyTo('noreply@totalhighspeed.net', 'NoReply');

      //Content
      $mail->isHTML(false);                                       //Set email format to plaintext
      $mail->Subject = $subject;
      $mail->Body    = $msg;

      $mail->send();
      echo "Message has been sent\r\n";

      // Write $notification to the database
      $notification_name = "notification$contact";
      $pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
      $stm = $pdo->query("UPDATE config SET $notification_name=datetime('now','localtime') WHERE 1");
      $stm->execute();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\r\n";
    }
  }


  return $contact_alarm;
}

$now = new DateTime;

// Get status of contacts
$con1 = exec("gpio read 25");
$con2 = exec("gpio read 27");
$con3 = exec("gpio read 24");
$con4 = exec("gpio read 23");
$con5 = exec("gpio read 26");
$con6 = exec("gpio read 22");

$contacts=array($con1,$con2,$con3,$con4,$con5,$con6);
$counter = 0;
foreach($contacts as $contact){
  $counter++;
  if($contact == 1){
    $contact_alarm = mailer($counter,$now,$notification_timer);
    storealarm($counter,$contact_alarm);
  } else {
    clearalarm($counter);
  }
}
exec("gpio mode 29 out&&gpio write 29 on");
?>
