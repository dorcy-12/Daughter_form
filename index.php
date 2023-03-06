<?php
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input values
    $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'Adress', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'Phone', FILTER_SANITIZE_STRING);
    $iq = filter_input(INPUT_POST, 'IQ', FILTER_SANITIZE_NUMBER_INT);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $descriptions = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    $allData = implode("; ",$descriptions);
    $politics = filter_input(INPUT_POST, 'politics', FILTER_SANITIZE_STRING);
    $education = filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING);
    $essay = filter_input(INPUT_POST, 'essay', FILTER_SANITIZE_STRING);
    $references = filter_input(INPUT_POST, 'references', FILTER_SANITIZE_STRING);
    $file = rand(1000,1000)."-".$_FILES['file']['name'];
    $file_loc = $_FILES['file']['tmp_name'];
    $file_dir = "Uploads/"; //fixed directory path

    // Validate input values
    $errors = array();
    
    if (empty($name)) {
        $errors[] = "Please enter a valid name.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Please enter a valid 10-digit phone number.";
    }
    if (empty($iq) || $iq < 0 || $iq > 200) {
        $errors[] = "Please enter a valid IQ score between 0 and 200.";
    }
    if (empty($gender)) {
        $errors[] = "Please select a gender.";
    }
    
    if (empty($date) || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
        $errors[] = "Please enter a valid date in yyyy-mm-dd format.";
    }
    if (empty($essay)) {
        $errors[] = "Please enter an essay.";
    }
    if (!empty($file_loc)) {
        $allowed_exts = array("jpg", "jpeg", "png", "pdf"); //allowed file extensions
        $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_exts)) {
            $errors[] = "Please upload a valid file with one of the following extensions: " . implode(", ", $allowed_exts);
        }
    }

    if (!empty($errors)) {
        echo "<h3>Errors in database:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else {
        //Database connection
        $conn =  mysqli_connect("localhost","root","root","PHP_FORMS");

        if($conn -> connect_error){
            die('Connection Failed : '.$conn->connect_error);
            echo "<h2>Error in connecting</h2>";
        } else {
            //Database connection
            $conn =  mysqli_connect("localhost","root","root","PHP_FORMS");
            if($conn -> connect_error){
                die('Connection Failed : '.$conn->connect_error);
                echo "<h2>Error in second connection</h2>";
            } else {
                move_uploaded_file($file_loc, $file_dir.$file); // Upload the file to the server
                $sql = "INSERT INTO registration (name, address, email, phone, iq, gender, date, descriptions, politics, education, essay, referrals, file) VALUES ('$name', '$address', '$email', '$phone', '$iq', '$gender', '$date', '$allData', '$politics', '$education', '$essay', '$references', '$file')";
                if (mysqli_query($conn, $sql)) {
                    echo "<h2>Thank you for submitting your form!</h2>";
                } else {
                    echo $sql;
                    echo "<h2>Last step Error:</h2><p>" . mysqli_error($conn) . "</p>";
                }
                mysqli_close($conn);
            }
        }
    }
}
?>