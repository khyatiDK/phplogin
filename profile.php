<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'user_authentication';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Retrieve user data
$stmt = $con->prepare('SELECT password, file_path FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $file_path);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profile Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1></h1>
        <a href="fileupload.html"><i class="fas fa-sign-out-alt"></i>File Upload</a>
        <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content">
    <h2>Profile Page</h2>
    <div>
        <p>Your account details are below:</p>
        <table>
            <tr>
                <td>Username:</td>
                <td><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><?=htmlspecialchars($password, ENT_QUOTES)?></td>
            </tr>
            <tr>
                <td>File:</td>
                <td>
                    <?php if (!empty($file_path)): ?>
                        <img src="<?=htmlspecialchars($file_path, ENT_QUOTES)?>" width="50%" alt="Uploaded File">
                    <?php else: ?>
                        <p>No file uploaded yet.</p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>