<?php
include_once './model/database.php';
include_once './model/person.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];

    $address = new Address($street, $city, $state, $postalCode);
    $person = new Person(null, $name, $age, $address);  // Add null for the ID if it's auto-incremented

    // $person->saveToDatabase($conn);

    echo "Person has been added to the database.";
}

$conn->close();