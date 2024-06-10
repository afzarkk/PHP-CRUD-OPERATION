<?php
include 'conn.php';

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $languageArray = isset($_POST['language']) ? $_POST['language'] : [];
    $language = implode(',', $languageArray);

    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $id = $_POST['id'];

    $filename = $_FILES["imageuploads"]["name"];
    $tempname = $_FILES["imageuploads"]["tmp_name"];
    $randomNumber = uniqid(); // Generate a unique ID
    $extension = pathinfo($filename, PATHINFO_EXTENSION); // Get file extension
    $newFilename = $randomNumber . '.' . $extension;
    $folder = "images/" . $newFilename;




    if (!empty($filename)) {
        if (move_uploaded_file($tempname, $folder)) {
            // File upload successful
            $sql = "UPDATE `data` SET `Name`='$name', `Dob`='$dob', `Age`='$age', `Gender`='$gender', `Language`='$language', `Phone_no`='$phonenumber', `Address`='$address', `City`='$city' , `Image`='$folder' WHERE id='$id'";
        } else {
            // File upload failed
            echo "Failed to upload image.";
            exit;
        }
    } else {
        //  update without changing the image
        $sql = "UPDATE `data` SET `Name`='$name', `Dob`='$dob', `Age`='$age', `Gender`='$gender', `Language`='$language', `Phone_no`='$phonenumber', `Address`='$address', `City`='$city' WHERE id='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: view_data.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

if (isset($_GET['update'])) {
    $id = $_GET['update'];
    $sql = "SELECT * FROM `data` WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $uprow = mysqli_fetch_assoc($result);

    $name = $uprow['Name'];
    $dob = $uprow['Dob'];
    $age = $uprow['Age'];
    $gender = $uprow['Gender'];
    $language = $uprow['Language'];
    $phonenumber = $uprow['Phone_no'];
    $address = $uprow['Address'];
    $city = $uprow['City'];
    $imageupload = $uprow['Image'];

    $languages = explode(',', $language);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Form</title>
    <link rel="stylesheet" type="text/css" href="insert.css">
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

        <h3>Update Form</h3>

        <form method="post" action="update.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" pattern="[A-Za-z\s]+" required><br><br>

            <label for="dob">DOB: </label>
            <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" required><br><br>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo $age; ?>" readonly><br><br>

            <label for="gender">Gender:</label>
            <input type="radio" id="male" name="gender" value="Male" <?php if ($gender == 'Male') echo "checked"; ?> required> 
            <label for="male">Male</label>
            <input type="radio" id="female" name="gender" value="Female" <?php if ($gender == 'Female') echo "checked"; ?> required> 
            <label for="female">Female</label><br><br>

            <label for="language">Language:</label>
            <input type="checkbox" id="tamil" name="language[]" value="tamil" <?php if (in_array('tamil', $languages)) echo "checked"; ?>>
            <label for="tamil">Tamil</label>
            <input type="checkbox" id="english" name="language[]" value="english" <?php if (in_array('english', $languages)) echo "checked"; ?>>
            <label for="english">English</label>
            <input type="checkbox" id="hindi" name="language[]" value="hindi" <?php if (in_array('hindi', $languages)) echo "checked"; ?>>
            <label for="hindi">Hindi</label><br><br>

            <label for="phonenumber">Phone Number:</label>
            <input type="tel" id="phonenumber" name="phonenumber" value="<?php echo $phonenumber; ?>" oninput="this.value=this.value.replace(/[^0-9]/g,'')" maxlength="10" minlength="10"><br><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo $address; ?></textarea><br><br>

            <label for="city">City:</label>
            <select name="city">
                <option value="erode" <?php if ($city === 'erode') echo "selected"; ?>>Erode</option>
                <option value="trichy" <?php if ($city === 'trichy') echo "selected"; ?>>Trichy</option>
                <option value="tirunelveli" <?php if ($city === 'tirunelveli') echo "selected"; ?>>Tirunelveli</option>
            </select><br><br>

            <label for="imageuploads">Upload Your Image: </label>
            <input type="file" id="imageuploads" name="imageuploads"><br><br>

            <input type="submit" value="Update" name="update">
        </form>

        <a href="view_data.php">View Data</a>
    </div>
</body>

</html>
