<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Alarm Panel</title>
</head>

<?php
// Get existing config from database for use on this page
$pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
$stm = $pdo->query("SELECT * FROM config");
$stm->execute();
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
  $relay_name_1 = $row['relay_name_1'];
  $relay_name_2 = $row['relay_name_2'];
  $contact_1 = $row['contact_name_1'];
  $contact_2 = $row['contact_name_2'];
  $contact_3 = $row['contact_name_3'];
  $contact_4 = $row['contact_name_4'];
  $contact_5 = $row['contact_name_5'];
  $contact_6 = $row['contact_name_6'];
}
?>

<body class="standard">
  <div id="container">
    <?php include('header.php'); ?>
    <div class="body">
      <?php
      // Get the sensor data from the python scripts
      $temp =`python3 getTemp.py`;
      $humid = `python3 getHumid.py`;

      //$contacts = `python3 getContacts.py`;
      /*PCB v3.0
      GPIO  PIN	DESCRIPTION
      4     16	Relay 1
      3     15	Relay 2
      27    36	Contact 1
      0     11	Contact 2
      1     12	Contact 3
      24    35	Contact 4
      28    38	Contact 5
      29    40	Contact 6
      22    31	PWR LED */
      $contact1 = exec("sudo gpio read 27");
      $contact2 = exec("sudo gpio read 0");
      $contact3 = exec("sudo gpio read 1");
      $contact4 = exec("sudo gpio read 24");
      $contact5 = exec("sudo gpio read 28");
      $contact6 = exec("sudo gpio read 29");
      $contacts = array($contact1,$contact2,$contact3,$contact4,$contact5,$contact6);

      $relay1 = exec("sudo gpio read 4");
      $relay2 = exec("sudo gpio read 3");
      $relays = array($relay1,$relay2);

      // TEMPERATURE AND HUMIDITY
      print "<span class='heading'>Temperature</span><br /><br />";
      print "<span class='readings'>".$temp."&deg;F</span><br />";
      print "<hr />";
      print "<span class='heading'>Relative Humidity</span><br /><br />";
      print "<span class='readings'>".$humid."%</span><br />";

      // DRY CONTACTS
      print "<hr />";
      print "<span class='heading'>Dry Contacts</span><br /><br />";
      print "<table>";
      //$contact = explode(',',$contacts);
      $contact_names = [$contact_1,$contact_2,$contact_3,$contact_4,$contact_5,$contact_6];
      $counter = 0;
      foreach($contact_names as $contact_name){
        print '<tr><td>'.$contact_name.':</td><td>';
        print '<label class="switch">';
        if($contacts[$counter] == 0){
          print'<input type="checkbox" onclick="return false;"/>';
          print '<span class="slider round"></span>';
          print '</label></td><td>OPEN</td></tr>';
        }else{
          print '<input type="checkbox" checked onclick="return false;"/>';
          print '<span class="slider round"></span>';
          print '</label></td><td>CLOSED</td></tr>';
        }
        $counter++;
      }
      print "</table>";

      // RELAYS
      print "<hr />";
      print "<span class='heading'>Relays</span><br /><br />";
      print "<table>";
      $relay_names = [$relay_name_1,$relay_name_2];
      $counter = 0;
      //$relays = explode(',',$relays);
      foreach($relay_names as $relay_name){
        print '<tr><td>'.$relay_name.':</td>';
        print '<td><label class="switch">';
        if($relays[$counter] == 0){
          print '<input type="checkbox" onclick="return false;"/>';
          print '<span class="slider round"></span>';
          print '</label></td><td>OFF</td>';
        } else {
          print '<input type="checkbox" checked onclick="return false;"/>';
          print '<span class="slider round"></span>';
          print '</label></td><td>ON</td>';
        }
        print '</tr>';
        $counter++;
      }
      print "</table>";
      ?>
    </div>
    <?php include('footer.php');?>
  </div>
</body>
</html>
