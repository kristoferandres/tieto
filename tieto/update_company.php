<?php
// Andmebaasiga ühendamine
$connection = mysqli_connect("localhost", "root", "", "kandres");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $avgRating = $_POST['avg_rating'];
    $reviewCount = $_POST['review_count'];

    $query = "UPDATE ettevotted SET name='$name', location='$location', avg_rating='$avgRating', review_count='$reviewCount' WHERE id='$id'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
