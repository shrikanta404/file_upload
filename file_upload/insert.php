<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    // Image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    
    // File upload path
    $targetDir = "uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $tergetFilepath = $targetDir . $fileName;


    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO output (category, name, comment, image_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $category, $name, $comment, $target_file);

            if ($stmt->execute()) {
                echo "Data inserted successfully!";
            } else {
                echo "Error inserting data: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    // File upload
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $fileTargetDir = "upload/";
        $fileFileName = basename($_FILES["file"]["name"]);
        $fileTargetFilePath = $fileTargetDir . $fileFileName;

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $fileTargetFilePath)) {
            $stmt = $conn->prepare("INSERT INTO output (file_name , file_path) VALUES (?, ?)");
            $stmt->bind_param("ss", $fileFileName, $fileTargetFilePath);

            if ($stmt->execute()) {
                echo "File uploaded and data inserted successfully.";
            } else {
                echo "Error inserting file data: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or there was an upload error.";
    }

}

$conn->close();
?>
