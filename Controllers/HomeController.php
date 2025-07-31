<?php
include 'DatabaseController.php';
$conn = OpenCon();
session_start();
 
 
if (isset($_POST['username']) && isset($_POST['password'])) {

    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $username = validate($_POST['username']);
    $pass = validate($_POST['password']);
    // $passwzord = 'mwk@0090';
// $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

// echo $hashedPassword;
// exit;
// echo ($pass);exit;
    if (empty($username)) {
        header("Location: ../login.php?error=Email is required");
        exit();
    } elseif (empty($pass)) {
        header("Location: ../login.php?error=Password is required");
        exit();
    } else {
        // Use prepared statement for security
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Use password_verify to check hashed password
            // if (password_verify($pass, $row['password'])) {
            if ($pass == $row['password']) {

                // Load vehicle info
                $vehicle = "SELECT * FROM vehicles LIMIT 1";
                $vehicle_record = mysqli_query($conn, $vehicle);

                if (mysqli_num_rows($vehicle_record) > 0) {
                    $vehicle_row = mysqli_fetch_assoc($vehicle_record);
                    $_SESSION['current_vehicle_id'] = $vehicle_row['id'];
                    $_SESSION['current_vehicle_number'] = str_replace(' ', '-', $vehicle_row['number']);
                } else {
                    $_SESSION['current_vehicle_id'] = 'No vehicle added';
                    $_SESSION['current_vehicle_number'] = 'No vehicle added';
                }

                // Store user info in session
                $_SESSION['user_name'] = $row['email'];
                
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];

                header("Location: ../index.php");
                exit();

            } else {
                header("Location: ../login.php?error=Incorrect username or password");
                exit();
            }
        } else {
            header("Location: ../login.php?error=Incorrect username or password");
            exit();
        }
    }

} else {
    header("Location: ../login.php");
    exit();
}

// Logout logic
if (isset($_GET['process']) && $_GET['process'] == 'singOut') {
    session_start();
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
