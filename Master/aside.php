<?php
$activePage = basename($_SERVER['PHP_SELF'], ".php");
$current_vehicle_id = $_SESSION['current_vehicle_id'];
?>

<aside id="sidebar" class="sidebar bg-white shadow-sm rounded-3">

  <ul class="sidebar-nav list-unstyled" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'index' ? 'active' : 'text-dark'; ?>"
        href="index.php">
        <i class="bi bi-grid-fill"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <?php if ($current_vehicle_id != "No vehicle added"): ?>

    <!-- Daily Operation -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'dailyoperation' ? 'active' : 'text-dark'; ?>"
        href="dailyoperation.php">
        <i class="bi bi-calendar-range-fill"></i>
        <span>Daily Operation</span>
      </a>
    </li>

    <!-- Vehicle Details -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= in_array($activePage, ['vehicle-details', 'vehicle-expenses-transaction']) ? 'active' : 'text-dark'; ?>"
        href="Controllers/VehicleController.php?vehicle_id=<?= $current_vehicle_id ?>&process=details">
        <i class="bi bi-truck"></i>
        <span>Vehicle Details</span>
      </a>
    </li>

    <!-- Vehicle Income Statements -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'vehicle-income-statements' ? 'active' : 'text-dark'; ?>"
        href="Controllers/VehicleController.php?vehicle_id=<?= $current_vehicle_id ?>&process=vehicle-income-statement">
        <i class="bi bi-journal-text"></i>
        <span>Income Statements</span>
      </a>
    </li>

    <!-- Financial Statement -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'vehicle-finacial-statements' ? 'active' : 'text-dark'; ?>"
        href="Controllers/VehicleController.php?vehicle_id=<?= $current_vehicle_id ?>&process=vehicle-financial-statements">
        <i class="bi bi-layout-text-window-reverse"></i>
        <span>Financial Statement</span>
      </a>
    </li>

    <!-- Vehicle Repair -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'vehicle-repair' ? 'active' : 'text-dark'; ?>"
        href="vehicle-repair.php">
        <i class="bi bi-tools"></i>
        <span>Vehicle Repair</span>
      </a>
    </li>

    <!-- Pumps -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= in_array($activePage, ['pump-index', 'pump-details']) ? 'active' : 'text-dark'; ?>"
        href="pump-index.php">
        <i class="bi bi-file-post"></i>
        <span>Pumps</span>
      </a>
    </li>

    <!-- Fuels -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'fuel-index' ? 'active' : 'text-dark'; ?>"
        href="fuel-index.php">
        <i class="bi bi-droplet-fill"></i>
        <span>Fuels</span>
      </a>
    </li>

    <!-- Trips -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'trip-index' ? 'active' : 'text-dark'; ?>"
        href="trip-index.php">
        <i class="bi bi-minecart-loaded"></i>
        <span>Trips</span>
      </a>
    </li>

    <!-- Customers -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= in_array($activePage, ['customer-index', 'customer-details']) ? 'active' : 'text-dark'; ?>"
        href="customer-index.php">
        <i class="bi bi-people"></i>
        <span>Customers</span>
      </a>
    </li>

    <!-- Partners -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'partners-index' ? 'active' : 'text-dark'; ?>"
        href="partners-index.php">
        <i class="bi bi-cash-coin"></i>
        <span>Partners</span>
      </a>
    </li>

    <!-- Cash Book -->
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center gap-2 <?= $activePage === 'account-cashbook' ? 'active' : 'text-dark'; ?>"
        href="account-cashbook.php">
        <i class="bi bi-wallet2"></i>
        <span>Cash Book</span>
      </a>
    </li>

    <!-- Reports (Collapsible) -->
    <?php
$reportPages = ['vehicle-reports', 'customer-reports', 'pump-reports'];
$isReportActive = in_array($activePage, $reportPages);
?>

    <li class="nav-item ">
      <a class="nav-link d-flex align-items-center gap-2 <?= $isReportActive ? 'active' : 'collapsed'; ?>"
        data-bs-toggle="collapse" href="#reportMenu" role="button"
        aria-expanded="<?= $isReportActive ? 'true' : 'false'; ?>" aria-controls="reportMenu">
        <i class="bi bi-bar-chart-line <?= $isReportActive ? 'text-white' : ''; ?>"></i>
        <span class="<?= $isReportActive ? 'text-white fw-semibold' : ''; ?>">Reports</span>
        <i class="bi bi-chevron-down ms-auto <?= $isReportActive ? 'text-white' : ''; ?>"></i>
      </a>

      <div class="collapse <?= $isReportActive ? 'show' : ''; ?>" id="reportMenu">
        <ul class="list-unstyled ps-4">
          <li class="mt-1 ">
            <a href="vehicle-reports.php"
              class="nav-link <?= $activePage === 'vehicle-reports' ? 'active text-white bg-dark rounded' : 'text-dark'; ?>">
              Vehicle Report
            </a>
          </li>
          <li>
            <a href="customer-reports.php"
              class="nav-link <?= $activePage === 'customer-reports' ? 'active text-white bg-dark rounded' : 'text-dark'; ?>">
              Customer Report
            </a>
          </li>
          <li>
            <a href="pump-reports.php"
              class="nav-link <?= $activePage === 'pump-reports' ? 'active text-white bg-dark rounded' : 'text-dark'; ?>">
              Pump Report
            </a>
          </li>
        </ul>
      </div>
    </li>
   

    <?php endif; ?>

     <li>

    <img  class="mb-5" src="assets/img/logo.png" alt="" width="250px">
    </li>
  </ul>

  
  

</aside>

<!-- Sidebar Style -->
<style>
  #sidebar {
    min-height: 100vh !important;

  }

  .sidebar-nav .nav-link {
    padding: 0.5rem 1rem !important;
    border-radius: 8px !important;
    transition: all 0.2s ease-in-out !important;
  }

  .sidebar-nav .nav-link.active {
    background-color: black !important;
    color: white !important;
    font-weight: 600;
  }

  .sidebar-nav .nav-link:hover {
    background-color: black !important;
    color: white !important;
  }
  

  .sidebar-nav .collapse .nav-link.active {
    background-color: transparent;
    font-weight: 600 !important;
  }
.sidebar-nav  .bi {
    color: black !important;
  }
  .sidebar-nav .nav-link.active .bi {
    color: white !important;
  }
  
  .sidebar-nav .nav-link.collapsed {
    color: black;
    background: #fff;
}

</style>