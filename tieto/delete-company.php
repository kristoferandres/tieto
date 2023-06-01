<?php
// Andmebaasiga ühendamine
$connection = mysqli_connect("localhost", "root", "", "kandres");

// Ettevõtte kustutamine
$companyId = $_POST['id'];

// Delete the ratings associated with the company ID
$deleteRatingsQuery = "DELETE FROM ratings WHERE company_id = $companyId";
mysqli_query($connection, $deleteRatingsQuery);

// Delete the company
$deleteCompanyQuery = "DELETE FROM ettevotted WHERE id = $companyId";
mysqli_query($connection, $deleteCompanyQuery);

// Suunamine administraatori vaatesse
header("Location: admin.php");
exit();
?>
