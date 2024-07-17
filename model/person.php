<?php
include_once 'address.php';

class Person {
    private $id;
    private $name;
    private $age;
    private $address;

    public function __construct($id, $name, $age, $address) {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
        $this->address = $address;
    }

    public static function deleteFromDatabase($conn, $id) {
        $query = "DELETE FROM persons WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    // Method definitions

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAge() {
        return $this->age;
    }

    public function getAddress() {
        return $this->address;
    }

    public static function getAll($conn, $offset, $limit) {
        $query = "SELECT * FROM persons LIMIT ?, ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $persons = [];
        while ($row = $result->fetch_assoc()) {
            $postal_code = isset($row['postal_code']) ? $row['postal_code'] : '';
            $address = new Address($row['street'], $row['city'], $row['state'], $postal_code);
            $person = new Person($row['id'], $row['name'], $row['age'], $address);
            $persons[] = $person;
        }

        return $persons;
    }

    public function updateInDatabase($conn) {
        $query = "UPDATE persons SET name=?, age=?, street=?, city=?, state=?, postal_code=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sissssi", $this->name, $this->age, $this->address->getStreet(), $this->address->getCity(), $this->address->getState(), $this->address->getPostalCode(), $this->id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
