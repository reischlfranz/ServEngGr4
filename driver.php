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
<head>
    <!--adding Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
<h4>
    <a href="index.html" class="badge badge-primary">Back to main page</a>
</h4>
<!-- -->
<hr />
<table class="table table-hover">
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
          <button type="submit" class="btn btn-danger">Delete</button>
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
</div>

</body>
</html>

