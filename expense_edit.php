<?php include 'Master/head.php';
$transaction_id = $_GET['transaction_id'];
 
?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Vehicles Expenses update</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Expenses transactions update</li>
                    <li class="breadcrumb-item active">Index</li>
                </ol>
            </nav>

        </div><!-- End Page Title -->

        <section class="section">
            <?php
if (@$_GET['successMessage']) {
    ?>
            <div class="alert alert-success bg-success text-light alert-dismissible fade show" role="alert">
                <?php echo $_GET['successMessage']; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>

            <?php
}
?>
            <?php
$common_expenses = mysqli_query($conn, "SELECT * FROM vehicle_expenses_details where expense_id = '$transaction_id'");

    ?>
            <div class="card">
                <div class="card-body">
                 
                    <!-- Floating Labels Form -->
                    <div class="p-5">
                        <form class="row g-3 mt-2" action="Controllers/VehicleController.php" method="post" onsubmit="handleSubmit(event, this)">
                             <input type="hidden" value="<?php echo $transaction_id;?>" name="expense_id">
                            <table>
                                <thead>
                                    <tr>
                                        <td>Expense</td>
                                        <td>Amount</td>
                                        <!-- <td>Action</td> -->

                                    </tr>
                                </thead>
                                <?php while($row = mysqli_fetch_array($common_expenses)){?>
                                <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id; ?>">

                    </div>
                    <div class="row">
                        <tbody>
                            <tr>
                                <td>

                                    <input type="hidden" value="<?php echo $row['id'];?>" name="ids[]">
                                    <input type="text" class="form-control" id="expense_names" name="expense_names[]"
                                         value="<?php echo $row['expense_name'];?>"
                                        placeholder="Expense expense name">

                                </td>
                                <td>


                                    <input type="text" required class="form-control" id="amount" name="amounts[]"
                                        value="<?php echo $row['balance'];?>" placeholder="Amount">

                                </td>
                                <!-- <td>


                                    <a href="delete_expense.php?id=<?php// echo $row['id'];?>"> <i
                                            class="bi text-danger bi-trash"></i> </a>

                                </td> -->
                                <?php }?>
                            </tr>
                        </tbody>

                    </div>

                    </table>
                    <hr>
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                        
                      
                           <input  type="hidden" name="vehicle_expense_transaction_update" value="Update">
                           <button type="submit" class="btn btn-dark btn-block form-control">Delete</button> 
                       
                       
                    </div>
                  
                    </form>
                    
                    <form class="row g-3" action="Controllers/VehicleController.php" method="post" onsubmit="handleSubmit(event, this)">
                    
                        <input type="hidden" value="<?php echo $transaction_id;?>" name="expense_id">
                        <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id; ?>">
                        
                     
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                          <input  type="hidden" name="vehicle_expense_transaction_delete" value="Delete">
                          <button type="submit" class="btn btn-danger btn-block form-control" onclick="return confirmDelete()">Delete</button> 
                    </div>
                            
                </form>

                </div>
                <hr>
                <!-- Floating Labels Form -->


            </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include 'Master/footer.php';?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <?php include 'Master/scripts.php';?>
    <!-- Scripts -->

    <script>
    
    function handleSubmit(event, form) {
    //event.preventDefault(); // Prevent the form from submitting immediately

    var button = form.querySelector("button[type='submit']");
    button.disabled = true;
    button.innerText = 'Loading...'; // Optional: Change button text to indicate loading

    // Simulate an async operation (e.g., an API call or some processing)
    
}
    
       function confirmDelete() {
        return confirm("Are you sure you want to delete this record?");
    }
    </script>

</body>

</html>