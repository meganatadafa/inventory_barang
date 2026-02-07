<?php
require 'function.php';
require 'cek.php';

// Analytics Data
$total_barang = mysqli_query($conn, "SELECT SUM(stock) as total FROM stock");
$total_barang_result = mysqli_fetch_array($total_barang);
$total_barang_count = $total_barang_result['total'] ?? 0;

$barang_masuk = mysqli_query($conn, "SELECT COUNT(*) as total FROM masuk");
$barang_masuk_result = mysqli_fetch_array($barang_masuk);
$total_masuk = $barang_masuk_result['total'] ?? 0;

$barang_keluar = mysqli_query($conn, "SELECT COUNT(*) as total FROM keluar");
$barang_keluar_result = mysqli_fetch_array($barang_keluar);
$total_keluar = $barang_keluar_result['total'] ?? 0;

$low_stock = mysqli_query($conn, "SELECT COUNT(*) as total FROM stock WHERE stock < 5");
$low_stock_result = mysqli_fetch_array($low_stock);
$total_low_stock = $low_stock_result['total'] ?? 0;

// Get barang with highest stock
$top_barang = mysqli_query($conn, "SELECT namabarang, stock FROM stock ORDER BY stock DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard Analytics</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/modern-styles.css" rel="stylesheet" />
    <link href="css/lemon-theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        /* Font Hitam untuk KPI Cards */
        .card h5 {
            color: #000000 !important;
        }

        .card .display-4 {
            color: #000000 !important;
        }

        .card h2 {
            color: #000000 !important;
        }

        /* KPI Cards Sejajar */
        .col-xl-3 .card {
            height: 90%;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">PT. Bondvast</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-10">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">INFORMASI GUDANG</div>
                        <a class="nav-link active" href="dashboard.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                            Stock Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-in-alt"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Barang Keluar
                        </a>
                        <?php if (isAdmin()): ?>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                                Kelola Admin
                            </a>
                        <?php endif; ?>
                        <a class="nav-link" href="logout.php">
                            LogOut
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Stock Information:</div>
                    PT. Bondvast
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Dashboard Analytics</h1>

                    <!-- KPI Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-2">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="card-title mb-0">Total Barang</h5>
                                            <h2 class="display-4 mb-0"><?php echo number_format($total_barang_count); ?></h2>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-boxes fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-2">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="card-title mb-0">Barang Masuk</h5>
                                            <h2 class="display-4 mb-0"><?php echo number_format($total_masuk); ?></h2>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-arrow-down fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-2">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="card-title mb-0">Barang Keluar</h5>
                                            <h2 class="display-4 mb-0"><?php echo number_format($total_keluar); ?></h2>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-arrow-up fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-2">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="card-title mb-0">Stok Sedikit</h5>
                                            <h2 class="display-4 mb-0"><?php echo number_format($total_low_stock); ?></h2>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exclamation-triangle fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    Top 5 Barang Terbanyak
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama Barang</th>
                                                    <th>Stok</th>
                                                    <th>Progress</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($data = mysqli_fetch_array($top_barang)) {
                                                    $namabarang = $data['namabarang'];
                                                    $stock = $data['stock'];
                                                    $max_stock = $total_barang_count > 0 ? $stock : 1;
                                                    $percentage = ($stock / ($total_barang_count / 5)) * 100;
                                                    if ($percentage > 100) $percentage = 100;
                                                ?>
                                                    <tr>
                                                        <td><?php echo $namabarang; ?></td>
                                                        <td><?php echo number_format($stock); ?></td>
                                                        <td>
                                                            <div class="progress">
                                                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Ringkasan Transaksi
                                </div>
                                <div class="card-body">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-list-alt mr-1"></i>
                                    Peringatan Stok Rendah
                                </div>
                                <div class="card-body">
                                    <?php
                                    $low_stock_items = mysqli_query($conn, "SELECT * FROM stock WHERE stock < 5 ORDER BY stock ASC LIMIT 10");
                                    $has_low_stock = false;
                                    while ($data = mysqli_fetch_array($low_stock_items)) {
                                        $has_low_stock = true;
                                        $namabarang = $data['namabarang'];
                                        $stock = $data['stock'];
                                    ?>
                                        <div class="alert alert-warning">
                                            <strong>Perhatian!</strong> Barang <strong><?php echo $namabarang; ?></strong> tersisa hanya <strong><?php echo $stock; ?></strong> unit!
                                        </div>
                                    <?php
                                    }
                                    if (!$has_low_stock) {
                                    ?>
                                        <div class="alert alert-success">
                                            <strong>Bagus!</strong> Semua barang dalam stok aman.
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; PT. Bondvast 2025</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script>
        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Barang Masuk', 'Barang Keluar'],
                datasets: [{
                    data: [<?php echo $total_masuk; ?>, <?php echo $total_keluar; ?>],
                    backgroundColor: ['#1cc88a', '#f6c23e'],
                    hoverBackgroundColor: ['#17a673', '#dda20a'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true,
                    position: 'bottom'
                },
                cutoutPercentage: 80,
            },
        });
    </script>
</body>

</html>