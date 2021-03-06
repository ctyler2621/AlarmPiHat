<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Alarm Panel</title>
</head>

<body class="standard">
  <div id="container">
    <?php include('header.php'); ?>
    <div class="body">
      <?php
      /*
      Setup a the session cookie for locking out the settings page.
      Check the password against the data base and if the hash matches then proceed to
      the settings page and if not, then block getting there.
      */
      session_start();
      ?>

      <form method="post">
        Password: <input type=password name=password_check autofocus> <input type=submit name=submit value=Submit><br />
        <br />
        <span style="font-size:.7em">
          Forgot your password?<br />
          Run &quot;php ./setPassword.php&quot; from the command line to reset it.
        </span>
      </form>
    </div>
    <?php
    if(isset($_POST["submit"])){
      // Open the config.db database
      class MyDB extends SQLite3 {
        function __construct() {
          $this->open('/home/pi/AlarmPiHat/ramdisk/config.db');
        }
      }

      // If connection fails echo the error to the webpage
      $db = new MyDB();
      if(!$db) {
        echo $db->lastErrorMsg();
      }

      // Pull all of the data from the databse
      $sql =<<<EOF
      SELECT * from config;
      EOF;

      $ret = $db->query($sql);

      // Put the data into variables for later use
      while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
        $password_hashed = $row['password'];
      }
      $db->close();

      $password = $_POST["password_check"];

      if(password_verify($password, $password_hashed)) {
        $_SESSION['auth'] = "ok";
        // Proceed to settings
        header('Location: settings.php');
      } else {
        print '<span style="font-weight:bold;color:#f00;">PASSWORD IS INCORRECT</span><br />';
      }
    }
    include('footer.php');
    ?>
  </div>
</body>
