<?php
session_start(); // Start session

// Connect to database
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'user_authentication';
$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Process file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // File upload directory
    $targetDir = "uploads/";

    // File path in database
    $filePath = $targetDir . basename($_FILES["fileToUpload"]["name"]);

    // Upload file
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filePath)) {
        echo "File uploaded successfully";
        
        // Update file path in database
        $id = $_SESSION["id"]; // Assuming you have stored user's ID in session
        $sql = "UPDATE users SET file_path=? WHERE id=?";
        
        // Prepare statement
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "si", $filePath, $id);
            
            // Execute statement
            mysqli_stmt_execute($stmt);
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Redirect to home page
            header('Location: home.php');
            exit();
        } else {
            echo "Error updating file path in database";
        }
    } else {
        echo "Error uploading file";
    }
}

// Close connection
mysqli_close($conn);
?>