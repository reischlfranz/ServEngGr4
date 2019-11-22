<?php

class Db
{
  private static $dbConString = 'sqlite:db/database.db';

  static function getDbObject() {
    // Close after use!
    $db  = new PDO(self::$dbConString)
        or die("cannot open the database - please reset via <a href='Db.php?r=1'>THIS RESET LINK!</a>");
    // Testing database currently not
    //self::testDb($db);
    self::testDb2($db);
    return $db;
  }

  private static function testDb2($db){
    // more simple version
    $result = $db->query("SELECT COUNT(name) AS cnt FROM sqlite_master");
    // Count number of tables
    $cnt = $result->fetchObject()->cnt;
//    var_dump($cnt);
    if($cnt < 8){


      self::resetDb($db);
    }
  }
  private static function testDb($db){
    $resetPlz = true;
    $statement = $db->prepare("SELECT COUNT(name) AS cnt FROM sqlite_master");
    try{
      $statement->execute();
      // no success, database empty
      $res = $statement->fetch(PDO::FETCH_OBJ);
      if($res->cnt >= 8) {
        //correct amount of tables
        $resetPlz = false;
      }
    }catch (Exception $exception){
      var_dump($exception);
    }finally{
      if($resetPlz) self::resetDb($db);
    }
  }

  static function resetDb($db){
    echo "<h1 style='color: red;'>Note: Database is being reset!</h1>";

//    echo "<ul>";
    if ($sqlSchema = file_get_contents('db/db-schema.sql')) {
      $var_array = explode(';', $sqlSchema);
      foreach ($var_array as $value) {
        if (!empty($value)) {

          $db->exec($value . ';');
//          echo "<li>" . $value . ';' . "<br></li>";
//          var_dump($db->errorInfo());
        }

      }
    }
//    echo "</ul>done 1\n";

//    echo "<ul>";
    if($sqlData = file_get_contents('db/db-demodata.sql')) {
      $var_array = explode(';',$sqlData);
      foreach($var_array as $value) {
        $db->exec($value.';');
//        echo "<li>".$value.';'."<br></li>";
//        var_dump($db->errorInfo());
      }
    }
//    echo "</ul>\n";
  }



}
if($_GET['r']==1) Db::resetDb();

?>

