<?php
require_once 'model/Guest.php';

if($_POST) {
  if ($_POST['method'] == "DELETE") {
    // DELETE driver
    if(empty($_POST['guestId'])) die("No GuestId given");
    Guest::deleteGuest($_POST['guestId']);
  } else {
    // New Driver
    if (empty($_POST['guestName'])) die("No guestName given!");
    Guest::addGuest($_POST['guestName']);
  }
}


?>

<html>
<head>
    <!--adding Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
<h4>
    <a href="index.html" class="badge badge-primary">Back to main page</a>
</h4>

<!-- -->
<hr />
<table class="table table-hover">
  <thead>
  <tr>
    <th>Guest ID</th>
    <th>Guest Name</th>
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

<!-- -->
<hr />
<form action="guest.php" method="post">
  <h3>Add Guest</h3>
  <label for="guestName">Guest Name</label>
  <input type="text" name="guestName">
  <input type="submit" >
</form>
<!-- -->


</body>
</html>

