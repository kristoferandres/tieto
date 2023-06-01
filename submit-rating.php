<?php
// Andmebaasiga ühendamine
$connection = mysqli_connect("localhost", "root", "", "kandres");

// Andmete hankimine vormist
$companyId = $_POST['company_id'];
$name = $_POST['name'];
$comment = $_POST['comment'];
$rating = $_POST['rating'];

// Hinnangu lisamine andmebaasi
$query = "INSERT INTO ratings (name, company_id, comment, rating) VALUES ('$name', '$companyId', '$comment', '$rating')";
mysqli_query($connection, $query);

// Suunamine tagasi ettevõtte lehele
header("Location: rate.php?company_id=$companyId");
exit();
?>
