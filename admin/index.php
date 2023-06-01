<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ./login");
    exit();
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Administraatori leht</title>
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
        <h1>Administraatori leht</h1>

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


<!-- Button -->
<button id="lisaButton" class="btn btn-success">LISA</button>

<!-- Modal -->
<div class="fade modal" id="addCompanyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal content goes here -->
            <div class="modal-header">
                <h5 class="modal-title">Add Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add company form inputs -->
                <form id="addCompanyForm" method="POST">
                    <div class="mb-3">
                        <label for="companyNames" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="companyNames" name="companyNames" required>
                    </div>
                    <div class="mb-3">
                        <label for="companyLocations" class="form-label">Company Location</label>
                        <input type="text" class="form-control" id="companyLocations" name="companyLocations" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>



        <!-- Ettevõtete tabel -->
        <table class="table">
            <thead>
                <tr>
                    <th><a href="?search=<?= $search ?>&sort=name&order=<?= ($sortColumn == 'name' && $sortOrder == 'ASC') ? 'desc' : 'asc' ?>">Nimi</a></th>
                    <th><a href="?search=<?= $search ?>&sort=location&order=<?= ($sortColumn == 'location' && $sortOrder == 'ASC') ? 'desc' : 'asc' ?>">Asukoht</a></th>
                    <th><a href="?search=<?= $search ?>&sort=avg_rating&order=<?= ($sortColumn == 'avg_rating' && $sortOrder == 'ASC') ? 'desc' : 'asc' ?>">Keskmine hinne</a></th>
                    <th><a href="?search=<?= $search ?>&sort=review_count&order=<?= ($sortColumn == 'review_count' && $sortOrder == 'ASC') ? 'desc' : 'asc' ?>">Hindajate arv</a></th>
                    <th>Tegevused</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['location'] ?></td>
                        <td><?= $row['avg_rating'] ?></td>
                        <td><?= $row['review_count'] ?></td>
                        <td>
                            <button type="button" class="btn btn-primary edit-company" data-id="<?= $row['id'] ?>" data-name="<?= $row['name'] ?>" data-location="<?= $row['location'] ?>">Muuda</button>
                            <button type="button" class="btn btn-danger delete-company" data-id="<?= $row['id'] ?>">Kustuta</button>
                        </td>
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

        <!-- Ettevõtte muutmise pop-up -->
        <div class="modal fade" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editCompanyForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCompanyModalLabel">Muuda ettevõtet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="companyId" id="companyId">
                            <div class="mb-3">
                                <label for="companyName" class="form-label">Nimi</label>
                                <input type="text" class="form-control" id="companyName" name="companyName" required>
                            </div>
                            <div class="mb-3">
                                <label for="companyLocation" class="form-label">Asukoht</label>
                                <input type="text" class="form-control" id="companyLocation" name="companyLocation" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sulge</button>
                            <button type="submit" class="btn btn-primary">Salvesta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ettevõtte kustutamise kinnitusaken -->
        <div class="modal fade" id="deleteCompanyModal" tabindex="-1" aria-labelledby="deleteCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCompanyModalLabel">Kustuta ettevõte</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="deleteCompanyId" id="deleteCompanyId">
                        Kas olete kindel, et soovite ettevõtte kustutada?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sulge</button>
                        <button type="button" class="btn btn-danger" id="deleteCompanyConfirm">Kustuta</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- Logout button -->
    <form action="logout.php" method="POST">
  <button type="submit" class="btn btn-danger">Logout</button>
</form>

<!-- Homepage link -->
<a href="index.php">Back to Homepage</a>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>

    <script>

$(document).ready(function() {
    // Show the "Add Company" modal when the "LISA" button is clicked
    $('#lisaButton').click(function() {
        $('#addCompanyModal').modal('show');
    });
});

$(document).ready(function() {
    $('#addCompanyForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        var companyName = $('#companyName').val();
        var companyLocation = $('#companyLocation').val();

        // AJAX call to add-company.php
        $.ajax({
            url: 'add-company.php',
            type: 'POST',
            data: {
                companyName: companyName,
                companyLocation: companyLocation
            },
            success: function(response) {
                // Handle the response from add-company.php
                // You can update the page or perform any other actions here

                // For example, you can show a success message

                // You can also reload the page or update the company list, etc.
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle any error that occurred during the AJAX request
                console.error(error);
            }
        });

        $('#addCompanyModal').modal('hide');
    });
});

$('.edit-company').click(function() {
    var companyId = $(this).data('id');
    var companyName = $(this).data('name');
    var companyLocation = $(this).data('location');

    $('#companyId').val(companyId);
    $('#companyName').val(companyName);
    $('#companyLocation').val(companyLocation);

    $('#editCompanyModal').modal('show');
});

$('#editCompanyForm').submit(function(event) {
    event.preventDefault(); // Prevent default form submission

    var companyId = $('#companyId').val();
    var companyName = $('#companyName').val();
    var companyLocation = $('#companyLocation').val();

    // AJAX call to edit-company.php
    $.ajax({
        url: 'edit-company.php',
        type: 'POST',
        data: {
            id: companyId,
            name: companyName,
            location: companyLocation
        },
        success: function(response) {
            // Handle the response from edit-company.php
            // You can update the page or perform any other actions here

            // For example, you can reload the page after successful edit
            location.reload();

            // You can also show a success message or perform any other necessary actions
            // based on the response from edit-company.php
        },
        error: function(xhr, status, error) {
            // Handle any error that occurred during the AJAX request
            console.error(error);
        }
    });

    $('#editCompanyModal').modal('hide');
});


        // Ettevõtte kustutamise pop-up-akna avamine
        $('.delete-company').click(function() {

            var companyId = $(this).data('id');
            console.log(companyId);
            $('#deleteCompanyId').val(companyId);
            $('#deleteCompanyModal').modal('show');
        });


        // Ettevõtte kustutamise kinnitus
        $('#deleteCompanyConfirm').click(function() {
            var companyId = $('#deleteCompanyId').val();

            // Send AJAX request to delete-company.php
            $.ajax({
                url: 'delete-company.php',
                method: 'POST',
                data: { id: companyId },
                success: function(response) {
                    // Handle the success response, such as refreshing the company list
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle the error response
                    console.log(xhr.responseText);
                }
            });

            $('#deleteCompanyModal').modal('hide');
});
    </script>
</body>
</html>
