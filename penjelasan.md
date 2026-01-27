# PENJELASAN LENGKAP ALUR WEBSITE STOK BARANG PT. BONDVAST

## **1. OVERVIEW SISTEM**

Website ini adalah **Sistem Informasi Manajemen Stok Barang** yang dibangun menggunakan PHP dan MySQL. Sistem ini dirancang untuk mengelola pergerakan barang masuk dan keluar, monitoring stok real-time, dan generate laporan. Nama perusahaan adalah PT. Bondvast.

## **2. STRUKTUR DATABASE & KONEKSI**

### **File: function.php**

```php
session_start();
$conn = mysqli_connect("localhost", "root", "root", "UKK_stokbarang");
```

**Penjelasan:**

- `session_start()` = Memulai session untuk menyimpan data login user
- `mysqli_connect()` = Membuat koneksi ke database MySQL dengan:
  - Host: localhost
  - Username: root
  - Password: root
  - Database: UKK_stokbarang

**Mengapa ini penting?** Koneksi database adalah fondasi utama agar semua operasi CRUD (Create, Read, Update, Delete) dapat berjalan. Tanpa koneksi, website tidak bisa berkomunikasi dengan database.

## **3. SISTEM AUTENTIKASI & KEAMANAN**

### **File: login.php**

```php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cekdatabase = mysqli_query($conn, "SELECT * FROM login where email='$email' and password='$password'");
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        $_SESSION['log'] = 'True';
        header('location:index.php');
    } else {
        header('location:login.php');
    }
}
```

**Penjelasan Detail:**

1. **Proses Login:**

   - User memasukkan email dan password
   - Sistem mencocokkan data dengan tabel `login` di database
   - `mysqli_num_rows()` menghitung jumlah data yang cocok
   - Jika ada data (>0), session `log` diset 'True' dan redirect ke `index.php`
   - Jika tidak ada data, kembali ke halaman login

2. **Desain Modern:**
   - Menggunakan CSS animations dan gradient effects
   - Responsive design untuk mobile dan desktop
   - Floating animation pada logo
   - Background pattern yang bergerak

### **File: cek.php**

```php
if (isset($_SESSION['log'])) {
 } else{
    header('location:login.php');
}
```

**Penjelasan:**
Ini adalah **security middleware** yang dipanggil di setiap halaman:

- Mengecek apakah user sudah login (session `log` ada)
- Jika belum login, redirect ke halaman login
- Mencegah akses langsung ke halaman tanpa login

**Mengapa ini penting?** Untuk melindungi data sensitif dari akses orang yang tidak berwenang.

### **File: logout.php**

```php
session_start();
session_destroy();
header('location:login.php');
```

**Penjelasan:**

- `session_destroy()` menghapus semua session data
- User di-redirect ke halaman login
- Ini adalah proses logout yang aman

## **4. HALAMAN UTAMA DASHBOARD (index.php)**

### **Fungsi Utama:**

1. **Menampilkan Stok Real-time:**

```php
$ambildatastock = mysqli_query($conn, "select * from stock where stock < 1");
while ($fetch = mysqli_fetch_array($ambildatastock)) {
    $barang = $fetch["namabarang"];
?>
    <div class="alert alert-danger">
        <strong>Perhatian!</strong> Stock <?= $barang; ?> Telah Habis!
    </div>
<?php
}
```

**Penjelasan:**

- Sistem memeriksa stok yang kurang dari 1 (habis)
- Menampilkan alert peringatan otomatis
- Ini adalah **early warning system** untuk manajemen

2. **CRUD Operations:**

   - **Create:** Modal tambah barang baru
   - **Read:** Tabel menampilkan semua data stok
   - **Update:** Modal edit untuk setiap barang
   - **Delete:** Modal konfirmasi hapus

3. **Navigation Menu:**
   - Stock Barang (Dashboard)
   - Barang Masuk
   - Barang Keluar
   - Kelola Admin
   - Logout

## **5. SISTEM BARANG MASUK (masuk.php)**

### **Flow Process:**

1. **Input Data:**

   - Pilih barang dari dropdown (diambil dari tabel stock)
   - Masukkan quantity
   - Masukkan nama supplier

2. **Proses Backend (function.php):**

```php
if (isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];
    $tanggal = date('Y-m-d');

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, tanggal, keterangan, qty) VALUES ('$barangnya', '$tanggal', '$penerima', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
}
```

**Penjelasan Detail:**

1. **Cek Stok Saat Ini:** Query ke tabel stock untuk mendapatkan jumlah stok terkini
2. **Hitung Stok Baru:** Stok lama + quantity masuk
3. **Insert ke Tabel Masuk:** Mencatat transaksi barang masuk dengan timestamp
4. **Update Tabel Stock:** Mengupdate jumlah stok terbaru

**Mengapa alur ini penting?** Karena setiap barang masuk harus:

- Tercatat di log transaksi (tabel masuk)
- Mengupdate stok real-time (tabel stock)
- Memiliki audit trail untuk tracking

## **6. SISTEM BARANG KELUAR (keluar.php)**

### **Flow Process dengan Validasi:**

```php
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $stocksekarang = $ambildatanya['stock'];

    // Validasi: cek apakah stok mencukupi
    if ($qty > $stocksekarang) {
        echo "<script>alert('Stok tidak mencukupi! Stok tersedia: $stocksekarang'); window.location.href='keluar.php';</script>";
        exit();
    }

    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;
    // ... lanjutan proses
}
```

**Penjelasan Critical Point:**

1. **Validasi Stok:** Sistem akan menolak transaksi jika quantity > stok tersedia
2. **Alert System:** Menampilkan pesan error dengan informasi stok tersedia
3. **Prevent Negative Stock:** Mencegah stok menjadi negatif

**Mengapa ini vital?** Untuk menjaga integritas data dan mencegah overselling atau pengeluaran barang yang tidak valid.

## **7. SISTEM LAPORAN & EXPORT**

### **File: export.php**

```php
<script>
$(document).ready(function() {
    $('#mauexport').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf', 'print'
        ]
    });
});
</script>
```

**Fitur Export:**

- **Excel:** Export ke format .xlsx
- **PDF:** Generate PDF report
- **Print:** Cetak langsung
- **DataTables:** Sorting, searching, pagination otomatis

**Mengapa ini penting untuk laporan?**

- Memberikan fleksibilitas format output
- Memudahkan sharing data ke management
- Standar laporan keuangan/inventaris

## **8. SISTEM MANAJEMEN ADMIN (admin.php)**

### **CRUD Admin:**

- **Add Admin:** Tambah user baru dengan email dan password
- **Edit Admin:** Update email/password admin
- **Delete Admin:** Hapus user admin

**Security Note:** Password disimpan dalam plaintext (tidak di-hash). Ini adalah **security vulnerability** yang harus diperbaiki menggunakan password hashing.

## **9. ARSITEKTUR DATA FLOW**

```
1. User Login → Session Created → Access Granted
2. Barang Masuk:
   Input Form → Validasi Stok → Update Stock → Log Transaction → Success Message
3. Barang Keluar:
   Input Form → Validasi Stok → Check Availability → Update Stock → Log Transaction → Success Message
4. Monitoring:
   Real-time Stock Display → Low Stock Alert → Export Reports
5. Admin Management:
   CRUD Operations → User Access Control
```

## **10. TEKNOLOGI YANG DIGUNAKAN**

- **Backend:** PHP 7+
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 4
- **JavaScript:** jQuery, DataTables, Chart.js
- **UI Framework:** Bootstrap dengan custom styling

## **11. KELEBIHAN SISTEM**

1. **Real-time Updates:** Stok update otomatis saat transaksi
2. **Validation System:** Mencegah error seperti negative stock
3. **Alert System:** Peringatan stok habis otomatis
4. **Audit Trail:** Semua transaksi tercatat
5. **Export Capability:** Multiple format reporting
6. **Responsive Design:** Mobile friendly
7. **User Management:** System untuk kelola admin

## **12. AREA YANG BISA DIPERBAIKI**

1. **Security:** Implement password hashing (bcrypt/password_hash)
2. **Input Validation:** Sanitasi input untuk mencegah SQL Injection
3. **Error Handling:** Better error messages and logging
4. **Date Range Filtering:** Filter laporan berdasarkan tanggal
5. **Dashboard Analytics:** Charts dan graphs untuk visualisasi data

## **13. KESIMPULAN**

Sistem ini adalah solusi lengkap untuk manajemen inventaris yang mencakup:

- **Core Operations:** CRUD stok, transaksi masuk/keluar
- **Monitoring:** Real-time tracking dan alerting
- **Reporting:** Multi-format export capabilities
- **User Management:** Access control system

Alur sistem dirancang dengan logika bisnis yang jelas: setiap transaksi mempengaruhi stok real-time, semua pergerakan tercatat untuk audit trail, dan sistem memiliki validasi untuk menjaga integritas data. Ini adalah fondasi yang solid untuk sistem inventory management.
