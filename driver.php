<?php
require_once 'Db.php';

function addDriver($name){
  $db = Db::getDbObject();
  $statement = $db->prepare("INSERT INTO drivers (drivername) VALUES (:name)");
  $statement->bindParam(':name',$name);
  $statement->execute();

  $db = null;
  return listDrivers();
}

function listDrivers(){
  $db = Db::getDbObject();
  $query = 'SELECT * FROM drivers';
  // Save Result as array (because connection will be closed afterwards!)
  $resultArray = $db->query($query)->fetchAll();
  $db = null; // close connection
  return $resultArray;
}

function deleteDriver($driverId){
  $db = Db::getDbObject();
  $statement = $db->prepare('DELETE FROM drivers WHERE driverid = :driverid');
  $statement->bindParam(':driverid',$driverId, PDO::PARAM_INT);
  $statement->execute();
  $db = null;
  return listDrivers();
}

if($_POST) {
  if ($_POST['method'] == "DELETE") {
    // DELETE driver
    if(empty($_POST['driverId'])) die("No DriverId given");
    deleteDriver($_POST['driverId']);
  } else {
    // New Driver
    if (empty($_POST['driverName'])) die("No driverName given!");
    addDriver($_POST['driverName']);
  }
}


?>

<html>
<body>

<a href="index.html">Back to main page</a>

<!-- -->
<hr />
<table>
  <thead>
  <tr>
    <th>Driver ID</th>
    <th>Driver Name</th>
    <th><i>Delete</i></th>
  </tr>
  </thead>
  <tbody>
<?php
  foreach (listDrivers() as $row){
    ?>
  <tr>
    <td><?= $row['driverid'] ?></td>
    <td><?= $row['drivername'] ?></td>
    <td>
      <form action="driver.php" method="post">
        <input type="hidden" name="method" value="DELETE">
        <input type="hidden" name="driverId" value="<?= $row['driverid'] ?>">
        <button type="submit">Delete</button>
      </form>
    </td>
  </tr>
<?php
  }
?>
  </tbody>
</table>

<!-- -->
<hr />
<form action="driver.php" method="post">
  <h3>Add Driver</h3>
  <label for="driverName">Driver Name</label>
  <input type="text" name="driverName">
  <input type="submit">
</form>
<!-- -->


</body>
</html>

