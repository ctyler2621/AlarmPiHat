<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Alarm Panel</title>
</head>

<?php

session_start();
if($_SESSION['auth'] == "ok"){
  echo "";
}else{
  print '<span style="font-weight:bold; color:#090;">Not logged in.</span>';
  print '<br /><a href="auth.php">Click here to log in!<a/><br />';
  print 'or <a href="index.php">go back to the status page';
  header('Location: index.php');
}

class Config {
  /**
  * path to the sqlite file
  */
  const PATH_TO_SQLITE_FILE = '/home/pi/AlarmPiHat/ramdisk/config.db';
}

class SQLiteConnection {
  /**
  * PDO instance
  * @var type
  */
  private $pdo;

  /**
  * return in instance of the PDO object that connects to the SQLite database
  * @return \PDO
  */

  public function connect() {
    if ($this->pdo == null) {
      $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
    }
    return $this->pdo;
  }

  public function getPassword() {
    $sql ='SELECT password FROM config WHERE id=1';
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
  }
}

class SQLiteUpdate {
  private $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function updatePassword($hash) {
    // SQL statement to update status of a task to completed
    $sql = "UPDATE config SET password = :hash WHERE id = 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':hash', $hash);
    return $stmt->execute();
  }

  public function updateCommunity($comm_string) {
    // SQL statement to update status of a task to completed
    $sql = "UPDATE config SET v2c_community = :comm_string WHERE id = 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':comm_string', $comm_string);
    return $stmt->execute();
  }

  public function updateContacts($contact_1,$contact_2,$contact_3,$contact_4,$contact_5,$contact_6) {
    // SQL statement to update status of a task to completed
    $sql = "UPDATE config
    SET
    contact_name_1 = :con1,
    contact_name_2 = :con2,
    contact_name_3 = :con3,
    contact_name_4 = :con4,
    contact_name_5 = :con5,
    contact_name_6 = :con6
    WHERE id = 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':con1', $contact_1);
    $stmt->bindValue(':con2', $contact_2);
    $stmt->bindValue(':con3', $contact_3);
    $stmt->bindValue(':con4', $contact_4);
    $stmt->bindValue(':con5', $contact_5);
    $stmt->bindValue(':con6', $contact_6);
    return $stmt->execute();
  }

  public function updateEmail($to_email,$from_email,$subject,$server,$user,$pass,$alert) {
    // SQL statement to update status of a task to completed
    $sql = "UPDATE config
    SET
    email_to = :email_to,
    email_from = :email_from,
    email_subject = :email_subject,
    smtp_server = :smtp_server,
    smtp_user = :smtp_user,
    smtp_pass = :smtp_pass,
    alert_timer = :alert_timer
    WHERE id = 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':email_to', $to_email);
    $stmt->bindValue(':email_from', $from_email);
    $stmt->bindValue(':email_subject', $subject);
    $stmt->bindValue(':smtp_server', $server);
    $stmt->bindValue(':smtp_user', $user);
    $stmt->bindValue(':smtp_pass', $pass);
    $stmt->bindValue(':alert_timer', $alert);
    return $stmt->execute();
  }

  public function updateRelays($relay1,$relay2,$relay1_check,$relay2_check,$relay1_init,$relay2_init) {
    if($relay1_check == ''){$relay1_check = "off";}
    if($relay2_check == ''){$relay2_check = "off";}
    if($relay1_init == ''){$relay1_init = "off";}
    if($relay2_init == ''){$relay2_init = "off";}
    $sql = "UPDATE config
    SET
    relay_name_1 = :relay_name_1,
    relay_name_2 = :relay_name_2,
    relay_status_1 = :relay_status_1,
    relay_status_2 = :relay_status_2,
    relay_init_1 = :relay_init_1,
    relay_init_2 = :relay_init_2
    WHERE id = 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':relay_name_1', $relay1);
    $stmt->bindValue(':relay_name_2', $relay2);
    $stmt->bindValue(':relay_status_1', $relay1_check);
    $stmt->bindValue(':relay_status_2', $relay2_check);
    $stmt->bindValue(':relay_init_1', $relay1_init);
    $stmt->bindValue(':relay_init_2', $relay2_init);
    return $stmt->execute();
  }
}
// Get existing config from database for use on this page
$pdo = new PDO('sqlite:/home/pi/AlarmPiHat/ramdisk/config.db');
$stm = $pdo->query("SELECT * FROM config");
$stm->execute();
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
  $password_hashed = $row['password'];
  $snmp_string = $row['v2c_community'];
  $relay_init_1 = $row['relay_init_1'];
  $relay_init_2 = $row['relay_init_2'];
  $relay_status_1 = $row['relay_status_1'];
  $relay_status_2 = $row['relay_status_2'];
  $relay_name_1 = $row['relay_name_1'];
  $relay_name_2 = $row['relay_name_2'];
  $contact_1 = $row['contact_name_1'];
  $contact_2 = $row['contact_name_2'];
  $contact_3 = $row['contact_name_3'];
  $contact_4 = $row['contact_name_4'];
  $contact_5 = $row['contact_name_5'];
  $contact_6 = $row['contact_name_6'];
  $email_to = $row['email_to'];
  $email_from = $row['email_from'];
  $email_subject = $row['email_subject'];
  $smtp_server = $row['smtp_server'];
  $smtp_user = $row['smtp_user'];
  $smtp_pass = $row['smtp_pass'];
  $alert_timer = $row['alert_timer'];
}
?>


<body class="standard">
  <div id="container">
    <?php include('header.php'); ?>
    <div class="body">
      <h1>Settings</h1>
      <form method=post>
        <span style="color:#900;">
          Settings are stored in RAM in order to conserve the SD card.<br />
          They are stored to disk at midnight each night. If you make <br />
          changes on this page and do not write them to disk they will <br />
          be lost after a reboot unless the reboot takes place after midnight<br />
          or you manually write the changes to disk using the button below.<br />
          Note that this is not the same as submitting changes. Submitting<br />
          changes only updates the database stored in RAM.<br />
        </span><br />
        <input type=submit name=write_to_disk value="Write to disk">
      </form>
      <?php
      if(isset($_POST['write_to_disk'])){
        exec("writeDb.php");
      }
      ?>
      <hr />
      <form method="post">
        <table>
          <tr><td colspan=3 class=heading>RELAYS</td></tr>
          <tr>
            <td colspan=3><input type=submit value="Toggle <?php print $relay_name_1?>" name=submit_toggle_1> for <input type=number min=1 max=60 value=10 name=toggle_time_1> seconds</td>
          </tr>
          <tr>
            <td colspan=3><input type=submit value="Toggle <?php print $relay_name_2?>" name=submit_toggle_2> for <input type=number min=1 max=60 value=10 name=toggle_time_2> seconds</td>
          </tr>
        </table>
      </form>
      <?php
      if(isset($_POST['submit_toggle_1'])){
        $toggle = 7; // Relay 1 pin D4 is tied to GPIO 7 pin 7
        $toggle_time = $_POST['toggle_time_1'];
      }elseif(isset($_POST['submit_toggle_2'])){
        $toggle = 0;  // Relay 2 pin D17 is tied to GPIO 0 pin 11
        $toggle_time = $_POST['toggle_time_2'];
      }

      if((isset($_POST['submit_toggle_1'])) || (isset($_POST['submit_toggle_2']))){

        $relaystate = exec("sudo gpio mode $toggle out&&sudo gpio read $toggle ");
        if($relaystate == 0){
          exec("sudo gpio write $toggle ON");
          sleep($toggle_time);
          exec("sudo gpio write $toggle OFF");
        }else{
          exec("sudo gpio write $toggle OFF");
          sleep($toggle_time);
          exec("sudo gpio write $toggle ON");
        }
      }
      ?>

      <form method=post>
        <table>
          <tr><td colspan="3"><hr /></td></tr>
          <tr>
            <td><input type=text name=relay1 value="<?php print $relay_name_1?>"></td>
            <?php
            if($relay_status_1 == "on"){
              $checked_on1 = "checked";
            } else {
              $checked_on1 = "";
            }
            ?>
            <td><label class="switch">
              <input type="checkbox" name=relay1_check <?php print $checked_on1 ?> />
              <span class="slider round"></span>
            </label>
          </td>
          <td>Init:</td>
          <?php
          if($relay_init_1 == "on"){
            $checked1 = "checked";
          } else {
            $checked1 = "";
          }
          ?>
          <td><input type=checkbox name=relay_init_1 <?php print $checked1; ?>></td>
        </tr>

        <tr>
          <td><input type=text name=relay2 value="<?php print $relay_name_2?>"></td>
          <?php
          if($relay_status_2 == "on"){
            $checked_on2 = "checked";
          } else {
            $checked_on2 = "";
          }
          ?>
          <td>
            <label class="switch">
              <input type="checkbox" name=relay2_check <?php print $checked_on2 ?> />
              <span class="slider round"></span>
            </label><br />
          </td>
          <td>Init:</td>
          <?php
          if($relay_init_2 == "on"){
            $checked2 = "checked";
          } else {
            $checked2 = "";
          }
          ?>
          <td><input type=checkbox name=relay_init_2 <?php print $checked2; ?>></td>
        </tr>
        <tr>
          <td colspan="3" align=center>
            <input type=submit value=Submit name=submit_relay> <input type=reset value=Reset name=reset>
          </td>
        </tr>
        <tr><td colspan=3>&nbsp;</td></tr>
      </table>
    </form>
    <?php
    if(isset($_POST['submit_relay'])){
      $relay1 = $_POST['relay1'];
      $relay2 = $_POST['relay2'];
      $relay1_check = $_POST['relay1_check'];
      $relay2_check = $_POST['relay2_check'];
      $relay1_init = $_POST['relay_init_1'];
      $relay2_init = $_POST['relay_init_2'];
      print "<br />";

      if($relay1_check == "on"){
        exec("sudo gpio mode 7 out&&sudo gpio write 7 on");
      } else {
        exec("sudo gpio mode 7 out&&sudo gpio write 7 off");
      }
      if($relay2_check == "on"){
        exec("sudo gpio mode 11 out&&sudo gpio write 0 on");
      }else{
        exec("sudo gpio mode 11 out&&sudo gpio write 0 off");
      }

      $pdo = (new SQLiteConnection())->connect();
      $conn = new SQLiteUpdate($pdo);
      $result = $conn->updateRelays($relay1,$relay2,$relay1_check,$relay2_check,$relay1_init,$relay2_init);
      if ($result) {
        echo 'Relays updated, refresh page to see changes.<br />';
        header("Refresh:0");
      }else{
        echo '<span style="font-weight:bold;color:#f00;">Database error.<br/ >Relays not updated</span><br />';
      }
    }
    ?>

    <form method="post">
      <table>
        <tr><td colspan=2><hr /></td></tr>
        <tr><td colspan=2 class=heading>RENAME CONTACTS</td></tr>
        <tr><td>Contact 1:</td><td><input type=text name=contact1 value="<?php print $contact_1; ?>" /></td></tr>
        <tr><td>Contact 2:</td><td><input type=text name=contact2 value="<?php print $contact_2; ?>" /></td></tr>
        <tr><td>Contact 3:</td><td><input type=text name=contact3 value="<?php print $contact_3; ?>" /></td></tr>
        <tr><td>Contact 4:</td><td><input type=text name=contact4 value="<?php print $contact_4; ?>" /></td></tr>
        <tr><td>Contact 5:</td><td><input type=text name=contact5 value="<?php print $contact_5; ?>" /></td></tr>
        <tr><td>Contact 6:</td><td><input type=text name=contact6 value="<?php print $contact_6; ?>" /></td></tr>
        <tr>
          <td colspan="2" align=center>
            <input type=submit value=Submit name=submit_contacts> <input type=reset value=Reset name=reset>
            <?php
            if(isset($_POST['submit_contacts'])){
              $contact_1 = $_POST["contact1"];
              $contact_2 = $_POST["contact2"];
              $contact_3 = $_POST["contact3"];
              $contact_4 = $_POST["contact4"];
              $contact_5 = $_POST["contact5"];
              $contact_6 = $_POST["contact6"];
              print "<br />";

              $pdo = (new SQLiteConnection())->connect();
              $conn = new SQLiteUpdate($pdo);
              $result = $conn->updateContacts($contact_1,$contact_2,$contact_3,$contact_4,$contact_5,$contact_6);
              if ($result) {
                echo 'Contact names updated, refresh page to see changes.<br />';
                header("Refresh:0");
              }else{
                echo '<span style="font-weight:bold;color:#f00;">Database error.<br/ >Contact names not updated</span><br />';
              }

            }
            ?>
          </td>
          <tr><td colspan=2><hr /></td></tr>
        </table>
      </form>
      <form method="post">
        <table>
          <tr><td colspan=2 class=heading>SNMP</td></tr>
          <tr>
            <td>v2c Community String:</td>
            <td><input type=text name=community value="<?php print $snmp_string;?>"></td>
            <tr>
              <td colspan="2" align=center>
                <input type=submit value=Submit name=submit_snmp> <input type=reset value=Reset name=reset>
                <?php
                if(isset($_POST['submit_snmp'])){
                  $comm_string = $_POST["community"];
                  print "<br />";

                  $pdo = (new SQLiteConnection())->connect();
                  $conn = new SQLiteUpdate($pdo);
                  $result = $conn->updateCommunity($comm_string);
                  if ($result) {
                    echo 'Community string updated, refresh page to see changes.<br />';
                    header("Refresh:0");
                  }else{
                    echo '<span style="font-weight:bold;color:#f00;">Database error.<br/ >Community string not updated ['.$comm_string.']</span><br />';
                  }

                }
                ?>
              </td>
            </tr>
          </tr>
          <tr><td colspan=2>&nbsp;</td></tr>
          <tr><td colspan=2><hr /></td></tr>
        </table>
      </form>
      <form method="post">
        <table>
          <tr><td colspan=2 class=heading>EMAIL</td></tr>
          <tr>
            <td>SMTP Server:</td>
            <td><input type=text name=smtp_server value="<?php print $smtp_server; ?>"></td>
          </tr>
          <tr>
            <td>SMTP User:</td>
            <td><input type=text name=smtp_user value="<?php print $smtp_user; ?>"></td>
          </tr>
          <tr>
            <td>SMTP Password:</td>
            <td><input type=text name=smtp_pass value="<?php print $smtp_pass; ?>"></td>
          </tr>
          <tr>
            <td>Mail To Address:</td>
            <td><input type=text name=to_email value="<?php print $email_to; ?>"></td>
          </tr>
          <tr>
            <td>Mail From Address:</td>
            <td><input type=text name=from_email value="<?php print $email_from; ?>"></td>
          </tr>
          <tr>
            <td>Subject Line:</td>
            <td><input type=text name=subject value="<?php print $email_subject; ?>"></td>
          </tr>
          <tr>
            <td>Notification Timer:</td>
            <td><input type=number min=1 max=60 name=alert_timer value="<?php print $alert_timer; ?>"> minutes</td>
          </tr>
          <tr>
            <td colspan="2" align=center>
              <input type=submit value=Submit name=submit_email> <input type=reset value=Reset name=reset>
              <?php
              if(isset($_POST['submit_email'])){
                $to_email = $_POST["to_email"];
                $from_email = $_POST["from_email"];
                $subject = $_POST["subject"];
                $server = $_POST['smtp_server'];
                $user = $_POST['smtp_user'];
                $pass = $_POST['smtp_pass'];
                $alert = $_POST['alert_timer'];

                print "<br />";

                $pdo = (new SQLiteConnection())->connect();
                $conn = new SQLiteUpdate($pdo);
                $result = $conn->updateEmail($to_email,$from_email,$subject,$server,$user,$pass,$alert);
                if ($result) {
                  echo 'Email information updated, refresh page to see changes.<br />';
                  header("Refresh:0");
                }else{
                  echo '<span style="font-weight:bold;color:#f00;">Database error.<br/ >Email information not updated</span><br />';
                }

              }
              ?>
            </td>
          </tr>
        </tr>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan=2><hr /></td></tr>
      </table>
    </form>
    <form method="post">
      <table>
        <tr><td colspan=2 class=heading>PASSWORD</td></tr>
        <tr>
          <td>Old Password:</td>
          <td><input type=password name=old_pass></td>
        </tr>
        <tr>
          <td>New Password:</td>
          <td><input type=password name=new_pass1></td>
        </tr>
        <tr>
          <td>Confirm Password:</td>
          <td><input type=password name=new_pass2></td>
        </tr>
        <tr>
          <td colspan="2" align=center>
            <input type=submit value=Submit name=submit_pass> <input type=reset value=Reset name=reset>
            <?php
            // Reset Password

            if(isset($_POST['submit_pass'])){

              $conn = new SQLiteConnection;
              $conn->connect();
              $password_hashed = $conn->getPassword();

              $old_pass = $_POST["old_pass"];
              $new_pass1 = $_POST["new_pass1"];
              $new_pass2 = $_POST["new_pass2"];

              print "<br />";
              if(password_verify($old_pass, $password_hashed)){
                if($new_pass1 == $new_pass2){
                  $hash = password_hash($new_pass1, PASSWORD_DEFAULT);
                  $pdo = (new SQLiteConnection())->connect();
                  $conn = new SQLiteUpdate($pdo);
                  $result = $conn->updatePassword($hash);
                  if ($result)
                  echo 'Password Updated<br />';
                  else
                  echo '<span style="font-weight:bold;color:#f00;">Database error. Password not updated</span><br />';
                }else{
                  print '<span style="font-weight:bold;color:#f00;">Passwords do not match</span>';
                }
              } else {
                print '<span style="font-weight:bold;color:#f00;">Password is Incorrect</span>';
              }
            }
            ?>
          </td>
        </tr>
      </table>
    </form>
    <br />
    <a href="index.php">BACK</a>
  </div>
  <div align=center>
    <form method="post">
      <input type=submit name=logout value="Log Out">
    </form>
  </div>
  <?php
  if(isset($_POST['logout'])){
    // remove all session variables and destroy the session.
    session_unset();
    session_destroy();
    print '<br /><a style="font-weight:bold;color:#090;" href="index.php">Logged out of session click here to go back to main page<a/>';
    header('Location: index.php');
  }
  include('footer.php');?>
</div>
</body>
</html>
