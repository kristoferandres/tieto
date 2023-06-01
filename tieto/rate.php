<!DOCTYPE html>
<html>
<head>
    <title>Ettevõtte hinnang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Ettevõtte hinnang</h1>

        <?php
        // Andmebaasiga ühendamine
        $connection = mysqli_connect("localhost", "root", "", "kandres");

        // Ettevõtte ID hankimine
        $companyId = $_GET['company_id'];

        // Ettevõtte andmete hankimine
        $query = "SELECT * FROM ettevotted WHERE id = $companyId";
        $result = mysqli_query($connection, $query);
        $company = mysqli_fetch_assoc($result);

        // Ettevõtte hinnangute hankimine
        $query = "SELECT * FROM ratings WHERE company_id = $companyId";
        $result = mysqli_query($connection, $query);
        ?>

        <!-- Ettevõtte info -->
        <h2><?= $company['name'] ?></h2>
        <p><strong>Asukoht:</strong> <?= $company['location'] ?></p>
        <p><strong>Keskmine hinne:</strong> <?= $company['avg_rating'] ?></p>
        <p><strong>Hindajate arv:</strong> <?= $company['review_count'] ?></p>

        <!-- Hinnangute kuvamine -->
        <h3>Hinnangud:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Nimi</th>
                    <th>Kommentaar</th>
                    <th>Hinnang</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['comment'] ?></td>
                        <td><?= $row['rating'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Hinnangu andmise vorm -->
        <h3>Anna hinnang:</h3>
        <form method="POST" action="submit-rating.php">
            <input type="hidden" name="company_id" value="<?= $companyId ?>">
            <div class="mb-3">
                <label for="name">Nimi:</label>
                <input type="text" name="name" required>
            </div>
            <div class="mb-3">
                <label for="comment">Kommentaar:</label>
                <textarea name="comment" required></textarea>
            </div>
            <div class="mb-3">
                <label for="rating">Hinnang:</label>
                <select name="rating" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Salvesta hinnang</button>
            </div>
        </form>

        <!-- Tagasi avalehele -->
        <a href="index.php">Tagasi avalehele</a>
    </div>
</body>
</html>
