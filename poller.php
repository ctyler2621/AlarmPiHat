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

//Load Composer's autoloader
require 'vendor/autoload.php';

function mailer($contact) {
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
    $mailto = $row['email_to'];
    $mailfrom = $row['email_from'];
    $subject = $row['email_subject'];
    $smtp = $row['smtp_server'];
    $smtp_user = $row['smtp_user'];
    $smtp_pass = $row['smtp_pass'];
  }
  $now = new DateTime;

  // Corrolate the name to the contact in alarm state
  $contact_name = $row['contact_name_'.$contact];
  print "\r\nContact: $contact_name\r\n";

  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);
  try {
    // Format the message
    $msg  = "Date     : ".$now->format("Y-m-d")."\r\n";
    $msg .= "Time     : ".$now->format("H:i:s")."\r\n";
    $msg .= "Contact  : $contact_name\r\n\r\n";
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
    mailer($counter);
  }
}
?>
