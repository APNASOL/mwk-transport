<?php

// session_start();
$current_vehicle_id = $_SESSION['current_vehicle_id'];
$current_vehicle_number = $_SESSION['current_vehicle_number'];
if (!isset($_SESSION['user_name']) && !isset($_SESSION['name']) && !isset($_SESSION['id'])) {
    echo "inside";
// echo $_SESSION['user_name'];
    header("Location:login.php?error=Please login first!");

    exit();
}

?>
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo  ">
            
            <span class="d-none d-lg-block text-black text-center">M W K - Transport </span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->



    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <?php
$conn = OpenCon();
$vehicles = mysqli_query($conn, "SELECT * FROM vehicles");
$customers = mysqli_query($conn, "SELECT * FROM customers");
?>

            <div class="col">

                <select class="form-select" id="vehicle_dropdown" name="vehicle_id" aria-label="vehicles">
                    <?php
while ($vehicle = mysqli_fetch_array($vehicles)) {?>
                    <?php if ($vehicle['id'] == $_SESSION['current_vehicle_id']) {?>
                    <option selected value="<?php echo $vehicle['id']; ?>">
                        <?php echo $vehicle['number']; ?>
                    </option>
                    <?php } else {?>

                    <option value="<?php echo $vehicle['id']; ?>">
                        <?php echo $vehicle['number']; ?>
                    </option>

                    <?php }}?>
                    <option hidden>Please vehicle from the list</option>
                </select>


            </div>


            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
                    <i class="bi bi-person-check"></i>
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION['name'];?></span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php echo $_SESSION['name'];?></h6>
                        <span>Software Manager</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="password_change.php">
                            <i class="bi bi-person"></i>
                            <span>Change Password</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="vehicle-index.php">
                            <i class="bi bi-plus-lg"></i>
                            <span>All Vehicles</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="all-customer-index.php">
                            <i class="bi bi-people"></i>
                            <span>All Customer</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="cashflow-index.php">
                            <i class="bi bi-cash-coin"></i>
                            <span>Cash Flow</span>
                        </a>
                    </li>

                    <li>
                        <a href="Controllers/HomeController.php?process=singOut"
                            class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

<script>
    // Listen for change event on dropdown menu
    $(document).ready(function () {
        $('#vehicle_dropdown').on('change', function () {
            // Send AJAX request to PHP script
            $.ajax({
                type: 'POST',
                url: 'Controllers/SessionController.php',
                data: {
                    selected_option: $(this).val()
                },
                success: function (data) {
                    window.location.href =
                        "index.php?successMessage=Vehicle Changed now selected vehicle is ";
                    // location.reload();
                }
            });

        });
    });
</script>