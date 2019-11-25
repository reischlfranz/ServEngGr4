<?php
require_once 'model/Guest.php';

if(isset($_POST['method'])) {
  if ($_POST['method'] == "DELETE") {
    // DELETE Guest
    if (empty($_POST['guestId'])) die("No GuestId given");
    Guest::deleteGuest($_POST['guestId']);
  }
  if($_POST['method'] == 'addguest'){
    // New Guest
    if (empty($_POST['guestName'])) die("No guestName given!");
    Guest::addGuest($_POST['guestName']);
  }
  if ($_POST['method'] == 'adddropoff') {
    // add a new dropoff
    if (empty($_POST['dropoffdate'])) die('No Dropoff date given');
    if (empty($_POST['dropoffguestselect'])) die('No guest selected');
    Guest::addDropOff($_POST['dropoffguestselect'], $_POST['dropoffdate']);
  }
  if ($_POST['method'] == 'addpickup') {
    // add a new dropoff
    if (empty($_POST['pickupdate'])) die('No Pickup date given');
    if (empty($_POST['pickupguestselect'])) die('No guest selected');
    Guest::addPickUp($_POST['pickupguestselect'], $_POST['pickupdate']);
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
    <form action="guest.php" method="post">
        <input type="hidden" name="method" value="addguest">
        <h3>Add Guest</h3>
        <label for="guestName">Guest Name</label>
        <input type="text" name="guestName">
        <input type="submit" >
    </form>

    <!-- -->
    <hr />
    <form action="guest.php" method="post">
        <input type="hidden" name="method" value="addpickup">
        <h3>Add pickup</h3>
        <label for="direction">Richtung</label>
        <input type="text" name="direction" disabled="disabled" value="Airport->Hotel">

        <label for="pickupguestselect">Guest</label>
        <select name="pickupguestselect">
          <?php
          foreach (Guest::listGuestsForPickUp() as $guest) {
            if(!empty($guest['date'])) continue;
            ?>
              <option value="<?= $guest['guestid'] ?>"><?= $guest['guestname'] ?></option>
            <?php
          }
          ?>
        </select>
        <label for="pickupdate">Zeit</label>
        <input type="datetime-local" name="pickupdate">
        <input type="submit" >
    </form>    <!-- -->
    <hr />
    <form action="guest.php" method="post">
        <input type="hidden" name="method" value="adddropoff">
        <h3>Add dropoff</h3>
        <label for="direction">Richtung</label>
        <input type="text" name="direction" disabled="disabled" value="Hotel->Airport">

        <label for="dropoffguestselect">Guest</label>
        <select name="dropoffguestselect">
          <?php
          foreach (Guest::listGuestsForDropOff() as $guest) {
            if(!empty($guest['date'])) continue;
            ?>
              <option value="<?= $guest['guestid'] ?>"><?= $guest['guestname'] ?></option>
            <?php
          }
          ?>
        </select>
        <label for="dropoffdate">Zeit</label>
        <input type="datetime-local" name="dropoffdate">
        <input type="submit" >
    </form>

    <!-- -->
<!-- -->
<hr />
<table class="table table-hover">
  <thead>
  <tr>
    <th>Guest ID</th>
    <th>Guest Name</th>
    <th>Pickup</th>
    <th>Dropoff</th>
    <th><i>Delete</i></th>
  </tr>
  </thead>
  <tbody>
  <?php
  foreach (Guest::listGuests() as $row){
    ?>
    <tr>
      <td><?= $row['guestid'] ?></td>
      <td><?= $row['guestname'] ?></td>
      <td><?= empty($row['pickupdate']) ? "N/A" : $row['pickupdate'] ?></td>
      <td><?= empty($row['dropoffdate']) ? "N/A" : $row['dropoffdate'] ?></td>
      <td>
        <form action="guest.php" method="post">
          <input type="hidden" name="method" value="DELETE">
          <input type="hidden" name="guestId" value="<?= $row['guestid'] ?>">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </td>
    </tr>
    <?php
  }
  ?>
  </tbody>
</table>

</div>

</body>
</html>

