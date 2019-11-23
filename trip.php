<?php
require_once 'model/Db.php';
require_once 'model/Driver.php';
require_once 'model/Car.php';

function listTrips() {
  $db = Db::getDbObject();
  $query = "SELECT t.tripid, t.direction, d.drivername, c.carname, t.timestart, t.timearrival, 
            (strftime('%s', timearrival) - strftime('%s', timestart) ) AS triptime
            FROM trip t 
            JOIN cars c on t.carid = c.carid
            JOIN drivers d on t.driverid = d.driverid
            ;
            ";
  // Save Result as array (because connection will be closed afterwards!)
  $resultArray = $db->query($query)->fetchAll();
  $db = null; // close connection
  return $resultArray;
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
        <th>Richtung</th>
        <th>Fahrer</th>
        <th>Auto</th>
        <th>Abfahrt</th>
        <th>Ankunft</th>
        <th>Fahrzeit (Sekunden)</th>
        <th>Gäste</th>
        <th>Freie Plätze</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach (listTrips() as $row){
        ?>
        <tr>
            <td><?= $row['direction'] ?></td>
            <td><?= $row['drivername'] ?></td>
            <td><?= $row['carname'] ?></td>
            <td><?= $row['timestart'] ?></td>
            <td><?= $row['timearrival'] ?></td>
            <td><?= $row['triptime'] ?></td>
            <td>??</td>
            <td>??</td>
            <td>
                <form XXaction="trip.php" method="post">
                    <input type="hidden" name="method" value="DELETE">
                    <input type="hidden" name="tripId" value="<?= $row['id'] ?>">
                    <button type="submit" disabled="disabled">Delete</button>
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
<a href="guest.php">Gäste</a>

<form Xaction="trip.php" method="post">
    <h3>Fahrten hinzufügen</h3>
    <label for="direction">Richtung</label>
    <input type="text" name="direction" disabled="disabled">

    <label for="driverselect">Fahrer</label>
    <select name="driverselect">
      <?php
      foreach (Driver::listDrivers() as $driver) {
        ?>
          <option value="<?= $driver['driverid'] ?>"><?= $driver['drivername'] ?></option>
        <?php
      }
      ?>
    </select>

    <label for="carselect">Auto</label>
    <select name="carselect">
      <?php
      foreach (Car::listCars() as $car) {
        ?>
          <option value="<?= $car['carid'] ?>"><?= $car['carname'] ?></option>
        <?php
      }
      ?>
    </select>


    <input type="submit" disabled="disabled">
</form>

<!-- -->
<hr />
<form Xaction="driver.php" method="post">
    <h3>Add Driver</h3>
    <label for="firstName">First Name</label>
    <input type="text" name="firstName">
    <label for="lastName">Last Name</label>
    <input type="text" name="lastName">
    <input type="submit" disabled="disabled">
</form>
<!-- -->


</body>
</html>
