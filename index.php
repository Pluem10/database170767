<?php
include_once './model/database.php';
include_once './model/person.php'; 

$database = new Database();
$conn = $database->getConnection();

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$persons = Person::getAll($conn, $offset, $limit);

$total_persons = $conn->query("SELECT COUNT(*) AS count FROM persons")->fetch_assoc()['count'];
$total_pages = ceil($total_persons / $limit);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Person List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Person List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Street</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Postal Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($persons as $person): ?>
                    <tr>
                        <td><?php echo $person->getId(); ?></td>
                        <td><?php echo $person->getName(); ?></td>
                        <td><?php echo $person->getAge(); ?></td>
                        <td><?php echo $person->getAddress()->getStreet(); ?></td>
                        <td><?php echo $person->getAddress()->getCity(); ?></td>
                        <td><?php echo $person->getAddress()->getState(); ?></td>
                        <td><?php echo $person->getAddress()->getPostalCode(); ?></td>
                        <td>
                            <a href="update.php?id=<?php echo $person->getId(); ?>" class="btn btn-warning btn-sm">Update</a>
                            <a href="delete.php?id=<?php echo $person->getId(); ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>
</html>
