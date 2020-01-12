<?php

class Db
{
  private static $dbConString = 'sqlite:db/database.db';
  private static $dbVersionRequired = 5;

  /**
   * Request a database connection object with configured database connection string.
   * Tests for correct version of database schema, resets if not correct.
   * @return PDO Database connection object
   */
  static function getDbObject() {
    // Close after use!
//    echo "test";
//    header("etest:1");
//    die();
    syslog(LOG_ERR, "Getting DB object");
    $db  = new PDO(self::$dbConString)
        or die("cannot open the database")
        ;
//    header("btest:1");
    syslog(LOG_ERR, "DB object gotten got.");

    // Testing database version
    if(!self::isValidDb($db)){
      self::resetDb($db);
    }

    // Return db connection object. Reset ( $db = null; ) after every use!
    return $db;
  }

  /**
   * Tests for correct version of the database schema
   * @param $db PDO Database connection object
   * @return boolean
   */
  private static function isValidDb(PDO $db){
    $result = $db->query("SELECT version FROM version");
    // return true/false if database is valid
    return ($result && $result->fetchObject()->version == self::$dbVersionRequired);
  }

  /**
   * Reset database schema and fill with demo data
   * @param $db PDO Database connection object
   */
  static function resetDb(PDO $db){
    // Inform the user
    echo "<h1 style='color: red;'>Note: Database is being reset!</h1>";

    // Reset the schema of database
    if ($sqlSchema = file_get_contents('db/db-schema.sql')) {
      $var_array = explode(';', $sqlSchema);
      foreach ($var_array as $value) {
        $db->exec($value . ';');
      }
    }

    // Fill the database with demo data
    if ($sqlData = file_get_contents('db/db-demodata.sql')) {
      $var_array = explode(';', $sqlData);
      foreach ($var_array as $value) {
        $db->exec($value . ';');
      }
    }
  }

}

