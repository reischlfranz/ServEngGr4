<?php
require_once 'Db.php';

function addDriver($firstName, $lastName){
  $db = Db::getDbObject();
  $statement = $db->prepare("INSERT INTO driver (firstname, lastname, airport, available) VALUES (:firstName, :lastName, :airport, :avail)");
  $statement->bindParam(':firstName',$firstName);
  $statement->bindParam(':lastName',$lastName);
  $statement->bindValue(':airport',1);
  $statement->bindValue(':avail',true);
  $statement->execute();

  $db = null;
  return listDrivers();
}

function listDrivers(){
  $db = Db::getDbObject();
  $query = 'SELECT * FROM driver';
  // $result = $db->query($query);
  // Save Result as array (because connection will be closed afterwards!)
  $resultArray = $db->query($query)->fetchAll();
  $db = null; // close connection
  //return $result->fetchAll();
  return $resultArray;
}

function deleteDriver($driverId){
  $db = Db::getDbObject();
  $statement = $db->prepare('DELETE FROM driver WHERE id = :driverId');
  $statement->bindParam(':driverId',$driverId, PDO::PARAM_INT);
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
    if (empty($_POST['firstName'])) die("No firstName given!");
    if (empty($_POST['lastName'])) die("No lastName given!");
    addDriver($_POST['firstName'], $_POST['lastName']);
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
    <th>First Name</th>
    <th>Last Name</th>
    <th><i>Delete</i></th>
  </tr>
  </thead>
  <tbody>
<?php
  foreach (listDrivers() as $row){
    ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['firstname'] ?></td>
    <td><?= $row['lastname'] ?></td>
    <td>
      <form action="driver.php" method="post">
        <input type="hidden" name="method" value="DELETE">
        <input type="hidden" name="driverId" value="<?= $row['id'] ?>">
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
  <label for="firstName">First Name</label>
  <input type="text" name="firstName">
  <label for="lastName">Last Name</label>
  <input type="text" name="lastName">
  <input type="submit">
</form>
<!-- -->


</body>
</html>

