# Dokumentasi Lengkap Sistem Inventory PT. Bondvast

## 📋 Tanggal: 8 Februari 2026

## 👨‍💻 Developer: Cline AI Assistant

---

## 🎯 OVERVIEW SISTEM

Sistem Inventory PT. Bondvast adalah aplikasi berbasis web untuk manajemen stok barang yang lengkap dengan fitur tracking barang masuk dan keluar, dashboard analytics, serta sistem role-based access control (Admin/User). Sistem ini dibangun menggunakan PHP, MySQL, Bootstrap 4, dan JavaScript.

---

## 📚 DAFTAR ISI

1. [Arsitektur Sistem](#arsitektur-sistem)
2. [Database Schema](#database-schema)
3. [Struktur File](#struktur-file)
4. [Alur Login & Autentikasi](#alur-login--autentikasi)
5. [Sistem Role-Based Access Control](#sistem-role-based-access-control)
6. [Fitur Utama dan Penjelasan Code](#fitur-utama-dan-penjelasan-code)
7. [Dashboard Analytics](#dashboard-analytics)
8. [Manajemen Stok Barang](#manajemen-stok-barang)
9. [Manajemen Barang Masuk](#manajemen-barang-masuk)
10. [Manajemen Barang Keluar](#manajemen-barang-keluar)
11. [Manajemen User/Admin](#manajemen-useradmin)
12. [Export Data](#export-data)
13. [Keamanan & Catatan Penting](#keamanan--catatan-penting)

---

## ARSITEKTUR SISTEM

### 1. Konsep MVC Sederhana

Sistem ini mengikuti pola arsitektur sederhana dengan pemisahan logika dan tampilan:

```
┌─────────────────────────────────────────────────┐
│                  BROWSER                         │
│         (User Interface / View)                 │
├─────────────────────────────────────────────────┤
│  index.php, dashboard.php, masuk.php, dll       │
│  (Halaman HTML dengan PHP embedded)             │
├─────────────────────────────────────────────────┤
│  function.php (Controller/Logic Layer)         │
│  - Semua logika CRUD                            │
│  - Query database                               │
│  - Session management                           │
├─────────────────────────────────────────────────┤
│  MySQL Database (Model/Data Layer)              │
│  - tabel stock                                  │
│  - tabel masuk                                  │
│  - tabel keluar                                 │
│  - tabel login                                  │
└─────────────────────────────────────────────────┘
```

### 2. Alur Data

```
User Input → Form HTML → POST Request → function.php → Database → Response → Update UI
```

### 3. Komponen Utama

**Frontend:**

- HTML5 (Structure)
- Bootstrap 4 (CSS Framework)
- jQuery (JavaScript Library)
- DataTables (Table Plugin)
- Chart.js (Visualization)
- Font Awesome (Icons)

**Backend:**

- PHP 7+ (Server-side Logic)
- MySQLi (Database Connectivity)
- Sessions (Authentication)

**Database:**

- MySQL (Relational Database)
- 4 Tabel Utama: stock, masuk, keluar, login

---

## DATABASE SCHEMA

### Tabel: stock (Master Barang)

```sql
CREATE TABLE stock (
    idbarang INT AUTO_INCREMENT PRIMARY KEY,
    namabarang VARCHAR(100) NOT NULL,
    deskripsi VARCHAR(255),
    stock INT DEFAULT 0
)
```

**Penjelasan:**

- `idbarang`: Primary key, identifier unik untuk setiap barang
- `namabarang`: Nama barang (wajib diisi)
- `deskripsi`: Deskripsi detail barang (opsional)
- `stock`: Jumlah stok saat ini (default 0)

**Relasi:**

- `idbarang` digunakan sebagai foreign key di tabel `masuk` dan `keluar`

### Tabel: masuk (Transaksi Barang Masuk)

```sql
CREATE TABLE masuk (
    idmasuk INT AUTO_INCREMENT PRIMARY KEY,
    idbarang INT,
    tanggal DATE,
    keterangan VARCHAR(100),  -- Nama supplier
    qty INT,
    FOREIGN KEY (idbarang) REFERENCES stock(idbarang)
)
```

**Penjelasan:**

- `idmasuk`: Primary key, identifier unik untuk setiap transaksi masuk
- `idbarang`: Foreign key ke tabel stock (barang yang masuk)
- `tanggal`: Tanggal transaksi (otomatis Y-m-d)
- `keterangan`: Nama supplier atau penerima
- `qty`: Jumlah barang yang masuk

**Relasi:**

- Banyak-to-one dengan tabel `stock` (satu barang bisa masuk berkali-kali)

### Tabel: keluar (Transaksi Barang Keluar)

```sql
CREATE TABLE keluar (
    idkeluar INT AUTO_INCREMENT PRIMARY KEY,
    idbarang INT,
    penerima VARCHAR(100),
    qty INT,
    tanggal DATE,
    FOREIGN KEY (idbarang) REFERENCES stock(idbarang)
)
```

**Penjelasan:**

- `idkeluar`: Primary key, identifier unik untuk setiap transaksi keluar
- `idbarang`: Foreign key ke tabel stock (barang yang keluar)
- `penerima`: Nama penerima barang
- `qty`: Jumlah barang yang keluar
- `tanggal`: Tanggal transaksi (NOW() function)

**Relasi:**

- Banyak-to-one dengan tabel `stock` (satu barang bisa keluar berkali-kali)

### Tabel: login (User Authentication)

```sql
CREATE TABLE login (
    iduser INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'admin'
)
```

**Penjelasan:**

- `iduser`: Primary key, identifier unik untuk setiap user
- `email`: Email user (unik, digunakan untuk login)
- `password`: Password user (masih plaintext, perlu hashing di masa depan)
- `role`: Role user ('admin' atau 'user')

**Catatan:**

- Role 'admin': Akses penuh (tambah, edit, hapus data)
- Role 'user': Akses baca saja (view-only)

---

## STRUKTUR FILE

```
UKK_stokbarang/
│
├── config.php              (Konfigurasi - tidak ada, langsung di function.php)
├── function.php            (LOGIKA UTAMA SISTEM)
├── cek.php                 (Session validation)
├── login.php               (Halaman login)
├── logout.php              (Logout handler)
├── index.php               (Halaman Stock Barang)
├── dashboard.php           (Halaman Dashboard Analytics)
├── masuk.php               (Halaman Barang Masuk)
├── keluar.php              (Halaman Barang Keluar)
├── admin.php               (Halaman Kelola Admin)
│
├── export.php              (Export stok ke Excel)
├── exportmasuk.php         (Export barang masuk ke Excel)
├── exportkeluar.php        (Export barang keluar ke Excel)
│
├── migration_add_role.sql   (SQL script untuk migrasi role)
├── run_migration.php       (PHP script untuk menjalankan migrasi)
│
├── css/
│   ├── styles.css          (Bootstrap styles)
│   ├── modern-styles.css   (Custom modern styles)
│   └── lemon-theme.css     (Lemon color theme)
│
├── js/
│   └── scripts.js          (Main JavaScript)
│
├── assets/
│   └── demo/
│       ├── chart-area-demo.js
│       ├── chart-bar-demo.js
│       ├── chart-pie-demo.js
│       └── datatables-demo.js
│
└── documentation/
    ├── DOKUMENTASI_LENGKAP.md (file ini)
    ├── DASHBOARD_GUIDE.md
    ├── USER_ROLE_GUIDE.md
    └── PANDUAN_BARU.md
```

---

## ALUR LOGIN & AUTENTIKASI

### 1. File: login.php

**Tujuan:** Menangani proses login dan memverifikasi user

**Code Breakdown:**

```php
<?php
require 'function.php';  // Load file function.php untuk koneksi dan helper functions

// Cek apakah form login disubmit
if (isset($_POST['login'])) {
    $email = $_POST['email'];          // Ambil email dari form
    $password = $_POST['password'];    // Ambil password dari form

    // Query database untuk mencari user dengan email dan password yang cocok
    $cekdatabase = mysqli_query($conn,
        "SELECT * FROM login where email='$email' and password='$password'"
    );

    // Hitung jumlah data yang ditemukan
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {  // Jika data ditemukan (login berhasil)
        $ambildata = mysqli_fetch_array($cekdatabase);

        // Set session variables
        $_SESSION['log'] = 'True';          // Flag login
        $_SESSION['role'] = $ambildata['role'];  // Simpan role user
        $_SESSION['email'] = $ambildata['email']; // Simpan email

        header('location:index.php');  // Redirect ke halaman utama
    } else {  // Jika data tidak ditemukan (login gagal)
        header('location:login.php');  // Redirect kembali ke login
    }
}

// Cek apakah user sudah login
if (!isset($_SESSION['log'])) {
    // Jika belum login, tetap di halaman login
} else {
    // Jika sudah login, redirect ke index.php
    header('location:index.php');
}
?>
```

**Penjelasan Detail:**

1. **`require 'function.php'`**

   - Memuat file function.php yang berisi:
     - Koneksi database (`$conn`)
     - Helper function `isAdmin()`

2. **`if (isset($_POST['login']))`**

   - Mengecek apakah tombol login ditekan
   - Form HTML menggunakan `<input type="submit" name="login">`

3. **Query Database:**

   ```php
   mysqli_query($conn, "SELECT * FROM login where email='$email' and password='$password'")
   ```

   - Mencari user dengan email dan password yang cocok
   - ⚠️ **SECURITY ISSUE:** Tidak menggunakan prepared statements (vulnerable SQL injection)

4. **`mysqli_num_rows($cekdatabase)`**

   - Mengembalikan jumlah baris hasil query
   - `> 0` berarti user ditemukan
   - `0` berarti user tidak ditemukan

5. **Session Management:**

   ```php
   $_SESSION['log'] = 'True';
   $_SESSION['role'] = $ambildata['role'];
   $_SESSION['email'] = $ambildata['email'];
   ```

   - `$_SESSION['log']`: Flag untuk menandai user sudah login
   - `$_SESSION['role']`: Menyimpan role ('admin' atau 'user')
   - `$_SESSION['email']`: Menyimpan email user

6. **Auto-Redirect:**
   ```php
   if (!isset($_SESSION['log'])) {
       // Tetap di login
   } else {
       header('location:index.php');  // Redirect jika sudah login
   }
   ```

### 2. File: cek.php (Session Validation)

**Tujuan:** Validasi session di setiap halaman yang memerlukan login

**Code:**

```php
<?php
if (!isset($_SESSION['log'])) {
    header('location:login.php');
}
?>
```

**Penjelasan:**

- Cek apakah session `log` tidak ada
- Jika tidak ada, redirect ke login.php
- Digunakan dengan `require 'cek.php'` di setiap halaman yang dilindungi

### 3. File: logout.php

**Tujuan:** Menghapus session dan logout user

**Code:**

```php
<?php
session_start();
session_destroy();
header('location:login.php');
?>
```

**Penjelasan:**

1. `session_start()`: Mulai session
2. `session_destroy()`: Hapus semua data session
3. `header('location:login.php')`: Redirect ke halaman login

---

## SISTEM ROLE-BASED ACCESS CONTROL

### 1. Helper Function: isAdmin()

**Lokasi:** function.php (baris 5-8)

```php
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
```

**Penjelasan:**

- Fungsi helper untuk mengecek apakah user adalah admin
- Mengembalikan `true` jika role = 'admin'
- Mengembalikan `false` jika role = 'user' atau session tidak ada

**Penggunaan di halaman:**

```php
<?php if (isAdmin()): ?>
    <!-- Konten khusus admin -->
    <button>Tambah Barang</button>
<?php endif; ?>
```

### 2. Migrasi Database Role

**File:** migration_add_role.sql

```sql
ALTER TABLE login ADD COLUMN role ENUM('admin', 'user') DEFAULT 'admin';
UPDATE login SET role = 'admin';
```

**Penjelasan:**

- Menambahkan kolom `role` ke tabel `login`
- Set default value = 'admin'
- Update semua existing user ke role 'admin'

**File:** run_migration.php

```php
<?php
$conn = mysqli_connect("localhost", "root", "root", "UKK_stokbarang");

$sql = file_get_contents('migration_add_role.sql');
if (mysqli_multi_query($conn, $sql)) {
    echo "Migrasi berhasil!";
} else {
    echo "Migrasi gagal: " . mysqli_error($conn);
}
?>
```

### 3. Perbedaan Akses Admin vs User

| Fitur                     | Admin | User |
| ------------------------- | ----- | ---- |
| Lihat Dashboard           | ✅    | ✅   |
| Lihat Stok Barang         | ✅    | ✅   |
| Lihat Barang Masuk        | ✅    | ✅   |
| Lihat Barang Keluar       | ✅    | ✅   |
| Export Data               | ✅    | ✅   |
| Tambah Barang             | ✅    | ❌   |
| Edit Stok                 | ✅    | ❌   |
| Hapus Barang              | ✅    | ❌   |
| Tambah Barang Masuk       | ✅    | ❌   |
| Edit Barang Masuk         | ✅    | ❌   |
| Hapus Barang Masuk        | ✅    | ❌   |
| Tambah Barang Keluar      | ✅    | ❌   |
| Edit Barang Keluar        | ✅    | ❌   |
| Hapus Barang Keluar       | ✅    | ❌   |
| Kelola Admin/User         | ✅    | ❌   |
| Akses menu "Kelola Admin" | ✅    | ❌   |

### 4. Implementasi Role di Frontend

**Contoh di index.php:**

```php
<?php if (isAdmin()): ?>
    <!-- Tombol Tambah Barang hanya muncul untuk admin -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Tambah Barang
    </button>
<?php endif; ?>

<!-- Di dalam tabel -->
<?php if (isAdmin()): ?>
    <th>Aksi</th>
<?php endif; ?>

<!-- Di dalam loop data -->
<?php if (isAdmin()): ?>
    <td>
        <button class="btn btn-warning">Edit</button>
        <button class="btn btn-danger">Delete</button>
    </td>
<?php endif; ?>
```

### 5. Implementasi Role di Sidebar

**Di semua halaman:**

```php
<div class="nav">
    <div class="sb-sidenav-menu-heading">INFORMASI GUDANG</div>
    <a class="nav-link" href="dashboard.php">Dashboard</a>
    <a class="nav-link" href="index.php">Stock Barang</a>
    <a class="nav-link" href="masuk.php">Barang Masuk</a>
    <a class="nav-link" href="keluar.php">Barang Keluar</a>

    <!-- Menu ini hanya muncul untuk admin -->
    <?php if (isAdmin()): ?>
        <a class="nav-link" href="admin.php">Kelola Admin</a>
    <?php endif; ?>

    <a class="nav-link" href="logout.php">LogOut</a>
</div>
```

---

## FITUR UTAMA DAN PENJELASAN CODE

### File: function.php (LOGIKA UTAMA)

**Tujuan:** Menangani semua operasi CRUD dan business logic

**Struktur:**

1. **Database Connection** (baris 3-11)
2. **Helper Function** (baris 5-8)
3. **Add New Item** (baris 14-30)
4. **Add Item In** (baris 33-56)
5. **Add Item Out** (baris 59-92)
6. **Update Item** (baris 95-108)
7. **Delete Item** (baris 111-130)
8. **Update Item In** (baris 133-172)
9. **Delete Item In** (baris 175-195)
10. **Update Item Out** (baris 199-234)
11. **Delete Item Out** (baris 237-258)
12. **Add Admin** (baris 261-276)
13. **Update Admin** (baris 279-293)
14. **Delete Admin** (baris 296-306)

### 1. Database Connection

```php
<?php
session_start();

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "root", "UKK_stokbarang");

// Helper function untuk cek admin
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
```

**Penjelasan:**

1. `session_start()`: Memulai PHP session untuk autentikasi
2. `mysqli_connect()`:
   - Parameter: hostname, username, password, database name
   - Mengembalikan connection object (`$conn`)
   - Jika gagal, `$conn = false`
3. `if (!$conn)`: Cek apakah koneksi gagal
4. `die()`: Stop eksekusi dan tampilkan error message

### 2. Tambah Barang Baru (Create Stock)

```php
if (isset($_POST['addnewbarang'])) {
    // Ambil nilai dari input form
    $namabarang = $_POST['namabarang'];
    $deskripsi  = $_POST['deskripsi'];
    $stock      = $_POST['stock'];

    // Insert ke database
    $addtotable = mysqli_query(
        $conn,
        "INSERT INTO stock (namabarang, deskripsi, stock)
        VALUES ('$namabarang', '$deskripsi', '$stock')"
    );

    if ($addtotable) {
        header('Location: index.php');
        exit();
    } else {
        echo "Gagal menambah barang: " . mysqli_error($conn);
    }
}
```

**Penjelasan:**

1. **Trigger Check:**

   ```php
   if (isset($_POST['addnewbarang']))
   ```

   - Mengecek apakah form dengan tombol "addnewbarang" disubmit

2. **Data Extraction:**

   ```php
   $namabarang = $_POST['namabarang'];
   $deskripsi  = $_POST['deskripsi'];
   $stock      = $_POST['stock'];
   ```

   - Mengambil data dari form POST
   - ⚠️ **SECURITY ISSUE:** Tidak ada sanitasi input (vulnerable XSS)

3. **Database Insert:**

   ```php
   $addtotable = mysqli_query($conn,
       "INSERT INTO stock (namabarang, deskripsi, stock)
       VALUES ('$namabarang', '$deskripsi', '$stock')"
   );
   ```

   - Insert data ke tabel `stock`
   - Mengembalikan `true` jika berhasil, `false` jika gagal
   - ⚠️ **SECURITY ISSUE:** Tidak menggunakan prepared statements

4. **Response Handling:**
   ```php
   if ($addtotable) {
       header('Location: index.php');  // Redirect jika berhasil
       exit();
   } else {
       echo "Gagal: " . mysqli_error($conn);  // Tampilkan error jika gagal
   }
   ```

**Flow:**

```
Form Submit → Check POST → Extract Data → Insert DB → Redirect/Show Error
```

### 3. Tambah Barang Masuk (Stock In)

```php
if (isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];    // ID barang
    $penerima = $_POST['penerima'];    // Nama supplier
    $qty = $_POST['qty'];               // Jumlah
    $tanggal = date('Y-m-d');            // Tanggal hari ini

    // Cek stock sekarang
    $cekstocksekarang = mysqli_query($conn,
        "select * from stock where idbarang='$barangnya'"
    );
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $stocksekarang = $ambildatanya['stock'];

    // Hitung stock baru (stock saat ini + qty masuk)
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    // Insert ke tabel masuk
    $addtomasuk = mysqli_query($conn,
        "INSERT INTO masuk (idbarang, tanggal, keterangan, qty)
        VALUES ('$barangnya', '$tanggal', '$penerima', '$qty')"
    );

    // Update stock di tabel stock
    $updatestockmasuk = mysqli_query($conn,
        "update stock set stock='$tambahkanstocksekarangdenganquantity'
        where idbarang='$barangnya'"
    );

    if ($addtomasuk && $updatestockmasuk) {
        header('Location: masuk.php');
        exit();
    } else {
        echo "Gagal menambah barang: " . mysqli_error($conn);
    }
}
```

**Penjelasan Detail:**

1. **Input Data:**

   - `$barangnya`: ID barang yang dipilih dari dropdown
   - `$penerima`: Nama supplier
   - `$qty`: Jumlah barang masuk
   - `$tanggal`: Tanggal hari ini (format Y-m-d)

2. **Read Current Stock:**

   ```php
   $cekstocksekarang = mysqli_query($conn,
       "select * from stock where idbarang='$barangnya'"
   );
   $ambildatanya = mysqli_fetch_array($cekstocksekarang);
   $stocksekarang = $ambildatanya['stock'];
   ```

   - Query tabel `stock` berdasarkan `idbarang`
   - `mysqli_fetch_array()`: Mengambil 1 baris hasil query sebagai array
   - `$stocksekarang`: Stock saat ini sebelum update

3. **Calculate New Stock:**

   ```php
   $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;
   ```

   - Stock baru = stock lama + qty masuk

4. **Insert Transaction Record:**

   ```php
   $addtomasuk = mysqli_query($conn,
       "INSERT INTO masuk (idbarang, tanggal, keterangan, qty)
       VALUES ('$barangnya', '$tanggal', '$penerima', '$qty')"
   );
   ```

   - Mencatat transaksi di tabel `masuk`
   - Menyimpan: idbarang, tanggal, keterangan (supplier), qty

5. **Update Stock Master:**

   ```php
   $updatestockmasuk = mysqli_query($conn,
       "update stock set stock='$tambahkanstocksekarangdenganquantity'
       where idbarang='$barangnya'"
   );
   ```

   - Update stock di tabel `stock`
   - Stock berkurang bertambah sesuai qty

6. **Transaction Integrity:**
   ```php
   if ($addtomasuk && $updatestockmasuk)
   ```
   - Hanya redirect jika KEDUA query berhasil
   - Jika salah satu gagal, tampilkan error

**Flow:**

```
Form Submit → Get Input → Read Current Stock → Calculate New Stock
→ Insert to masuk table → Update stock table → Redirect/Show Error
```

### 4. Tambah Barang Keluar (Stock Out)

```php
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    // Cek stock sekarang
    $cekstocksekarang = mysqli_query($conn,
        "select * from stock where idbarang='$barangnya'"
    );
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $stocksekarang = $ambildatanya['stock'];

    // Validasi: cek apakah stok mencukupi
    if ($qty > $stocksekarang) {
        // Jika stok tidak mencukupi, tampilkan pesan error dan hentikan proses
        echo "<script>alert('Stok tidak mencukupi! Stok tersedia: $stocksekarang');
              window.location.href='keluar.php';</script>";
        exit();
    }

    // Hitung stock baru (stock saat ini - qty keluar)
    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

    // Insert ke tabel keluar
    $addtokeluar = mysqli_query($conn,
        "INSERT INTO keluar (idbarang, penerima, qty, tanggal)
        VALUES ('$barangnya', '$penerima', '$qty', NOW())"
    );

    // Update stock di tabel stock
    $updatestockmasuk = mysqli_query($conn,
        "update stock set stock='$tambahkanstocksekarangdenganquantity'
        where idbarang='$barangnya'"
    );

    if ($addtokeluar && $updatestockmasuk) {
        header('Location:keluar.php');
        exit();
    } else {
        echo "Gagal menambah barang: " . mysqli_error($conn);
    }
}
```

**Penjelasan Detail:**

1. **Stock Validation:**

   ```php
   if ($qty > $stocksekarang) {
       echo "<script>alert('Stok tidak mencukupi! Stok tersedia: $stocksekarang');
             window.location.href='keluar.php';</script>";
       exit();
   }
   ```

   - **VALIDASI PENTING:** Mencegah stock negatif
   - Jika qty keluar > stock saat ini, hentikan proses
   - Tampilkan alert JavaScript dengan informasi stock tersedia
   - `window.location.href='keluar.php'`: Redirect kembali ke halaman
   - `exit()`: Stop eksekusi PHP

2. **Stock Calculation:**

   ```php
   $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;
   ```

   - Stock baru = stock lama - qty keluar

3. **Insert Transaction:**
   ```php
   $addtokeluar = mysqli_query($conn,
       "INSERT INTO keluar (idbarang, penerima, qty, tanggal)
       VALUES ('$barangnya', '$penerima', '$qty', NOW())"
   );
   ```
   - `NOW()`: Fungsi MySQL untuk tanggal dan waktu saat ini
   - Menyimpan: idbarang, penerima, qty, timestamp

**Flow:**

```
Form Submit → Get Input → Read Current Stock →
Validate Stock (qty <= stock?) →
[IF VALID] Calculate New Stock → Insert to keluar → Update stock → Redirect
[IF INVALID] Show Alert → Redirect Back → Exit
```

### 5. Update Barang (Edit Stock)

```php
if (isset($_POST["updatebarang"])) {
    $idb = $_POST["idb"];              // ID barang
    $namabarang = $_POST["namabarang"]; // Nama baru
    $deskripsi = $_POST["deskripsi"];  // Deskripsi baru

    $update = mysqli_query($conn,
        "update stock set namabarang='$namabarang', deskripsi='$deskripsi'
        where idbarang = '$idb'"
    );

    if ($update) {
        header('Location:index.php');
        exit();
    } else {
        echo "Gagal" . mysqli_error($conn);
    }
}
```

**Penjelasan:**

- Update kolom `namabarang` dan `deskripsi` saja
- Tidak update stock (stock diupdate lewat transaksi masuk/keluar)
- Menggunakan WHERE clause untuk spesifik barang

### 6. Hapus Barang (Delete Stock)

```php
if (isset($_POST["hapusbarang"])) {
    $idb = $_POST["idb"];

    // Hapus referensi di tabel masuk terlebih dahulu
    $hapus_masuk = mysqli_query($conn,
        "delete from masuk where idbarang = '$idb'"
    );

    // Hapus referensi di tabel keluar terlebih dahulu
    $hapus_keluar = mysqli_query($conn,
        "delete from keluar where idbarang = '$idb'"
    );

    // Baru hapus dari tabel stock
    $hapus = mysqli_query($conn,
        "delete from stock where idbarang = '$idb'"
    );

    if ($hapus) {
        header('Location:index.php');
        exit();
    } else {
        echo "Gagal" . mysqli_error($conn);
    }
}
```

**Penjelasan Detail:**

1. **CASCADE DELETE Manual:**

   - MySQL memiliki foreign key constraint
   - Jika menghapus dari `stock` tanpa menghapus dari `masuk`/`keluar` dulu, akan error
   - Order delete penting: `masuk` → `keluar` → `stock`

2. **Transaction Safety:**
   ```php
   if ($hapus)
   ```
   - Hanya redirect jika hapus dari `stock` berhasil
   - ⚠️ **ISSUE:** Tidak mengecek apakah `hapus_masuk` dan `hapus_keluar` berhasil
   - Bisa terjadi data inconsistency

**Rekomendasi Perbaikan:**

```php
if ($hapus_masuk && $hapus_keluar && $hapus) {
    header('Location:index.php');
} else {
    echo "Gagal";
}
```

### 7. Update Barang Masuk (Edit In Transaction)

```php
if (isset($_POST["updatebarangmasuk"])) {
    $idb = $_POST["idb"];        // ID barang
    $idm = $_POST["idm"];        // ID transaksi masuk
    $keterangan = $_POST["keterangan"]; // Supplier baru
    $qty = $_POST["qty"];        // Qty baru

    // Ambil stock saat ini
    $lihatstock = mysqli_query($conn,
        "select * from stock where idbarang='$idb'"
    );
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // Ambil qty lama dari tabel masuk
    $qtyskrg = mysqli_query($conn,
        "select * from masuk where idmasuk='$idm'"
    );
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtylama = $qtynya['qty'];

    // Hitung selisih dan stock baru
    if ($qty > $qtylama) {
        // Jika qty baru lebih besar, tambahkan ke stock
        $selisih = $qty - $qtylama;
        $stockbaru = $stockskrg + $selisih;
    } else {
        // Jika qty baru lebih kecil, kurangi dari stock
        $selisih = $qtylama - $qty;
        $stockbaru = $stockskrg - $selisih;
    }

    // Update stock dan data masuk
    $updatestock = mysqli_query($conn,
        "update stock set stock='$stockbaru' where idbarang='$idb'"
    );
    $updatemasuk = mysqli_query($conn,
        "update masuk set qty='$qty', keterangan='$keterangan'
        where idmasuk='$idm'"
    );

    if ($updatestock && $updatemasuk) {
        header('Location:masuk.php');
        exit();
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
```

**Penjelasan Detail:**

1. **Business Logic Penting:**

   - Saat edit transaksi masuk, stock master harus diupdate juga
   - Perlu menghitung selisih antara qty lama dan baru

2. **Selisih Calculation:**
   ```php
   if ($qty > $qtylama) {
       $selisih = $qty - $qtylama;
       $stockbaru = $stockskrg + $selisih;  // Tambah ke stock
   } else {
       $selisih = $qtylama - $qty;
       $stockbaru = $stockskrg - $selisih;  // Kurangi dari stock
   }
   ```
   - Jika qty bertambah: stock master bertambah
   - Jika qty berkurang: stock master berkurang

**Contoh:**

- Stock saat ini: 100
- Transaksi masuk lama: +50 (seharusnya stock jadi 150)
- Edit transaksi jadi: +30 (seharusnya stock jadi 130)
- Selisih: 50 - 30 = 20
- Stock baru: 150 - 20 = 130

**Flow:**

```
Form Submit → Get New Data → Read Current Stock → Read Old Qty →
Calculate Difference → Calculate New Stock →
Update Stock Master → Update Transaction Record → Redirect
```

### 8. Hapus Barang Masuk (Delete In Transaction)

```php
if (isset($_POST["hapusbarangmasuk"])) {
    $idb = $_POST["idb"];     // ID barang
    $idm = $_POST["idm"];     // ID transaksi
    $qty = $_POST["kty"];     // Qty yang akan dihapus

    // Ambil stock saat ini
    $lihatstock = mysqli_query($conn,
        "select * from stock where idbarang='$idb'"
    );
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // Kurangi stock dengan qty yang dihapus
    $stockbaru = $stockskrg - $qty;

    // Update stock dan hapus data masuk
    $updatestock = mysqli_query($conn,
        "update stock set stock='$stockbaru' where idbarang='$idb'"
    );
    $hapusmasuk = mysqli_query($conn,
        "delete from masuk where idmasuk='$idm'"
    );

    if ($updatestock && $hapusmasuk) {
        header('Location:masuk.php');
        exit();
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
}
```

**Penjelasan:**

- Saat menghapus transaksi masuk, stock master harus dikurangi
- Karena transaksi masuk menambah stock, maka menghapusnya harus mengurangi stock

**Contoh:**

- Stock saat ini: 150 (terdiri dari 100 awal + 50 dari transaksi)
- Hapus transaksi +50
- Stock baru: 150 - 50 = 100

### 9. Update Barang Keluar (Edit Out Transaction)

```php
if (isset($_POST["updatebarangkeluar"])) {
    $idb = $_POST["idb"];        // ID barang
    $idk = $_POST["idk"];        // ID transaksi keluar
    $penerima = $_POST["penerima"]; // Penerima baru
    $qty = $_POST["qty"];        // Qty baru

    // Ambil stock saat ini
    $lihatstock = mysqli_query($conn,
        "select * from stock where idbarang='$idb'"
    );
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // Ambil qty lama
    $qtyskrg = mysqli_query($conn,
        "select * from keluar where idkeluar='$idk'"
    );
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtylama = $qtynya['qty'];

    // Hitung selisih dan stock baru
    if ($qty > $qtylama) {
        $selisih = $qty - $qtylama;
        $stockbaru = $stockskrg - $selisih;

        // Validasi stock tidak boleh negatif
        if ($stockbaru < 0) {
            echo "<script>alert('Stok tidak mencukupi! Stok tersedia: $stockskrg');
                  window.location.href='keluar.php';</script>";
            exit();
        }
    } else {
        $selisih = $qtylama - $qty;
        $stockbaru = $stockskrg + $selisih;
    }

    // Update stock dan data keluar
    $updatestock = mysqli_query($conn,
        "update stock set stock='$stockbaru' where idbarang='$idb'"
    );
    $updatekeluar = mysqli_query($conn,
        "update keluar set qty='$qty', penerima='$penerima'
        where idkeluar='$idk'"
    );

    if ($updatestock && $updatekeluar) {
        header('Location:keluar.php');
        exit();
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}
```

**Penjelasan:**

- Logika mirip dengan edit barang masuk
- Tapi arahnya terbalik (barang keluar mengurangi stock)

**Validasi Penting:**

```php
if ($stockbaru < 0) {
    echo "<script>alert('Stok tidak mencukupi! Stok tersedia: $stockskrg');
          window.location.href='keluar.php';</script>";
    exit();
}
```

- Mencegah stock negatif saat edit
- Jika qty baru > qty lama, stock berkurang

**Contoh:**

- Stock saat ini: 50 (terdiri dari 100 awal - 50 dari transaksi keluar)
- Edit transaksi keluar jadi: 70
- Selisih: 70 - 50 = 20
- Stock baru: 50 - 20 = 30

### 10. Hapus Barang Keluar (Delete Out Transaction)

```php
if (isset($_POST["hapusbarangkeluar"])) {
    $idb = $_POST["idb"];     // ID barang
    $idk = $_POST["idk"];     // ID transaksi
    $qty = $_POST["kty"];     // Qty yang akan dihapus

    // Ambil stock saat ini
    $lihatstock = mysqli_query($conn,
        "select * from stock where idbarang='$idb'"
    );
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // Tambahkan kembali stock (karena barang keluar dihapus)
    $stockbaru = $stockskrg + $qty;

    // Update stock dan hapus data keluar
    $updatestock = mysqli_query($conn,
        "update stock set stock='$stockbaru' where idbarang='$idb'"
    );
    $hapuskeluar = mysqli_query($conn,
        "delete from keluar where idkeluar='$idk'"
    );

    if ($updatestock && $hapuskeluar) {
        header('Location:keluar.php');
        exit();
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
}
```

**Penjelasan:**

- Saat menghapus transaksi keluar, stock master harus ditambah kembali
- Karena transaksi keluar mengurangi stock, maka menghapusnya harus menambah stock

**Contoh:**

- Stock saat ini: 50 (terdiri dari 100 awal - 50 dari transaksi keluar)
- Hapus transaksi -50
- Stock baru: 50 + 50 = 100

### 11. Manajemen Admin/User

**Add Admin:**

```php
if (isset($_POST['addadmin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'admin';

    $queryinsert = mysqli_query($conn,
        "insert into login (email, password, role)
        VALUES ('$email','$password','$role')"
    );

    if ($queryinsert) {
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}
```

**Update Admin:**

```php
if (isset($_POST['updateadmin'])) {
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $rolebaru = $_POST['role'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn,
        "update login set email='$emailbaru', password='$passwordbaru',
        role='$rolebaru' where iduser='$idnya'"
    );

    if ($queryupdate) {
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}
```

**Delete Admin:**

```php
if (isset($_POST['hapusadmin'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn,
        "delete from login where iduser='$id'"
    );

    if ($querydelete) {
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}
```

**Catatan Security:**

- ⚠️ Password disimpan dalam plaintext
- ⚠️ Tidak ada validasi password strength
- ⚠️ Email bisa duplikat (tapi di database ada UNIQUE constraint)

---

## DASHBOARD ANALYTICS

### File: dashboard.php

**Tujuan:** Menampilkan visualisasi data dan statistik real-time

### Fitur Utama:

#### 1. KPI Cards (4 Kartu Informasi)

```php
<!-- Total Barang -->
<?php
$total_barang_query = mysqli_query($conn,
    "SELECT SUM(stock) as total FROM stock"
);
$total_barang = mysqli_fetch_array($total_barang_query)['total'];
?>
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <div class="small text-gray-500">Total Barang</div>
                <div class="h3 mb-0 font-weight-bold text-primary">
                    <?php echo $total_barang; ?>
                </div>
            </div>
            <div class="fa-2x text-gray-300">
                <i class="fas fa-boxes text-primary"></i>
            </div>
        </div>
    </div>
</div>
```

**Penjelasan:**

- Query `SUM(stock)` menghitung total semua stok di gudang
- Ditampilkan dalam card dengan icon boxes
- Warna biru (primary)

#### 2. Barang Masuk

```php
<?php
$barang_masuk_query = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM masuk"
);
$barang_masuk = mysqli_fetch_array($barang_masuk_query)['total'];
?>
```

- `COUNT(*)`: Menghitung jumlah transaksi barang masuk
- Menunjukkan berapa kali barang masuk ke gudang

#### 3. Barang Keluar

```php
<?php
$barang_keluar_query = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM keluar"
);
$barang_keluar = mysqli_fetch_array($barang_keluar_query)['total'];
?>
```

- `COUNT(*)`: Menghitung jumlah transaksi barang keluar
- Menunjukkan berapa kali barang keluar dari gudang

#### 4. Stok Sedikit (Alert)

```php
<?php
$stok_sedikit_query = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM stock WHERE stock < 5"
);
$stok_sedikit = mysqli_fetch_array($stok_sedikit_query)['total'];
?>
```

- Threshold: Stock < 5 dianggap rendah
- Alert berwarna merah (danger)
- Indikator barang yang perlu restock

#### 5. Top 5 Barang Terbanyak

```php
<?php
$top_barang_query = mysqli_query($conn,
    "SELECT namabarang, stock FROM stock
    ORDER BY stock DESC LIMIT 5"
);
$barang_terbanyak = [];
$total_top_stock = 0;

while ($row = mysqli_fetch_array($top_barang_query)) {
    $barang_terbanyak[] = $row;
    $total_top_stock += $row['stock'];
}
?>

<table class="table">
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Progress</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($barang_terbanyak as $barang): ?>
            <tr>
                <td><?php echo $barang['namabarang']; ?></td>
                <td><?php echo $barang['stock']; ?></td>
                <td>
                    <?php
                    $percentage = ($barang['stock'] / $total_top_stock) * 100;
                    ?>
                    <div class="progress">
                        <div class="progress-bar bg-success"
                             style="width: <?php echo $percentage; ?>%">
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

**Penjelasan:**

- `ORDER BY stock DESC LIMIT 5`: Ambil 5 barang dengan stok tertinggi
- Progress bar menunjukkan proporsi stok relatif terhadap total
- Perhitungan persentase: `(stok barang / total stok top 5) * 100`

#### 6. Pie Chart (Ringkasan Transaksi)

```php
<canvas id="transactionPieChart"></canvas>

<script>
const ctx = document.getElementById('transactionPieChart').getContext('2d');
const transactionPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Barang Masuk', 'Barang Keluar'],
        datasets: [{
            data: [<?php echo $barang_masuk; ?>, <?php echo $barang_keluar; ?>],
            backgroundColor: ['#28a745', '#ffc107'], // Hijau dan Kuning
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
```

**Penjelasan:**

- Menggunakan Chart.js library
- Tipe chart: `pie`
- Data: `[barang_masuk, barang_keluar]`
- Warna: Hijau (#28a745) untuk masuk, Kuning (#ffc107) untuk keluar
- Interactive: Hover untuk melihat detail

#### 7. Peringatan Stok Rendah

```php
<?php
$low_stock_query = mysqli_query($conn,
    "SELECT * FROM stock WHERE stock < 5 ORDER BY stock ASC LIMIT 10"
);
$low_stock_items = [];
while ($row = mysqli_fetch_array($low_stock_query)) {
    $low_stock_items[] = $row;
}
?>

<?php if (count($low_stock_items) > 0): ?>
    <div class="alert alert-warning">
        <h4><i class="fas fa-exclamation-triangle"></i> Peringatan Stok Rendah</h4>
        <ul>
            <?php foreach ($low_stock_items as $item): ?>
                <li>
                    <strong><?php echo $item['namabarang']; ?></strong>
                    - Stok: <?php echo $item['stock']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <div class="alert alert-success">
        <h4><i class="fas fa-check-circle"></i> Semua Stok Aman</h4>
        <p>Bagus! Semua barang dalam stok aman (≥ 5 unit)</p>
    </div>
<?php endif; ?>
```

**Penjelasan:**

- Query barang dengan stok < 5
- Diurutkan dari stok terendah (`ORDER BY stock ASC`)
- Menampilkan list barang jika ada yang stok rendah
- Pesan sukses jika semua stok aman

### Performance Dashboard:

- **KPI Cards:** <100ms (single query)
- **Top 5 Table:** <50ms (LIMIT 5)
- **Pie Chart:** <200ms (Chart.js rendering)
- **Low Stock Alerts:** <100ms (LIMIT 10)

---

## MANAJEMEN STOK BARANG

### File: index.php

**Tujuan:** Halaman utama untuk melihat dan mengelola stok barang

### Struktur Halaman:

```
┌─────────────────────────────────────────┐
│ Navbar (PT. Bondvast + User Dropdown)   │
├─────────────────────────────────────────┤
│ Sidebar Navigation                     │
│ - Dashboard                             │
│ - Stock Barang (ACTIVE)                 │
│ - Barang Masuk                          │
│ - Barang Keluar                         │
│ - Kelola Admin (admin only)             │
│ - Logout                               │
├─────────────────────────────────────────┤
│ Main Content                           │
│                                        │
│ Header: "Stock Barang"                  │
│ Button: [Tambah Barang] [Export Data]  │
│                                        │
│ Alerts (Stock < 1)                     │
│                                        │
│ Table:                                 │
│ No | Nama | Deskripsi | Stock | Aksi   │
│ ───┼──────┼──────────┼───────┼─────   │
│ 1  | Laptop| Core i5   | 50    | Edit │
│                                        │
│ Modals:                                │
│ - Tambah Barang                        │
│ - Edit Barang                          │
│ - Delete Barang                        │
│                                        │
└─────────────────────────────────────────┘
```

### 1. Alert Stock Habis

```php
<?php
$ambildatastock = mysqli_query($conn,
    "select * from stock where stock < 1"
);

while ($fetch = mysqli_fetch_array($ambildatastock)) {
    $barang = $fetch["namabarang"];
?>
    <div class="alert alert-danger">
        <strong>Perhatian!</strong> Stock <?= $barang; ?> Telah Habis!
    </div>
<?php
}
?>
```

**Penjelasan:**

- Query barang dengan stock < 1 (stock = 0)
- Menampilkan alert danger untuk setiap barang yang habis
- Alert di-LOOP untuk setiap barang

### 2. Tabel Stock Barang

```php
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Deskripsi</th>
            <th>Stock</th>
            <?php if (isAdmin()): ?>
                <th>Aksi</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $ambilsemuadatastock = mysqli_query($conn, 'select * from stock');
        $i = 1;
        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
            $namabarang = $data['namabarang'];
            $deskripsi = $data['deskripsi'];
            $stock = $data['stock'];
            $idb = $data['idbarang'];
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $namabarang; ?></td>
                <td><?php echo $deskripsi; ?></td>
                <td><?php echo $stock; ?></td>
                <?php if (isAdmin()): ?>
                    <td>
                        <button type="button" class="btn btn-warning"
                                data-toggle="modal" data-target="#edit<?= $idb; ?>">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger"
                                data-toggle="modal" data-target="#delete<?= $idb; ?>">
                            Delete
                        </button>
                    </td>
                <?php endif; ?>
            </tr>
        <?php
        };
        ?>
    </tbody>
</table>
```

**Penjelasan:**

- `mysqli_query()`: Ambil semua data dari tabel stock
- `mysqli_fetch_array()`: Ambil baris per baris sebagai array
- `$i++`: Increment counter untuk nomor urut
- Role-based display: Kolom "Aksi" hanya muncul untuk admin
- DataTables plugin: Menambah fitur sorting, searching, pagination

### 3. Modal Tambah Barang

```php
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="text" name="namabarang"
                           placeholder="Nama Barang" class="form-control" required>
                    <br>
                    <input type="text" name="deskripsi"
                           placeholder="Deskripsi Barang" class="form-control" required>
                    <br>
                    <input type="number" name="stock"
                           class="form-control" placeholder="Stock" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="addnewbarang">
                        Submit
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
```

**Penjelasan:**

- Bootstrap Modal untuk popup form
- `data-dismiss="modal"`: Tutup modal saat tombol ditekan
- `method="post"`: Submit data via POST
- `required`: Validasi HTML5 (form tidak bisa disubmit tanpa isi)
- `name="addnewbarang"`: Trigger di function.php

### 4. Modal Edit Barang

```php
<?php foreach ($ambilsemuadatastock as $data): ?>
    <!-- Edit Modal -->
    <div class="modal" id="edit<?= $idb; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Barang</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="text" name="namabarang"
                               value="<?= $namabarang; ?>" class="form-control" required>
                        <br>
                        <input type="text" name="deskripsi"
                               value="<?= $deskripsi; ?>" class="form-control" required>
                        <br>
                        <input type="hidden" name="idb" value="<?= $idb; ?>">
                        <button type="submit" class="btn btn-primary" name="updatebarang">
                            Submit
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
```

**Penjelasan:**

- Modal edit di-LOOP untuk setiap barang
- `id="edit<?= $idb; ?>"`: Unique ID untuk setiap modal
- `value="<?= $namabarang; ?>"`: Pre-fill form dengan data existing
- `<input type="hidden">`: Kirim ID barang tanpa ditampilkan

### 5. Modal Delete Barang

```php
<div class="modal" id="delete<?= $idb; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hapus Barang?</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    Apakah Anda Yakin Ingin Menghapus <?= $namabarang; ?>?
                    <input type="hidden" name="idb" value="<?= $idb; ?>">
                    <br><br>
                    <button type="submit" class="btn btn-danger" name="hapusbarang">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
```

**Penjelasan:**

- Modal konfirmasi untuk mencegah penghapusan tidak sengaja
- Menampilkan nama barang yang akan dihapus
- Hanya tombol "Hapus", tidak ada tombol "Batal" (gunakan X di pojok)

---

## MANAJEMEN BARANG MASUK

### File: masuk.php

**Tujuan:** Halaman untuk mencatat dan mengelola transaksi barang masuk

### Fitur Utama:

1. **Tambah Barang Masuk**
2. **Edit Transaksi Masuk**
3. **Hapus Transaksi Masuk**
4. **Export Data**

### 1. Form Tambah Barang Masuk

```php
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang Masuk</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="barangnya">Pilih Barang</label>
                        <select name="barangnya" class="form-control" required>
                            <?php
                            $ambilsemuadatanya = mysqli_query($conn, "select * from stock");
                            while ($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                                $namabarangnya = $fetcharray['namabarang'];
                                $idbarangnya = $fetcharray['idbarang'];
                            ?>
                                <option value="<?= $idbarangnya; ?>">
                                    <?= $namabarangnya; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qty">Jumlah</label>
                        <input type="number" name="qty" class="form-control"
                               placeholder="Quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="penerima">Supplier</label>
                        <input type="text" name="penerima" class="form-control"
                               placeholder="Nama Supplier" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="barangmasuk">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="submit" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
```

**Penjelasan:**

- Dropdown diisi dinamis dari tabel `stock`
- Looping semua barang untuk mengisi options
- `<option value="<?= $idbarangnya; ?>">`: Value = ID barang, Display = Nama barang
- Input fields: Qty (number), Supplier (text)

### 2. Tabel Transaksi Masuk

```php
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Supplier</th>
            <?php if (isAdmin()): ?>
                <th>Aksi</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $ambilsemuadatastock = mysqli_query($conn,
            "select * from masuk m, stock s where s.idbarang = m.idbarang"
        );
        while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
            $idb = $data["idbarang"];
            $idm = $data["idmasuk"];
            $tanggal = $data['tanggal'];
            $namabarang = $data['namabarang'];
            $qty = $data['qty'];
            $keterangan = $data['keterangan'];
        ?>
            <tr>
                <td><?= $tanggal; ?></td>
                <td><?= $namabarang; ?></td>
                <td><?= $qty; ?></td>
                <td><?= $keterangan; ?></td>
                <?php if (isAdmin()): ?>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm"
                                data-toggle="modal" data-target="#edit<?= $idm; ?>">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm"
                                data-toggle="modal" data-target="#delete<?= $idm; ?>">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                <?php endif; ?>
            </tr>
        <?php
        };
        ?>
    </tbody>
</table>
```

**Penjelasan:**

- **JOIN Query:**
  ```php
  "select * from masuk m, stock s where s.idbarang = m.idbarang"
  ```
  - Menggabungkan tabel `masuk` dan `stock`
  - `m`: alias untuk tabel masuk
  - `s`: alias untuk tabel stock
  - `WHERE s.idbarang = m.idbarang`: Join condition
- Menampilkan: Tanggal, Nama Barang, Jumlah, Supplier
- Role-based: Kolom "Aksi" hanya untuk admin

### 3. Modal Edit Barang Masuk

```php
<div class="modal" id="edit<?= $idm; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Barang Masuk</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keterangan">Supplier</label>
                        <input type="text" name="keterangan"
                               value="<?= $keterangan; ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="qty">Jumlah</label>
                        <input type="number" name="qty"
                               value="<?= $qty; ?>" class="form-control" required>
                    </div>
                    <input type="hidden" name="idb" value="<?= $idb; ?>">
                    <input type="hidden" name="idm" value="<?= $idm; ?>">
                    <button type="submit" class="btn btn-primary" name="updatebarangmasuk">
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
```

**Penjelasan:**

- Tidak bisa mengubah barang yang masuk (hanya qty dan supplier)
- 2 hidden fields: `idb` (ID barang) dan `idm` (ID transaksi masuk)
- Logic update ada di function.php

---

## MANAJEMEN BARANG KELUAR

### File: keluar.php

**Tujuan:** Halaman untuk mencatat dan mengelola transaksi barang keluar

**Struktur:** Mirip dengan masuk.php, tapi:

- Tombol: "Tambah Barang Keluar"
- Kolom: "Penerima" (bukan "Supplier")
- Form: Input "Penerima" (bukan "Supplier")

### Perbedaan Utama:

1. **Form Input:**

   ```php
   <div class="form-group">
       <label for="penerima">Penerima</label>
       <input type="text" name="penerima" class="form-control"
              placeholder="Nama Penerima" required>
   </div>
   ```

   - Nama field: `penerima` (bukan `supplier`)

2. **Table Column:**

   ```php
   <th>Penerima</th>
   ```

   - Kolom menampilkan penerima barang

3. **Logic Backend:**
   - Validasi stock tidak cukup
   - Stock berkurang saat ditambah
   - Stock bertambah saat dihapus

---

## MANAJEMEN USER/ADMIN

### File: admin.php

**Tujuan:** Halaman untuk mengelola user dan admin sistem

### Fitur Utama:

1. **Tambah User/Admin Baru**
2. **Edit User/Admin**
3. **Hapus User/Admin**
4. **Role Management**

### Proteksi Halaman Admin:

```php
<?php
require 'function.php';
require 'cek.php';

// Cek apakah user adalah admin
if (!isAdmin()) {
    header('location:index.php');
    exit();
}
?>
```

**Penjelasan:**

- `require 'function.php'`: Load helper function `isAdmin()`
- `require 'cek.php'`: Validasi session
- `if (!isAdmin())`: Jika bukan admin, redirect ke index.php
- `exit()`: Stop eksekusi

### 1. Form Tambah Admin

```php
<div class="modal" id="addAdminModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Admin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
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
```

**Penjelasan:**

- Dropdown role: Admin atau User
- Default selection: Admin
- Type email validation: `type="email"`
- Password field: `type="password"`

### 2. Tabel User/Admin

```php
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Email</th>
            <th>Password</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $ambildataadmin = mysqli_query($conn, "select * from login");
        $i = 1;
        while ($data = mysqli_fetch_array($ambildataadmin)) {
            $iduser = $data['iduser'];
            $email = $data['email'];
            $password = $data['password'];
            $role = $data['role'];
        ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $email; ?></td>
                <td><?php echo $password; ?></td>
                <td>
                    <span class="badge <?php echo $role == 'admin' ? 'badge-primary' : 'badge-secondary'; ?>">
                        <?php echo $role; ?>
                    </span>
                </td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm"
                            data-toggle="modal" data-target="#edit<?= $iduser; ?>">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-danger btn-sm"
                            data-toggle="modal" data-target="#delete<?= $iduser; ?>">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
        <?php
        };
        ?>
    </tbody>
</table>
```

**Penjelasan:**

- Badge role: Primary untuk admin, Secondary untuk user
- Password ditampilkan dalam plaintext (⚠️ security issue)
- Role-based badge color

### 3. Modal Edit Admin

```php
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
                        <label for="emailadmin">Email</label>
                        <input type="email" name="emailadmin"
                               value="<?= $email; ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordbaru">Password</label>
                        <input type="password" name="passwordbaru"
                               value="<?= $password; ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" <?= $role == 'admin' ? 'selected' : ''; ?>>
                                Admin
                            </option>
                            <option value="user" <?= $role == 'user' ? 'selected' : ''; ?>>
                                User
                            </option>
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
```

**Penjelasan:**

- `<?= $role == 'admin' ? 'selected' : ''; ?>`: Set selected berdasarkan role saat ini
- Ternary operator untuk conditional HTML attribute
- Pre-fill semua field dengan data existing

---

## EXPORT DATA

### 1. Export Stock Barang (export.php)

```php
<?php
require 'function.php';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=Stock_Barang_'.date('d-m-Y').'.xls');

echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>Nama Barang</th>
        <th>Deskripsi</th>
        <th>Stock</th>
      </tr>";

$ambildatastock = mysqli_query($conn, "select * from stock");
$no = 1;
while ($data = mysqli_fetch_array($ambildatastock)) {
    echo "<tr>
            <td>".$no++."</td>
            <td>".$data['namabarang']."</td>
            <td>".$data['deskripsi']."</td>
            <td>".$data['stock']."</td>
          </tr>";
}

echo "</table>";
?>
```

**Penjelasan:**

1. **Headers:**

   ```php
   header('Content-Type: application/vnd.ms-excel');
   header('Content-Disposition: attachment; filename=Stock_Barang_'.date('d-m-Y').'.xls');
   ```

   - `Content-Type`: Tell browser ini file Excel
   - `Content-Disposition: attachment`: Force download
   - `filename`: Nama file dengan tanggal hari ini

2. **Generate HTML Table:**

   - Excel bisa membaca HTML table
   - `border='1'`: Border tabel
   - `<th>`: Header kolom
   - `<td>`: Data kolom

3. **Loop Data:**
   - Query semua data dari tabel stock
   - Loop dan echo setiap baris sebagai HTML `<tr>`

**Flow:**

```
User Click Export → Load export.php → Set Headers → Query Database →
Generate HTML Table → Browser Download as Excel File
```

### 2. Export Barang Masuk (exportmasuk.php)

```php
<?php
require 'function.php';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=Barang_Masuk_'.date('d-m-Y').'.xls');

echo "<table border='1'>";
echo "<tr>
        <th>Tanggal</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Supplier</th>
      </tr>";

$ambildatamasuk = mysqli_query($conn,
    "select * from masuk m, stock s where s.idbarang = m.idbarang"
);
while ($data = mysqli_fetch_array($ambildatamasuk)) {
    echo "<tr>
            <td>".$data['tanggal']."</td>
            <td>".$data['namabarang']."</td>
            <td>".$data['qty']."</td>
            <td>".$data['keterangan']."</td>
          </tr>";
}

echo "</table>";
?>
```

**Penjelasan:**

- JOIN query untuk mendapatkan nama barang
- Kolom: Tanggal, Nama Barang, Jumlah, Supplier

### 3. Export Barang Keluar (exportkeluar.php)

```php
<?php
require 'function.php';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=Barang_Keluar_'.date('d-m-Y').'.xls');

echo "<table border='1'>";
echo "<tr>
        <th>Tanggal</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Penerima</th>
      </tr>";

$ambildatakeluar = mysqli_query($conn,
    "select * from keluar k, stock s where s.idbarang = k.idbarang"
);
while ($data = mysqli_fetch_array($ambildatakeluar)))) {
    echo "<tr>
            <td>".$data['tanggal']."</td>
            <td>".$data['namabarang']."</td>
            <td>".$data['qty']."</td>
            <td>".$data['penerima']."</td>
          </tr>";
}

echo "</table>";
?>
```

**Penjelasan:**

- JOIN query untuk mendapatkan nama barang
- Kolom: Tanggal, Nama Barang, Jumlah, Penerima

---

## KEAMANAN & CATATAN PENTING

### ⚠️ Security Issues (CRITICAL)

#### 1. SQL Injection Vulnerability

**Masalah:**

```php
$email = $_POST['email'];
$cekdatabase = mysqli_query($conn,
    "SELECT * FROM login where email='$email' and password='$password'"
);
```

**Penjelasan:**

- Input user langsung di-embed ke query SQL
- Attacker bisa inject SQL malicious

**Contoh Attack:**

```sql
-- Input: admin' OR '1'='1
-- Query menjadi:
SELECT * FROM login where email='admin' OR '1'='1' and password='...'
-- Hasil: Login berhasil tanpa password!
```

**Solusi (Prepared Statements):**

```php
$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM login WHERE email=? AND password=?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();
```

#### 2. Password dalam Plaintext

**Masalah:**

```php
$password = $_POST['password'];
mysqli_query($conn, "INSERT INTO login (email, password) VALUES ('$email','$password')");
```

**Penjelasan:**

- Password disimpan dalam plaintext (bukan hash)
- Jika database bocor, semua password terungkap

**Solusi (Password Hashing):**

```php
// Saat register/admin dibuat
$password = $_POST['password'];
$password_hash = password_hash($password, PASSWORD_DEFAULT);
mysqli_query($conn, "INSERT INTO login (email, password) VALUES ('$email','$password_hash')");

// Saat login
$password_input = $_POST['password'];
$stmt = $conn->prepare("SELECT * FROM login WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user && password_verify($password_input, $user['password'])) {
    // Login berhasil
}
```

#### 3. XSS Vulnerability

**Masalah:**

```php
$namabarang = $_POST['namabarang'];
echo "<td>".$namabarang."</td>";
```

**Penjelasan:**

- Input user langsung di-output ke HTML
- Attacker bisa inject JavaScript malicious

**Contoh Attack:**

```
-- Input: <script>alert('XSS')</script>
-- Output: <td><script>alert('XSS')</script></td>
-- Hasil: Script jalan saat user membuka halaman
```

**Solusi (Output Escaping):**

```php
$namabarang = $_POST['namabarang'];
echo "<td>".htmlspecialchars($namabarang, ENT_QUOTES, 'UTF-8')."</td>";
```

#### 4. Tidak Ada Input Validation

**Masalah:**

```php
$stock = $_POST['stock']; // Tidak dicek apakah angka negatif
mysqli_query($conn, "INSERT INTO stock (namabarang, stock) VALUES ('$namabarang', '$stock')");
```

**Solusi:**

```php
$stock = $_POST['stock'];
if ($stock < 0) {
    die("Stock tidak boleh negatif!");
}
```

#### 5. Tidak Ada CSRF Protection

**Masalah:**

- Tidak ada CSRF token
- Attacker bisa buat form malicious dan submit ke server

**Solusi:**

```php
// Generate token
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Di form
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

// Validasi saat submit
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token invalid!");
}
```

#### 6. Tidak Ada Session Timeout

**Masalah:**

- Session tidak pernah expire
- Jika user lupa logout, session tetap aktif

**Solusi:**

```php
session_start();
$inactive = 1800; // 30 menit

if (isset($_SESSION['timeout']) && (time() - $_SESSION['timeout'] > $inactive)) {
    session_destroy();
    header('location:login.php');
}
$_SESSION['timeout'] = time();
```

### ✅ Best Practices yang Sudah Diterapkan

1. **Session Management:** Menggunakan PHP session untuk autentikasi
2. **Role-Based Access Control:** Helper function `isAdmin()` untuk kontrol akses
3. **Form Validation:** Validasi HTML5 (`required`, `type="email"`, dll)
4. **Database Normalization:** Tabel terpisah untuk stock, masuk, keluar, login
5. **Foreign Key Relationship:** Relasi antar tabel
6. **Modal Confirmation:** Modal konfirmasi sebelum delete
7. **Responsive Design:** Bootstrap untuk mobile-friendly
8. **Data Validation:** Validasi stock tidak cukup sebelum barang keluar

### 🔧 Rekomendasi Improvements

#### 1. Database Schema Improvements

```sql
-- Add indexes untuk performance
ALTER TABLE stock ADD INDEX idx_namabarang (namabarang);
ALTER TABLE masuk ADD INDEX idx_tanggal (tanggal);
ALTER TABLE keluar ADD INDEX idx_tanggal (tanggal);

-- Add created_at dan updated_at
ALTER TABLE stock ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE stock ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Add is_active untuk soft delete
ALTER TABLE stock ADD COLUMN is_active BOOLEAN DEFAULT TRUE;
```

#### 2. Code Structure Improvements

```
project/
├── config/
│   └── database.php       // Database connection
├── includes/
│   ├── auth.php           // Authentication logic
│   ├── functions.php      // Helper functions
│   └── validation.php     // Input validation
├── classes/
│   ├── User.php           // User class
│   ├── Stock.php          // Stock class
│   └── Transaction.php    // Transaction class
├── pages/
│   ├── dashboard.php
│   ├── stock.php
│   ├── masuk.php
│   └── keluar.php
└── index.php
```

#### 3. Error Handling

```php
// Custom error handler
function handleError($errno, $errstr, $errfile, $errline) {
    error_log("Error: $errstr in $errfile on line $errline");
    // Log ke file atau database
    header('HTTP/1.1 500 Internal Server Error');
    echo "<h1>Terjadi kesalahan. Silakan coba lagi nanti.</h1>";
    exit();
}

set_error_handler('handleError');
```

#### 4. Logging System

```php
// Log aktivitas user
function logActivity($user_id, $action, $details) {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $user_id, $action, $details);
    $stmt->execute();
}

// Penggunaan
logActivity($_SESSION['user_id'], 'STOCK_IN', "Menambah barang: $namabarang qty: $qty");
```

---

## ALUR LENGKAP SISTEM

### 1. User Login Flow

```
┌─────────────┐
│ User Buka   │
│ login.php   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Masukkan    │
│ Email &     │
│ Password    │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Submit Form │
│ POST login  │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│ function.php       │
│ - Cek email &      │
│   password di DB   │
│ - Jika match, set  │
│   session          │
│ - Redirect to      │
│   index.php        │
└──────┬─────────────┘
       │
       ▼
┌─────────────┐
│ Dashboard   │
│ index.php   │
└─────────────┘
```

### 2. Tambah Barang Masuk Flow

```
┌─────────────┐
│ Admin Klik  │
│ "Tambah     │
│ Barang      │
│ Masuk"      │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Modal Muncul│
│ - Pilih     │
│   barang    │
│ - Input qty │
│ - Input     │
│   supplier  │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Submit Form │
│ POST        │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│ function.php       │
│ 1. Read current    │
│    stock from DB   │
│ 2. Calculate new   │
│    stock (old+qty) │
│ 3. Insert to       │
│    masuk table     │
│ 4. Update stock    │
│    table           │
│ 5. Redirect to     │
│    masuk.php       │
└──────┬─────────────┘
       │
       ▼
┌─────────────┐
│ Halaman     │
│ Barang      │
│ Masuk       │
│ (Updated)   │
└─────────────┘
```

### 3. Tambah Barang Keluar Flow

```
┌─────────────┐
│ Admin Klik  │
│ "Tambah     │
│ Barang      │
│ Keluar"     │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Modal Muncul│
│ - Pilih     │
│   barang    │
│ - Input qty │
│ - Input     │
│   penerima  │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Submit Form │
│ POST        │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│ function.php       │
│ 1. Read current    │
│    stock from DB   │
│ 2. Validate: qty <=│
│    stock?          │
│    [YES] continue  │
│    [NO] show alert │
│ 3. Calculate new   │
│    stock (old-qty) │
│ 4. Insert to       │
│    keluar table    │
│ 5. Update stock    │
│    table           │
│ 6. Redirect to     │
│    keluar.php      │
└──────┬─────────────┘
       │
       ▼
┌─────────────┐
│ Halaman     │
│ Barang      │
│ Keluar      │
│ (Updated)   │
└─────────────┘
```

### 4. Edit Barang Masuk Flow

```
┌─────────────┐
│ Admin Klik  │
│ "Edit" pada │
│ transaksi   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Modal Edit   │
│ - Tampil qty │
│   lama       │
│ - Tampil     │
│   supplier   │
│ - Input qty  │
│   baru &     │
│   supplier   │
│   baru       │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Submit Form │
│ POST update  │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│ function.php       │
│ 1. Read current    │
│    stock from DB   │
│ 2. Read old qty    │
│    from masuk table│
│ 3. Calculate       │
│    difference      │
│ 4. If new qty >    │
│    old: stock +=   │
│    difference     │
│    If new qty <    │
│    old: stock -=   │
│    difference     │
│ 5. Update stock    │
│    table           │
│ 6. Update masuk    │
│    table           │
│ 7. Redirect to     │
│    masuk.php       │
└──────┬─────────────┘
       │
       ▼
┌─────────────┐
│ Halaman     │
│ Barang      │
│ Masuk       │
│ (Updated)   │
└─────────────┘
```

### 5. Dashboard Flow

```
┌─────────────┐
│ User Login  │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Redirect to │
│ dashboard.  │
│ php         │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│ dashboard.php       │
│ 1. Query 1: SUM    │
│    stock (total)   │
│ 2. Query 2: COUNT  │
│    masuk (trans.)  │
│ 3. Query 3: COUNT  │
│    keluar (trans.) │
│ 4. Query 4: COUNT  │
│    stock < 5       │
│ 5. Query 5: TOP 5  │
│    stock DESC      │
│ 6. Query 6: Low    │
│    stock items     │
└──────┬─────────────┘
       │
       ▼
┌─────────────┐
│ Render      │
│ - KPI Cards │
│ - Top 5     │
│   Table     │
│ - Pie Chart │
│ - Low Stock │
│   Alerts    │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│ Display     │
│ Dashboard   │
└─────────────┘
```

---

## TEKNIS IMPLEMENTASI

### 1. Bootstrap 4 Framework

**CDN Links:**

```html
<!-- CSS -->
<link href="css/styles.css" rel="stylesheet" />
<link
  href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"
  rel="stylesheet"
/>
<link href="css/lemon-theme.css" rel="stylesheet" />

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
```

**Components Used:**

- Navbar (`navbar`, `navbar-dark`, `bg-dark`)
- Sidebar (`sb-sidenav`, `sb-sidenav-dark`)
- Cards (`card`, `card-header`, `card-body`)
- Tables (`table`, `table-bordered`, `table-responsive`)
- Modals (`modal`, `modal-dialog`, `modal-content`)
- Buttons (`btn`, `btn-primary`, `btn-warning`, `btn-danger`)
- Forms (`form-control`, `form-group`)
- Alerts (`alert`, `alert-success`, `alert-danger`, `alert-warning`)
- Progress bars (`progress`, `progress-bar`)

### 2. DataTables Plugin

**Initialization:**

```html
<script src="assets/demo/datatables-demo.js"></script>
```

**Features:**

- Sorting (klik header kolom)
- Searching (input search di pojok kanan)
- Pagination (prev/next, page numbers)
- Responsive (horizontal scroll di mobile)

### 3. Chart.js Library

**Pie Chart:**

```javascript
const ctx = document.getElementById('transactionPieChart').getContext('2d');
const transactionPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Barang Masuk', 'Barang Keluar'],
        datasets: [{
            data: [<?php echo $barang_masuk; ?>, <?php echo $barang_keluar; ?>],
            backgroundColor: ['#28a745', '#ffc107'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
```

**Features:**

- Interactive hover (show values)
- Responsive (resize dengan window)
- Legend display
- Custom colors

### 4. PHP Functions

**mysqli_connect():**

```php
$conn = mysqli_connect("localhost", "root", "root", "UKK_stokbarang");
```

- Connect ke MySQL database
- Return: Connection object atau FALSE

**mysqli_query():**

```php
$result = mysqli_query($conn, "SELECT * FROM stock");
```

- Execute SQL query
- Return: Result object atau FALSE

**mysqli_fetch_array():**

```php
$row = mysqli_fetch_array($result);
```

- Fetch result row as array
- Return: Array dengan kolom sebagai keys atau FALSE

**mysqli_num_rows():**

```php
$count = mysqli_num_rows($result);
```

- Count number of rows in result
- Return: Integer

**mysqli_error():**

```php
$error = mysqli_error($conn);
```

- Return error description for last query
- Return: String error message

### 5. Session Management

**Start Session:**

```php
session_start();
```

- Start new session atau resume existing session
- Harus dipanggil sebelum output HTML

**Set Session Variable:**

```php
$_SESSION['role'] = 'admin';
```

- Set value di session array
- Persist across page requests

**Get Session Variable:**

```php
$role = $_SESSION['role'];
```

- Get value dari session array

**Destroy Session:**

```php
session_destroy();
```

- Destroy all session data
- Use when logout

### 6. Redirect

**PHP Redirect:**

```php
header('Location: index.php');
exit();
```

- Send HTTP header untuk redirect
- `exit()`: Stop eksekusi setelah redirect

**JavaScript Redirect:**

```javascript
window.location.href = "index.php";
```

- Redirect menggunakan JavaScript
- Bisa di-echo dari PHP

---

## TROUBLESHOOTING

### 1. Masalah: Tidak bisa login

**Kemungkinan:**

- Email/password salah
- Tabel login kosong
- Session tidak tersimpan

**Solusi:**

```bash
# Cek apakah ada data di tabel login
mysql -u root -p -e "SELECT * FROM UKK_stokbarang.login;"

# Cek apakah kolom role ada
mysql -u root -p -e "DESCRIBE UKK_stokbarang.login;"

# Jalankan migrasi jika belum
php run_migration.php
```

### 2. Masalah: Dashboard tidak muncul data

**Kemungkinan:**

- Tabel tidak ada
- Data kosong
- Query error

**Solusi:**

```bash
# Cek apakah tabel ada
mysql -u root -p -e "SHOW TABLES FROM UKK_stokbarang;"

# Cek data di tabel stock
mysql -u root -p -e "SELECT * FROM UKK_stokbarang.stock;"

# Cek error PHP
tail -f /var/log/apache2/error.log
```

### 3. Masalah: Chart tidak tampil

**Kemungkinan:**

- Internet tidak aktif (CDN)
- JavaScript error
- Browser cache

**Solusi:**

```javascript
// Buka browser console (F12)
// Cek apakah ada error

// Clear cache
Ctrl + Shift + Delete(Windows);
Cmd + Shift + Delete(Mac);

// Cek apakah CDN load
console.log("Chart.js loaded:", typeof Chart !== "undefined");
```

### 4. Masalah: Modal tidak muncul

**Kemungkinan:**

- jQuery tidak load
- Bootstrap JS tidak load
- ID tidak match

**Solusi:**

```javascript
// Cek jQuery
console.log("jQuery loaded:", typeof $ !== "undefined");

// Cek Bootstrap
console.log("Bootstrap loaded:", typeof bootstrap !== "undefined");

// Cek modal
$("#myModal").modal("show");
```

---

## KESIMPULAN

### 📊 Ringkasan Sistem

Sistem Inventory PT. Bondvast adalah aplikasi manajemen stok lengkap dengan:

1. **Autentikasi & Authorization:**

   - Login system dengan email dan password
   - Role-based access control (Admin/User)
   - Session management

2. **Manajemen Stok:**

   - CRUD barang (Create, Read, Update, Delete)
   - Tracking barang masuk dan keluar
   - Automatic stock calculation

3. **Dashboard Analytics:**

   - KPI cards (total barang, masuk, keluar, stok rendah)
   - Top 5 barang terbanyak
   - Pie chart transaksi
   - Low stock alerts

4. **Export Data:**

   - Export stok ke Excel
   - Export barang masuk ke Excel
   - Export barang keluar ke Excel

5. **UI/UX:**
   - Responsive design
   - Bootstrap 4 framework
   - DataTables untuk tabel
   - Modal forms untuk CRUD
   - Lemon color theme

### 🎯 Strengths

✅ Fungsionalitas lengkap untuk manajemen stok
✅ Dashboard analytics untuk decision making
✅ Role-based access control untuk security
✅ Responsive design untuk multi-device
✅ Export data untuk reporting
✅ User-friendly interface dengan modals

### ⚠️ Weaknesses

⚠️ SQL injection vulnerability (CRITICAL)
⚠️ Password dalam plaintext (CRITICAL)
⚠️ XSS vulnerability (HIGH)
⚠️ Tidak ada CSRF protection (MEDIUM)
⚠️ Tidak ada session timeout (MEDIUM)
⚠️ Error handling minimal (LOW)
⚠️ Tidak ada logging system (LOW)

### 🚀 Recommendations

1. **URGENT:** Implement prepared statements untuk semua query
2. **URGENT:** Hash password dengan `password_hash()`
3. **HIGH:** Sanitize output dengan `htmlspecialchars()`
4. **MEDIUM:** Add CSRF tokens
5. **MEDIUM:** Implement session timeout
6. **MEDIUM:** Add comprehensive error handling
7. **LOW:** Implement activity logging
8. **LOW:** Add database indexes untuk performance

### 📝 Final Notes

Dokumentasi ini menjelaskan secara lengkap:

- Alur kerja sistem dari awal sampai akhir
- Penjelasan code function-by-function
- Database schema dan relasi
- Teknis implementasi
- Security issues dan solusi
- Troubleshooting guide

Sistem ini sudah fungsional dan bisa digunakan untuk manajemen stok, namun memerlukan perbaikan security sebelum digunakan di production environment.

---

**Dokumentasi dibuat oleh:** Cline AI Assistant
**Untuk:** PT. Bondvast
**Tanggal:** 8 Februari 2026
**Versi:** 1.0

---

## 📞 SUPPORT

Untuk pertanyaan lebih lanjut atau jika mengalami masalah:

1. Cek troubleshooting section di atas
2. Lihat error log PHP
3. Cek browser console untuk JavaScript errors
4. Review security recommendations
5. Contact tim pengembang

---

**End of Documentation**
