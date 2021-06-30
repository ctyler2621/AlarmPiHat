<?php
session_start();

function usage($error) {
  print "\r\n  [ $error ]\r\n";
  print"
  This script allows you to update the password for this device.\r\n
  \r\n
  -h --help                 Get contextual help (this text)\r\n
  -p --password [password]  Provide a new password. Password must not be empty or less
                            than eight charaters in length. Best practice is to encase
                            the password string in quotes so that it accepts special
                            characters.\r\n
  \r\n
  Example Usage: php setPassword.php -p \"MyPass\"\r\n";
  exit();
}


if(count($argv) == 1) {
  usage("Script requires input from user");
}elseif(($argv[1] == "-p") || ($argv[1] == "--password")){
  $length = strlen($argv[2]);
  if(strlen($argv[2]) >= 8){
    $new_pass = $argv[2];
  }else{
    usage("Provided password is too short");
  }
}elseif(($argv[1] == "-h") || ($argv[1] == "--help")){
  usage("Help requested by user");
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

  public function connect() {
      if ($this->pdo == null) {
          $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
      }
      return $this->pdo;
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
}

// Set the password
$hash = password_hash($new_pass, PASSWORD_DEFAULT);
$pdo = (new SQLiteConnection())->connect();
$conn = new SQLiteUpdate($pdo);
$result = $conn->updatePassword($hash);
if ($result)
    echo "\r\n  Password updated using $new_pass\r\n\r\n";
else
    echo "\r\n  ERROR, Password not updated\r\n\r\n";
?>
