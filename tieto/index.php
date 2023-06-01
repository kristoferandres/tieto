<!DOCTYPE html>
<html>
<head>
    <title>Ettevõtete hinnang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <style>
        .company-link {
            cursor: pointer;
            text-decoration: underline;
        }
        .pagination li.page-item:not(.active) a.page-link {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ettevõtete hinnang</h1>

        <!-- Otsing -->
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Otsi ettevõtet...">
                <button type="submit" class="btn btn-primary">Otsi</button>
            </div>
        </form>

        <?php
        // Andmebaasiga ühendamine
        $connection = mysqli_connect("localhost", "root", "", "kandres");

        // Otsingusõna hankimine
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Andmete päring
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'name';
        $sortOrder = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

        $query = "SELECT * FROM ettevotted WHERE name LIKE '%$search%' ORDER BY $sortColumn $sortOrder, name";
        $result = mysqli_query($connection, $query);
        $totalResults = mysqli_num_rows($result);
        $resultsPerPage = 10;
        $totalPages = ceil($totalResults / $resultsPerPage);

        // Lehe numbrit hankimine
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $currentPage = max(1, min($currentPage, $totalPages));
        $offset = ($currentPage - 1) * $resultsPerPage;

        // Andmete hankimine vastavalt lehele
        $query = "SELECT * FROM ettevotted WHERE name LIKE '%$search%' ORDER BY $sortColumn $sortOrder, name LIMIT $offset, $resultsPerPage";
        $result = mysqli_query($connection, $query);
        ?>

        <!-- Ettevõtete tabel -->
        <table class="table">
            <thead>
                <tr>
                    <th><a href="?search=<?= $search ?>&sort=name&order=<?= ($sortColumn == 'name' && $sortOrder == 'ASC') ? 'desc' : 'asc' ?>">Nimi</a></th>
                    <th><a href="?search=<?= $search ?>&sort=location&order=<?= ($sortColumn == 'location' && $sortOrder == 'ASC') ? 'desc' : 'asc' ?>">Asukoht</a></th>
                    <th><a href="?search=<?= $search ?>&sort=avg_rating&order=<?= ($sortColumn == 'avg_rating' && $sortOrder == 'ASC') ? 'desc' : 'asc' ?>">Keskmine hinne</a></th>
                    <th><a href="?search=<?= $search ?>&sort=review_count&order=<?= ($sortColumn == 'review_count' && $sortOrder == 'ASC') ? 'desc' : 'asc' ?>">Hindajate arv</a></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><a class="company-link" href="rate.php?company_id=<?= $row['id'] ?>"><?= $row['name'] ?></a></td>
                        <td><?= $row['location'] ?></td>
                        <td><?= $row['avg_rating'] ?></td>
                        <td><?= $row['review_count'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Lehe navigatsioon -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?search=<?= $search ?>&page=<?= $currentPage - 1 ?>" aria-label="Eelmine">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($currentPage > 3): ?>
                        <li class="page-item">
                            <a class="page-link" href="?search=<?= $search ?>&page=1">1</a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="...">
                                <span aria-hidden="true">...</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $currentPage - 2); $i <= min($currentPage + 2, $totalPages); $i++): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="?search=<?= $search ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages - 2): ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="...">
                                <span aria-hidden="true">...</span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?search=<?= $search ?>&page=<?= $totalPages ?>"><?= $totalPages ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?search=<?= $search ?>&page=<?= $currentPage + 1 ?>" aria-label="Järgmine">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <script>
        // Ettevõtte nime peale klõpsamine
        const companyLinks = document.querySelectorAll('.company-link');
        companyLinks.forEach(link => {
            link.addEventListener('click', event => {
                event.preventDefault();
                const companyUrl = link.getAttribute('href');
                window.location.href = companyUrl;
            });
        });
    </script>
</body>
</html>
