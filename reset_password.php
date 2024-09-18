<?php
$token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #cbd3da;
        }
        .reset-password-container {
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .reset-password-container h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .reset-password-container .form-group {
            margin-bottom: 15px;
        }
        .reset-password-container .btn {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <h1>Změna hesla (koukej.online)</h1>
        <form action="update_password.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="form-group">
                <label for="new-password">Nové heslo:</label>
                <input type="password" class="form-control" id="new-password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Potvrdit heslo:</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Změnit heslo</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
