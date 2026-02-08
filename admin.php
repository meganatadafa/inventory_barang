<?php
require 'function.php';
require 'cek.php';

// Check if user is admin, if not redirect
if (!isAdmin()) {
    header('location:index.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Kelola Akun</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/modern-styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="css/lemon-theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">TOKO BAROKAH</a>
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
                        <a class="nav-link" href="dashboard.php">
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
                        <a class="nav-link active" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                            Kelola Akun
                        </a>
                        <a class="nav-link" href="logout.php">
                            LogOut
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Stock Information:</div>
                    TOKO BAROKAH
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Kelola Akun</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                <i class="fas fa-user-plus mr-2"></i>Tambah Akun
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Email Admin</th>
                                            <th>Role</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ambilsemuadataadmin = mysqli_query($conn, 'select * from login');
                                        $i = 1;
                                        while ($data = mysqli_fetch_array($ambilsemuadataadmin)) {
                                            $em = $data['email'];
                                            $iduser = $data['iduser'];
                                            $pw = $data['password'];
                                            $role = $data['role'];
                                        ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $em; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $role == 'admin' ? 'badge-primary' : 'badge-info'; ?>">
                                                        <?php echo ucfirst($role); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?= $iduser; ?>">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?= $iduser; ?>">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal" id="edit<?= $iduser; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Admin</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="emailadmin">Email Admin</label>
                                                                    <input type="email" name="emailadmin" value="<?= $em; ?>" class="form-control" placeholder="Email" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="passwordbaru">Password Baru</label>
                                                                    <input type="password" name="passwordbaru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                                                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="role">Role</label>
                                                                    <select name="role" class="form-control" required>
                                                                        <option value="admin" <?= $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                                        <option value="user" <?= $role == 'user' ? 'selected' : ''; ?>>User</option>
                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="id" value="<?= $iduser; ?>">
                                                                <button type="submit" class="btn btn-primary" name="updateadmin">
                                                                    <i class="fas fa-save mr-2"></i>Update
                                                                </button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                    <i class="fas fa-times mr-2"></i>Batal
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal" id="delete<?= $iduser; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Admin?</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus admin <strong><?= $em; ?></strong>?</p>
                                                                <div class="alert alert-warning">
                                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                                                                </div>
                                                                <input type="hidden" name="id" value="<?= $iduser; ?>">
                                                                <button type="submit" class="btn btn-danger" name="hapusadmin">
                                                                    <i class="fas fa-trash mr-2"></i>Hapus
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        };
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; TOKO BAROKAH 2025</div>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
</body>

<!-- Add Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Admin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email Admin</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <small class="form-text text-muted">Minimal 6 karakter</small>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                        <small class="form-text text-muted">Admin: Full access | User: Read-only access</small>
                    </div>
                    <button type="submit" class="btn btn-primary" name="addadmin">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</html>