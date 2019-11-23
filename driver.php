<?php

require_once 'model/Driver.php';


if($_POST) {
  if ($_POST['method'] == "DELETE") {
    // DELETE driver
    if(empty($_POST['driverId'])) die("No DriverId given");
    Driver::deleteDriver($_POST['driverId']);
  } else {
    // New Driver
    if (empty($_POST['driverName'])) die("No driverName given!");
    Driver::addDriver($_POST['driverName']);
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
  foreach (Driver::listDrivers() as $row){
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

