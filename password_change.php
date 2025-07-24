<?php include('Master/head.php');?>

<body>

  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">

      <div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-header bg-dark text-white rounded-top-4 d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0 text-white text-center" id="tel-repeater">Update Password</h4>
        <a class="text-white" data-action="expand"><i class="ft-maximize"></i></a>
      </div>
      <div class="card-body pt-4">

        <form name="frm1" action="Controllers/UserController.php" method="POST" autocomplete="OFF">
          <div class="form-group col-12 mb-3">
            <input type="password" class="form-control" name="password" placeholder="Enter new password">
          </div>
          <div class="form-group col-12 mb-3">
            <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm new password">
          </div>
          <div class="d-flex justify-content-end">
            <button onclick="return val_a();" type="submit" name="btn_password"
              class="btn btn-dark active mt-2 px-4 rounded-pill shadow-sm">
              Save Changes
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>


    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include('Master/footer.php');?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <?php include('Master/scripts.php');?>

</body>

<script>
  function val_a() {
    if (frm1.password.value == "") {
      alert("Enter the Password.");
      frm1.password.focus();
      return false;
    }
    if ((frm1.password.value).length < 8) {
      alert("Password should be minimum 8 characters.");
      frm1.password.focus();
      return false;
    }

    if ((frm1.password.value).length > 20) {
      alert("Password should be maximum 20 characters.");
      frm1.password.focus();
      return false;
    }

    if (frm1.confirmpassword.value == "") {
      alert("Enter the Confirmation Password.");
      return false;
    }
    if (frm1.confirmpassword.value != frm1.password.value) {
      alert("Password confirmation does not match.");
      return false;
    }

    return true;
  }
</script>

</html>