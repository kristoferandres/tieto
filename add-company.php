<?php
// Andmebaasiga ühendamine
$connection = mysqli_connect("localhost", "root", "", "kandres");

// Kontrolli, kas saadeti POST päring
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Andmete hankimine
    $companyName = $_POST["companyName"];
    $companyLocation = $_POST["companyLocation"];

    // Andmete sisestamine andmebaasi
    $query = "INSERT INTO ettevotted (name, location) VALUES ('$companyName', '$companyLocation')";
    if (mysqli_query($connection, $query)) {
        // Kui sisestamine õnnestus
        echo "success";
    } else {
        // Kui sisestamine ebaõnnestus
        echo "error";
    }
} else {
    // Kui päring ei olnud POST meetodiga
    echo "Invalid request method";
}
?>