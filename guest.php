<?php
require_once 'Db.php';


function addGuest($firstName, $lastName){
  $db = Db::getDbObject();
  $statement = $db->prepare("INSERT INTO guest (firstname, lastname, hotel) VALUES (:firstName, :lastName, :hotel)");
  $statement->bindParam(':firstName',$firstName);
  $statement->bindParam(':lastName',$lastName);
  $statement->bindValue(':hotel',1);
  $statement->execute();

  $db = null;
  return listGuests();
}

function listGuests(){
  $db = Db::getDbObject();
  $query = 'SELECT * FROM guest';
  // $result = $db->query($query);
  // Save Result as array (because connection will be closed afterwards!)
  $resultArray = $db->query($query)->fetchAll();
  $db = null; // close connection
  //return $result->fetchAll();
  return $resultArray;
}

function deleteGuest($guestId){
  $db = Db::getDbObject();
  $statement = $db->prepare('DELETE FROM guest WHERE id = :driverId');
  $statement->bindParam(':driverId',$guestId, PDO::PARAM_INT);
  $statement->execute();
  $db = null;
  return listGuests();
}

if($_POST) {
  if ($_POST['method'] == "DELETE") {
    // DELETE driver
    if(empty($_POST['guestId'])) die("No GuestId given");
    deleteGuest($_POST['guestId']);
  } else {
    // New Driver
    if (empty($_POST['firstName'])) die("No firstName given!");
    if (empty($_POST['lastName'])) die("No lastName given!");
    addGuest($_POST['firstName'], $_POST['lastName']);
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
    <th>First Name</th>
    <th>Last Name</th>
    <th><i>Delete</i></th>
  </tr>
  </thead>
  <tbody>
  <?php
  foreach (listGuests() as $row){
    ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['firstname'] ?></td>
      <td><?= $row['lastname'] ?></td>
      <td>
        <form action="guest.php" method="post">
          <input type="hidden" name="method" value="DELETE">
          <input type="hidden" name="guestId" value="<?= $row['id'] ?>">
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

