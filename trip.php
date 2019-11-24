<?php
require_once 'model/Car.php';
require_once 'model/Driver.php';
require_once 'model/Guest.php';
require_once 'model/Trip.php';

if ($_POST) {
  if (!empty($_POST['task']) && $_POST['task'] == 'adddropoff') {
    // add Dropoff reservation

    if(empty($_POST['dropoffdate'])) die("No dropoffdate specified!");
    if(empty($_POST['guestid'])) die("No Guest specified!");
    Guest::addDropOff($_POST['guestid'], $_POST['dropoffdate']);
  }
  if (!empty($_POST['task']) && $_POST['task'] == 'addpickup') {
    // add Pickup reservation

    if(empty($_POST['pickupdate'])) die("No pickupdate specified!");
    if(empty($_POST['guestid'])) die("No Guest specified!");
    Guest::addPickUp($_POST['guestid'], $_POST['pickupdate']);
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
    foreach (Trip::listTrips() as $row){
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
<h3>Pickups</h3>
<table>
    <thead>
    <tr>
        <th>Gast-ID</th>
        <th>Gast Name</th>
        <th>Pick-Up Time</th>
        <th>Gebucht?</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach (Guest::listGuestsForPickUp() as $pick){
      if(empty($pick['date'])) continue;
      ?>
        <tr>
            <td><?= $pick['guestid'] ?></td>
            <td><?= $pick['guestname'] ?></td>
            <td><?= $pick['date'] ?></td>
            <td><?= empty($pick['tripid'])?'No':'Yes'; ?></td>
        </tr>
      <?php
    }
    ?>
    </tbody>
</table>

<form action="trip.php" method="post">
    <input type="hidden" name="task" value="addpickup">
    <h3>Pickup hinzufügen</h3>
    <label for="pickupdate">Zeitpunkt</label>
    <input type="datetime-local" name="pickupdate">
    <label for="guestid">Gast</label>
    <select name="guestid">
      <?php
      foreach (Guest::listGuestsForPickUp() as $guest) {
        if (empty($guest['date'])) {
          ?>
            <option value="<?= $guest['guestid'] ?>"><?= $guest['guestname'] ?></option>
          <?php
          continue;
        } else {
          ?>
            <option disabled="disabled"><?= $guest['guestname'] ?></option>
          <?php
        }
      }
      ?>
    </select>
    <input type="submit">
</form>

<!-- -->
<hr />
<a href="guest.php">Gäste</a>
<h3>Dropoffs</h3>
<table>
    <thead>
    <tr>
        <th>Gast-ID</th>
        <th>Gast Name</th>
        <th>Drop-Off Time</th>
        <th>Gebucht?</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach (Guest::listGuestsForDropOff() as $drop){
      if(empty($drop['date'])) continue;
      ?>
        <tr>
            <td><?= $drop['guestid'] ?></td>
            <td><?= $drop['guestname'] ?></td>
            <td><?= $drop['date'] ?></td>
            <td><?= empty($drop['tripid'])?'No':'Yes'; ?></td>
        </tr>
      <?php
    }
    ?>
    </tbody>
</table>

<form action="trip.php" method="post">
    <input type="hidden" name="task" value="adddropoff">
    <h3>Dropoff hinzufügen</h3>
    <label for="dropoffdate">Zeitpunkt</label>
    <input type="datetime-local" name="dropoffdate">
    <label for="guestid">Gast</label>
    <select name="guestid">
      <?php
      foreach (Guest::listGuestsForDropOff() as $guest) {
        if (empty($guest['date'])) {
          ?>
            <option value="<?= $guest['guestid'] ?>"><?= $guest['guestname'] ?></option>
          <?php
          continue;
        } else {
          ?>
            <option disabled="disabled"><?= $guest['guestname'] ?></option>
          <?php
        }
      }
      ?>
    </select>
    <input type="submit">
</form>











</body>
</html>
