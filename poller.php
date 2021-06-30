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
Documentation:
25	D26	37	Contact 1
27	D16	36	Contact 2
24	D19	35	Contact 3
23	D13	33	Contact 4
26	D12	32	Contact 5
22	D6	31	Contact 6
=============================================================================
*/

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
  if($contact_alarm != NULL){
    print "NOT NULL - NO DATA STORED";
  } else {
    print "NULL - STORING DATA";
    $contact_name = 'contact'.$counter.'_alarm';
    $now = new datetime();
    $now = $now->format('Y-m-d H:i:s');
    $pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
    $stm = $pdo->query("UPDATE config SET $contact_name=$now WHERE 1");
    $stm->execute();
  }
}

function clearalarm($counter){
  $contact_name = 'contact'.$counter.'_alarm';
  $pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
  $stm = $pdo->query("UPDATE config SET $contact_name=NULL WHERE 1");
  $stm->execute();
}

function checkalarm($contact){
  // check to see if alarm time is set and what the last alarm time was
  // Get the mail and contact naming information from the database
  $pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
  $stm = $pdo->query("SELECT * FROM config");
  $stm->execute();
  $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
  foreach($rows as $row){
    $contact1_alarm = $row['contact1_alarm'];
    $contact2_alarm = $row['contact2_alarm'];
    $contact3_alarm = $row['contact3_alarm'];
    $contact4_alarm = $row['contact4_alarm'];
    $contact5_alarm = $row['contact5_alarm'];
    $contact6_alarm = $row['contact6_alarm'];
  }

  $alarm_time = $row['contact'.$contact.'_alarm'];
  $alarm_start = new datetime($alarm_time);
  $rightnow = new datetime();
  $duration = $alarm_start->diff($rightnow);
  print "DEBUG: Duration in hours: ".$duration->h."\r\n";


  if(!empty($alarm_time)){
    if($duration->h >= 1){
      print "1 hour has passed, sending message\r\n";
      $status = "send";
    } else {
      print "Not sending message, 1 hour has not passed since last alarm\r\n";
      $status = "no_send";
    }
  } else {
    print "No alarm state, sending message\r\n";
    $status = "send";
  }
  return $status;
}
// Then if last alarm time was more than 60 mins ago send mail

function mailer($contact,$alarm,$now) {
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

  print "\r\nContact: $contact_name\r\n";

  if($alarm == "send"){
    // Diff the time so we know how long the alarm has been active
    $alarm_start = new datetime($contact_alarm);
    $rightnow = new datetime();
    $duration = $alarm_start->diff($rightnow);

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    try {
      // Format the message
      $msg  = "Date     : ".$now->format("Y-m-d")."\r\n";
      $msg .= "Time     : ".$now->format("H:i:s")."\r\n";
      $msg .= "Contact  : $contact_name\r\n\r\n";
      $msg .= "Duration : ".$duration->format('%Y-%m-%d %H:%i:%s')."\r\n";

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
$con1 = 1;

$contacts=array($con1,$con2,$con3,$con4,$con5,$con6);
$counter = 0;
foreach($contacts as $contact){
  $counter++;
  if($contact == 1){
    $alarm = checkalarm($contact);
    $contact_alarm = mailer($counter,$alarm,$now);
    storealarm($counter,$contact_alarm);
  } else {
    clearalarm($counter);
  }
}
?>
