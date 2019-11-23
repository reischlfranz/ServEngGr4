<?php
require_once 'model/Car.php';

if ($_POST) {
  if ($_POST['method'] == "DELETE") {
    // DELETE driver
    if (empty($_POST['carId'])) die("No CarId given");
    Car::deleteCar($_POST['carId']);
  } else {
    // New Driver
    if (empty($_POST['carName'])) die("No carName given!");
    if (empty($_POST['seats'])) die("No seats given!");
    Car::addCar($_POST['carName'], $_POST['seats']);
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
        <th>Car ID</th>
        <th>Car Name</th>
        <th>Number Seats</th>
        <th><i>Delete</i></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach (Car::listCars() as $row) {
      ?>
        <tr>
            <td><?= $row['carid'] ?></td>
            <td><?= $row['carname'] ?></td>
            <td><?= $row['carpassengers'] ?></td>
            <td>
                <form action="car.php" method="post">
                    <input type="hidden" name="method" value="DELETE">
                    <input type="hidden" name="carId" value="<?= $row['carid'] ?>">
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
<hr/>
<form action="car.php" method="post">
    <h3>Add Driver</h3>
    <label for="carName">Name of Car</label>
    <input type="text" name="carName">
    <label for="seats">Number of Seats</label>
    <input type="number" name="seats">
    <input type="submit">
</form>
<!-- -->


</body>
</html>

