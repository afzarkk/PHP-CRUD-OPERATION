<?php
include 'conn.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $languageArray = isset($_POST['language']) ? $_POST['language'] : [];
    $language = implode(',', $languageArray);

    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $city = $_POST['city'];

    $filename = $_FILES["imageupload"]["name"];
    $tempname = $_FILES["imageupload"]["tmp_name"];
     $randomNumber = uniqid(); // Generate a unique ID
    $extension = pathinfo($filename, PATHINFO_EXTENSION); // Get file extension
    $newFilename = $randomNumber . '.' . $extension;
    $folder = "images/" . $newFilename;

    // $dobFormatted = date("d/m/Y", strtotime($dob));

    

    // Check if the image already exists in the database
    $imagevalidation = $conn->query("SELECT * FROM data WHERE Image = '$folder'");
    $imageexist = mysqli_fetch_assoc($imagevalidation);

    $phonevalidation = $conn->query("SELECT * FROM data WHERE Phone_no = '$phonenumber'");
    $numexist = mysqli_fetch_assoc($phonevalidation);

    $namevalidation = $conn->query("SELECT * FROM data WHERE Name = '$name'");
    $nameexist = mysqli_fetch_assoc($namevalidation);

    if ($imageexist) {
        echo 'Image already exists';
    } elseif ($numexist) {
        echo 'Phone Number already exists';
    } elseif ($nameexist) {
        echo 'Name already exists';
    } else {
        // Move the uploaded image to the designated folder
        if (move_uploaded_file($tempname, $folder)) {
            $sql = "INSERT INTO `data`(`Name`, `Dob`, `Age`, `Gender`, `Language`, `Phone_no`, `Address`, `City`, `Image`) VALUES ('$name', '$dob', '$age', '$gender', '$language', '$phonenumber', '$address', '$city', '$folder');";
            mysqli_query($conn, $sql);
            header("Location: view_data.php");
        } else {
            echo 'Failed to upload image';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Form Validation</title>
    <!-- <link rel="stylesheet" href="insert.css"> -->
    <link rel="stylesheet" href="insert.css">

    <script>
        function calculateAge(dob) {
            var dobDate = new Date(dob);
            var diffMs = Date.now() - dobDate.getTime();
            var ageDt = new Date(diffMs);
            return Math.abs(ageDt.getUTCFullYear() - 1970);
        }

        document.addEventListener("DOMContentLoaded", function() {
            var dobInput = document.getElementById("dob");
            var ageInput = document.getElementById("age");

            dobInput.addEventListener("change", function() {
                var dob = dobInput.value;
                if (dob) {
                    var age = calculateAge(dob);
                    ageInput.value = age;
                }
            });
            var nameInput = document.getElementById("name");
            nameInput.addEventListener("input", function() {
                nameInput.value = nameInput.value.replace(/[^a-zA-Z\s]/g, '');
            });
        });
    </script>

</head>

<body>
    <div>
        <h1>Form Validation</h1>
        <form method="post" action="insert.php" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="enter your name" pattern="[A-Za-z\s]+" required><br><br>

            <label for="dob">DOB: </label>
            <input type="date" id="dob" name="dob" oninput="calculateAge(dob)" required><br><br>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" placeholder="enter your age" readonly><br><br>

            <label for="gender">Gender:</label>
            <input type="radio" id="male" name="gender" value="Male" required> <label for="male">Male</label>
            <input type="radio" id="female" name="gender" value="Female" required> <label for="female">Female</label><br><br>

            <label for="language">Language:</label>
            <input type="checkbox" id="tamil" name="language[]" value="tamil">
            <label for="tamil">Tamil</label>
            <input type="checkbox" id="english" name="language[]" value="english">
            <label for="english">English</label>
            <input type="checkbox" id="hindi" name="language[]" value="hindi">
            <label for="hindi">Hindi</label><br><br>

            <label for="phonenumber">Phone Number:</label>
            <input type="tel" id="phonenumber" name="phonenumber" placeholder="enter your Phone no" oninput="this.value=this.value.replace(/[^0-9]/,'')" maxlength="10" minlength="10"><br><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address" placeholder="enter your address" required></textarea><br><br>

            <label for="city">City:</label>
            <select name="city">
                <option value="erode">Erode</option>
                <option value="trichy">Trichy</option>
                <option value="tirunelveli">Tirunelveli</option>
            </select><br><br>

            <label for="imageupload">Upload Your Image: </label>
            <input type="file" id="imageupload" name="imageupload" required><br><br>

            <input type="submit" value="Submit" name="submit">
            <input type="reset" value="Reset">
        </form>
        <a href="view_data.php">View Data</a>
    </div>
</body>

</html>
