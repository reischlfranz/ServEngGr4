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
<body>

<a href="index.html">Back to main page</a>

<!-- -->
<hr />
<table>
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
<form action="guest.php" method="post">
  <h3>Add Guest</h3>
  <label for="guestName">Guest Name</label>
  <input type="text" name="guestName">
  <input type="submit">
</form>
<!-- -->


</body>
</html>

