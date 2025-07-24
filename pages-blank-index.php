<?php include 'Master/head.php';?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php';?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php';?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>MWK - Transport Transport Users</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Index</li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
        <a class="btn btn-dark" href="pages-blank-create.php"><i class="bi bi-plus-lg"></i> Add new user
        </a>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <?php
          if (@$_GET['successMessage']) {
              ?>
                <div class="alert alert-success bg-success text-light alert-dismissible fade show" role="alert">
                  <?php echo $_GET['successMessage']; ?>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <?php
          }
        ?>

 
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Records</h5>
               
              <?php 
                $conn = OpenCon();
                $users = mysqli_query($conn, "SELECT * FROM users");
              ?>
              <!-- Table with stripped rows -->
              <table class="table datatable" id="example">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Image</th> 
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php  
                  $i = 1;
                  while($user = mysqli_fetch_array($users))
                  {
                ?>

                <tr>
                  <th scope="row"><?php echo $i++;?></th>
                  <td><?php echo $user['name'];?></td>
                  <td><?php echo $user['email'];?></td>
                  <td>
                  
                     <?php 
                        if($user['profile_photo_path'])
                        {
                          echo $user['profile_photo_path'];
                        }else
                        {
                    ?>
                    <img src="CommonImages/dummy.png" alt=""  width="100px">
                    <?php 

                        }
                    ?>  
                  </td> 
                  <td>
                    
                    <a href="Controllers/UserController.php?user_id=<?php echo $user['id'];?>&process=edit" type="button"
                        class="btn btn-sm fs-6"
                        title="Edit"><i class="bi bi-pencil-square"></i></a>
                    <a href="Controllers/UserController.php?user_id=<?php echo $user['id'];?>&process=delete" type="button"
                        class="btn btn-sm fs-6"
                        title="Delete"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
                   
                <?php }?>
                  
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

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

</body>

</html>