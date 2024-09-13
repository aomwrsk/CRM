<?php include('header.php');
$name = htmlspecialchars($name);
$staff = htmlspecialchars($staff);
$level = htmlspecialchars($level);
if($staff === 0 || $level <= 1){
  echo '<script>alert("Can not enter this site");window.location="/CRM/pages-login.html";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Forms Appoint</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/Logo_En-Tech_1.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <script>
    // Function to set the current date in YYYY-MM-DD format
    function setCurrentDate() {
        const inputDate = document.getElementById('inputDate');
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        const day = String(today.getDate()).padStart(2, '0');
        inputDate.value = `${day}/${month}/${year}`;
    }

    // Set the current date on page load
    window.onload = setCurrentDate;
</script>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/Logo_En-Tech_1.png" alt="">
        <span class="d-none d-lg-block">En-technology</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <!--div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div--><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <!--li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">999+</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul>

        </li--><!-- End Notification Nav -->

        <!--li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul>

        </li--><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/person-circle.svg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"> <?php echo $name; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
             <!--li class="dropdown-header">
              <h6>Kevin Anderson</h6>
              <span>Web Designer</span>
            </li-->
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="/CRM/log_out.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="components-alerts.html">
              <i class="bi bi-circle"></i><span>Alerts</span>
            </a>
          </li>
          <li>
            <a href="components-accordion.html">
              <i class="bi bi-circle"></i><span>Accordion</span>
            </a>
          </li>
          <li>
            <a href="components-badges.html">
              <i class="bi bi-circle"></i><span>Badges</span>
            </a>
          </li>
          <li>
            <a href="components-breadcrumbs.html">
              <i class="bi bi-circle"></i><span>Breadcrumbs</span>
            </a>
          </li>
          <li>
            <a href="components-buttons.html">
              <i class="bi bi-circle"></i><span>Buttons</span>
            </a>
          </li>
          <li>
            <a href="components-cards.html">
              <i class="bi bi-circle"></i><span>Cards</span>
            </a>
          </li>
          <li>
            <a href="components-carousel.html">
              <i class="bi bi-circle"></i><span>Carousel</span>
            </a>
          </li>
          <li>
            <a href="components-list-group.html">
              <i class="bi bi-circle"></i><span>List group</span>
            </a>
          </li>
          <li>
            <a href="components-modal.html">
              <i class="bi bi-circle"></i><span>Modal</span>
            </a>
          </li>
          <li>
            <a href="components-tabs.html">
              <i class="bi bi-circle"></i><span>Tabs</span>
            </a>
          </li>
          <li>
            <a href="components-pagination.html">
              <i class="bi bi-circle"></i><span>Pagination</span>
            </a>
          </li>
          <li>
            <a href="components-progress.html">
              <i class="bi bi-circle"></i><span>Progress</span>
            </a>
          </li>
          <li>
            <a href="components-spinners.html">
              <i class="bi bi-circle"></i><span>Spinners</span>
            </a>
          </li>
          <li>
            <a href="components-tooltips.html">
              <i class="bi bi-circle"></i><span>Tooltips</span>
            </a>
          </li>
        </ul>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link " data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
          <li>
            <a href="forms-elements.html">
              <i class="bi bi-circle"></i><span>Form Elements</span>
            </a>
          </li>
          <li>
          <a href="forms-appoint.php">
              <i class="bi bi-circle"></i><span>บันทึกข้อมูลลูกค้า</span>
            </a>
          </li>
          <li>
            <a href="forms-order.php" class="active">
              <i class="bi bi-circle"></i><span>บันทึก Order</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form Validation</span>
            </a>
          </li>
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>General Tables</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="charts-chartjs.html">
              <i class="bi bi-circle"></i><span>Chart.js</span>
            </a>
          </li>
          <li>
            <a href="charts-apexcharts.html">
              <i class="bi bi-circle"></i><span>ApexCharts</span>
            </a>
          </li>
          <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>ECharts</span>
            </a>
          </li>
        </ul>
      </li><!-- End Charts Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gem"></i><span>Icons</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="icons-bootstrap.html">
              <i class="bi bi-circle"></i><span>Bootstrap Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-remix.html">
              <i class="bi bi-circle"></i><span>Remix Icons</span>
            </a>
          </li>
          <li>
            <a href="icons-boxicons.html">
              <i class="bi bi-circle"></i><span>Boxicons</span>
            </a>
          </li>
        </ul>
      </li><!-- End Icons Nav -->

      <li class="nav-heading">Pages</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="users-profile.html">
    <i class="bi bi-person"></i>
    <span>Profile</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="permission.php">
    <i class="bi bi-question-circle"></i>
    <span>Permission</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="pages-contact.html">
    <i class="bi bi-envelope"></i>
    <span>Contact</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="pages-register.html">
    <i class="bi bi-card-list"></i>
    <span>Register</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="pages-login.html">
    <i class="bi bi-box-arrow-in-right"></i>
    <span>Login</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="pages-error-404.html">
    <i class="bi bi-dash-circle"></i>
    <span>Error 404</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link collapsed" href="pages-blank.html">
    <i class="bi bi-calendar-day"></i>
    <span>Blank</span>
  </a>
</li>

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>บันทึก Order</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Forms</li>
          <li class="breadcrumb-item active">บันทึก Order</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <!--div class="col-lg-6"-->

          <!--div class="card">
            <div class="card-body">
              <h5 class="card-title">Horizontal Form</h5>

              <form>
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Your Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputText">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword">
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Radios</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked>
                      <label class="form-check-label" for="gridRadios1">
                        First radio
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                      <label class="form-check-label" for="gridRadios2">
                        Second radio
                      </label>
                    </div>
                    <div class="form-check disabled">
                      <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="option3" disabled>
                      <label class="form-check-label" for="gridRadios3">
                        Third disabled radio
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <div class="col-sm-10 offset-sm-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="gridCheck1">
                      <label class="form-check-label" for="gridCheck1">
                        Example checkbox
                      </label>
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form>

            </div>
          </div-->

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">บันทึก Order</h5>

              <!-- Multi Columns Form -->
              <form action="create-order.php" method="post" enctype="multipart/form-data" class="row g-3">
              <div class="col-md-2">
                  <label for="inputQt_No" class="form-label">QT No.</label>
                  <input type="text" class="form-control" id="inputQt_No" name="inputQt_No" readonly>
                </div>
              <div class="col-md-2">
                  <label for="inputIs_No" class="form-label" data-bs-toggle="modal" data-bs-target="#ExtralargeModal">Issue No.</label>
                  <input type="text" class="form-control" id="inputIs_No" name="inputIs_No" readonly>
                  <input type="hidden" class="form-control" id="Is_No" name="inputIs_No" readonly>
                </div>
                                <!-- Extra Large Modal -->
            
              <div class="modal fade" id="ExtralargeModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">เลือกรายการแนบท้าย</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <table  id="tableOW"class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">OW No.</th>
                        <th scope="col">OW Date</th>
                        <th scope="col">QT No.</th>
                        <th scope="col">รหัสลูกค้า</th>
                        <th scope="col">ชื่อลูกค้า</th>
                        <th scope="col">เลือก</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                      </tr>
                    </tbody>
                  </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <button type="button" class="btn btn-primary" id="confirmBtn">ยืนยัน</button>
                    </div>
                  </div>
                </div>
              </div><!-- End Extra Large Modal-->
                <div class="col-md-2">
                  <label for="inputOR_No" class="form-label" data-bs-toggle="modal" data-bs-target="#ExtralargeModal1">Order No.</label>
                  <input type="text" class="form-control" id="inputOR_No" name="inputOR_No" readonly>
                </div>  
                                  <!-- Extra Large Modal -->
            
              <div class="modal fade" id="ExtralargeModal1" tabindex="-1">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Extra Large Modal</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <table id="tableOR" class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">Order No.</th>
                        <th scope="col">Shipment Date</th>
                        <th scope="col">Issue No.</th>
                        <th scope="col">Customer Code</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Plan No.</th>
                        <th scope="col">Status</th>
                        <th scope="col">เลือก</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr>
                      </tr>
                    </tbody>
                  </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <button type="button" class="btn btn-primary" id="confirmBtn1">ยืนยัน</button>
                    </div>
                  </div>
                </div>
              </div><!-- End Extra Large Modal-->
                <div class="col-md-1">
                  <legend class="col-form-label col-md-2 pt-0">.</legend>
                  <div class="col-sm-10">

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="gridCheck1">
                      <label class="form-check-label" for="gridCheck1">
                       New
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-md-1">
                  <label for="department" class="form-label">แผนก</label>
                  <input type="text" class="form-control" id="department" name="department" readonly>
                </div>  
                <div class="col-md-2">
                  <label for="inputDate" class="form-label">วันที่เปิด Order</label>
                  <input type="text" class="form-control" id="inputDate" name="inputDate" value="" readonly >
                </div>
                <div class="col-md-2">
                  <label for="shipDate" class="form-label">วันที่ขนงาน</label>
                  <input type="date" class="form-control" id="shipDate" name="shipDate" required>
                </div>
                <div class="col-md-2">
                  <label for="arrivetime" class="form-label">เวลาถึงลูกค้า</label>
                  <input type="time" class="form-control" id="arrivetime" name="arrivetime" value="" required>
                </div>
                <div class="col-md-2">
                  <label for="Customer_contact" class="form-label">ผู้ติดต่อ</label>
                  <input type="text" class="form-control" id="Customer_contact" name="Customer_contact" readonly>
                </div>
                <div class="col-md-3">
                  <label for="Customer_name" class="form-label">ชื่อลูกค้า</label>
                  <input type="text" class="form-control" id="Customer_name" name="Customer_name" readonly>
                </div>
                <div class="col-md-5">
                  <label for="Comp_Address" class="form-label">ที่อยู่รับWaste</label>
                  <input type="text" class="form-control" id="Comp_Address" name="Comp_Address" readonly>
                </div>
                <div class="col-md-2">
                  <label for="Trans_set" class="form-label">ชุดขนส่ง</label>
                  <select  class="form-select" id="Trans_set" name="Trans_set" onchange="fetchset()">
                    <option selected value="1">1</option>  
                    <option value="2">2</option>
                    <option value="3">3</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputTrans_type" class="form-label">ประเภทรถขนส่ง</label>
                  <input type="text" class="form-control" id="inputTrans_type" name="inputTrans_type" readonly>
                </div>
                <div class="col-md-2">
                  <label for="Trans_group" class="form-label">กลุ่มรถขนส่ง</label>
                  <input type="text" class="form-control" id="Trans_group" name="Trans_group" readonly>
                </div>
                <div class="col-md-2">
                  <label for="Container_support" class="form-label">ประเภทภาชนะ</label>
                  <input type="text" class="form-control" id="Container_support" name="Container_support" readonly>
                </div>
                <div class="col-md-4">
                  <label for="Staff_name" class="form-label">ชื่อพนักงานขาย</label>
                  <input type="text" class="form-control" id="Staff_name" name="Staff_name" readonly>
                </div>
                <div class="col-md-4">
                  <label for="Customer_service" class="form-label">ลูกค้าสัมพันธ์</label>
                  <input type="text" class="form-control" id="Customer_service" name="Customer_service" readonly>
                </div>
                <div class="col-md-6">
                  <label for="inputRemark" class="form-label">Remark</label>
                  <input type="text" class="form-control" id="inputRemark" name="inputRemark">
                </div>
                <div class="card">
            <div class="card-body">
              <!--h5 class="card-title"></h5-->
              <!-- Bordered Tabs Justified -->
              <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Waste</button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">หมายเหตุ</button>
                </li>
              </ul>
              <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">
                <table id="tablewaste" class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">เลือก</th>
                        <th scope="col">Waste Code</th>
                        <th scope="col">Waste Name</th>
                        <th scope="col">การกำจัด</th>
                        <th scope="col">ปริมาณ</th>
                        <th scope="col">หน่วย</th>
                        <th scope="col">จำนวนเงิน</th>
                        <th scope="col">รับ Waste</th>
                        <th scope="col">Mf Code</th>
                        <th scope="col">Factory</th>
                        <th scope="col">เลข กอ.</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                    
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
                <table id="tableorder_remark" class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Remark</th>
                        <th scope="col">Order No.</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                    
                      </tr>
                    </tbody>
                  </table>
                </div>
                
              </div><!-- End Bordered Tabs Justified -->

            </div>
          </div>       
                <!--div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck">
                    <label class="form-check-label" for="gridCheck">
                      Check me out
                    </label>
                  </div>
                </div-->
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-danger">ยกเลิก Order</button>
                  <button type="reset" class="btn btn-secondary" onclick="location.reload();">Reset</button>
                  <input type="hidden" id="staff" name="staff" value="<?php echo $staff;?>">
                </div>
              </form><!-- End Multi Columns Form -->

            </div>
          </div>

        <!--/div-->

        <!--div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Vertical Form</h5>

    
              <form class="row g-3">
                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Your Name</label>
                  <input type="text" class="form-control" id="inputNanme4">
                </div>
                <div class="col-12">
                  <label for="inputEmail4" class="form-label">Email</label>
                  <input type="email" class="form-control" id="inputEmail4">
                </div>
                <div class="col-12">
                  <label for="inputPassword4" class="form-label">Password</label>
                  <input type="password" class="form-control" id="inputPassword4">
                </div>
                <div class="col-12">
                  <label for="inputAddress" class="form-label">Address</label>
                  <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form>

            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">No Labels / Placeholders as labels Form</h5>

            
              <form class="row g-3">
                <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="Your Name">
                </div>
                <div class="col-md-6">
                  <input type="email" class="form-control" placeholder="Email">
                </div>
                <div class="col-md-6">
                  <input type="password" class="form-control" placeholder="Password">
                </div>
                <div class="col-12">
                  <input type="text" class="form-control" placeholder="Address">
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="City">
                </div>
                <div class="col-md-4">
                  <select id="inputState" class="form-select">
                    <option selected>Choose...</option>
                    <option>...</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <input type="text" class="form-control" placeholder="Zip">
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form>

            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Floating labels Form</h5>

       
              <form class="row g-3">
                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingName" placeholder="Your Name">
                    <label for="floatingName">Your Name</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="floatingEmail" placeholder="Your Email">
                    <label for="floatingEmail">Your Email</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating">
                    <textarea class="form-control" placeholder="Address" id="floatingTextarea" style="height: 100px;"></textarea>
                    <label for="floatingTextarea">Address</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="col-md-12">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingCity" placeholder="City">
                      <label for="floatingCity">City</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelect" aria-label="State">
                      <option selected>New York</option>
                      <option value="1">Oregon</option>
                      <option value="2">DC</option>
                    </select>
                    <label for="floatingSelect">State</label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingZip" placeholder="Zip">
                    <label for="floatingZip">Zip</label>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form>

            </div>
          </div>

        </div-->
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/query_issue.js"></script>
  <script src="assets/js/select_issue.js"></script>
  <script src="assets/js/select_order.js"></script>
  <!--script src="assets/js/script_form-order.js"></script-->
</body>

</html>