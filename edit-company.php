<!DOCTYPE html>
<html>
<head>
    <title>Ettevõtte muutmine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <?php
        // Andmebaasiga ühendamine
        $connection = mysqli_connect("localhost", "root", "", "kandres");

        // Ettevõtte andmete hankimine
        $companyId = $_POST['id'];
        $query = "SELECT * FROM ettevotted WHERE id = $companyId";
        $result = mysqli_query($connection, $query);
        $company = mysqli_fetch_assoc($result);


            $name = $_POST['name'];
            $location = $_POST['location'];

            // Ettevõtte andmete uuendamine andmebaasis
            $query = "UPDATE ettevotted SET name = '$name', location = '$location' WHERE id = $companyId";
            mysqli_query($connection, $query);

            // Suunamine administraatori vaatesse
            header("Location: admin.php");
            exit();

        ?>
        <h1>Muuda ettevõtet:</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="name">Nimi:</label>
                <input type="text" name="name" value="<?= $company['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="location">Asukoht:</label>
                <input type="text" name="location" value="<?= $company['location'] ?>" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary" name="update_company">Uuenda ettevõte</button>
            </div>
        </form>
    </div>
</body>
</html>
