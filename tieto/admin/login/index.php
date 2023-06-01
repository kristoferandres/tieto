<!DOCTYPE html>
<html>
<head>
    <title>Administraatori sisselogimine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Administraatori sisselogimine</h1>

        <?php
        
        
         //Sisselogimise kontroll
        session_start();
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            header("Location: ../");
            exit();
        }
        $username='';
        $password='';
            

            // Võrdle kasutajanime ja parooli
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];
            if ($username=='admin' && $password=='admin') {
                $_SESSION['admin'] = true;
                header("Location: ../");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Vale kasutajanimi või parool</div>";
            }
        }
        ?>

        <!-- Sisselogimise vorm -->
        <form method="POST">
            <div class="mb-3">
                <label for="username">Kasutajanimi:</label>
                <input type="text" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password">Parool:</label>
                <input type="password" name="password" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Logi sisse</button>
            </div>
        </form>
    </div>
</body>
</html>
