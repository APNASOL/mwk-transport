<?php
include 'DatabaseController.php';
$conn = OpenCon();

if (isset($_POST['create_user'])) {
    $user_name = mysqli_real_escape_string($conn,$_POST['user_name']);
    $user_email = mysqli_real_escape_string($conn,$_POST['user_email']);
    $user_password = sha1($_POST['user_password']);

    $queryFired = mysqli_query($conn, "INSERT INTO users (name,email,password) VALUES ('$user_name','$user_email','$userpassword')");

    if ($queryFired) {
        header('location:../pages-blank-index.php?successMessage=New record added successfully');
    } else {
        header('location:../pages-blank-create.php?errorMessage=New record adding error');
    }
}

if (@$_GET['process'] == 'delete') {

    $user_id = $_GET['user_id'];

    $user_object = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
    $user = mysqli_fetch_array($user_object);

    if ($user['profile_photo_path']) {
        $img = $user['profile_photo_path'];
        $imgpath = "../UserImages/" . $img;
        unlink($imgpath);
    }

    $queryFired = mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
    if ($queryFired) {
        header('location:../pages-blank-index.php?successMessage=User deleted successfully.');

    }

}
if (@$_GET['process'] == 'edit') {

    $user_id = $_GET['user_id'];
    if (isset($user_id)) {
        header('location:../pages-blank-update.php?user_id=' . $user_id);
    }
}
 
if (isset($_POST['btn_password'])) {

    
      session_start();
   
    $usermail = $_SESSION['user_name'];
 
    $password = trim($_POST['password']);
 

    // ✅ Securely hash the password using bcrypt (default)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Use prepared statements to avoid SQL injection
    $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $usermail);

    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("New Password updated successfully."); window.location.href="../index.php";</script>';
    } else {
        echo '<script>alert("Error updating password.");</script>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
 

if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $queryFired = mysqli_query($conn, "UPDATE users SET name = '$user_name' , email= '$user_email' WHERE id = $user_id");

    if ($queryFired) {
        header('location:../pages-blank-index.php?successMessage=Record updated successfully');
    } else {
        header('location:../pages-blank-update.php?errorMessage=Record updating error' . '&user_id=' . $user_id);
    }
}
