<?php

class Config {
 /**
  * path to the sqlite file
  */
  const PATH_TO_SQLITE_FILE = 'db/config.db';
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

  public function getPassword() {
        $sql ='SELECT password FROM config WHERE id=1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
  }

  public function getData() {
    $sql ='SELECT * FROM config WHERE id=1';
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
}

$exec="whoami";
exec($exec);

// Test getting all data
$pdo = new PDO('sqlite:db/config.db');

$stm = $pdo->query("SELECT * FROM config");
$rows = $stm->fetchAll(PDO::FETCH_NUM);
var_dump($rows);

foreach($rows as $row) {
    printf("$row[0] $row[1] $row[2] $row[3] $row[4] $row[5] $row[6] $row[7] $row[8] $row[9] $row[10]\n");
}

$stm = $pdo->query("SELECT * FROM config");
$stm->execute();
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $row){
  $id = $row['id'];
  $password_hashed = $row['password'];
  $snmp_string = $row['v2c_community'];
  $relay_init_1 = $row['relay_init_1'];
  $relay_init_2 = $row['relay_init_2'];
  $relay_status_1 = $row['relay_status_1'];
  $relay_status_2 = $row['relay_status_2'];
  $contact_1 = $row['contact_name_1'];
  $contact_2 = $row['contact_name_2'];
  $contact_3 = $row['contact_name_3'];
  $contact_4 = $row['contact_name_4'];
  $contact_5 = $row['contact_name_5'];
  $contact_6 = $row['contact_name_6'];
  $email_to = $row['email_to'];
  $email_from = $row['email_from'];
  $email_subject = $row['email_subject'];
}

?>
