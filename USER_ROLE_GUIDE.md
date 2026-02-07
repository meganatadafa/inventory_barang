# Panduan Sistem Role User (Admin & User)

## Ringkasan Perubahan

Sistem inventaris sekarang mendukung dua role user:

1. **Admin** - Akses penuh (tambah, edit, hapus data)
2. **User** - Akses baca saja (view-only)

## Langkah-langkah Setup

### 1. Jalankan Migrasi Database

Buka browser dan akses: `http://localhost/UKK_stokbarang/run_migration.php`

Ini akan menambahkan kolom `role` ke tabel `login` dan mengupdate semua user yang sudah ada menjadi role 'admin'.

### 2. Login sebagai Admin

Login dengan akun admin yang sudah ada untuk mengelola user.

## Fitur Admin

Admin memiliki akses penuh ke semua fitur:

### ✅ Dapat Dilakukan:

- Melihat Dashboard
- Melihat Data Stok Barang
- Melihat Data Barang Masuk
- Melihat Data Barang Keluar
- Export Data (Stok, Barang Masuk, Barang Keluar)
- Menambah Barang Baru
- Edit Stok Barang
- Hapus Barang
- Tambah Barang Masuk/Keluar
- Edit Barang Masuk/Keluar
- Hapus Barang Masuk/Keluar
- Mengelola Admin/User (Tambah, Edit, Hapus akun)

### 📍 Menu yang Tersedia:

- Dashboard
- Stock Barang
- Barang Masuk
- Barang Keluar
- **Kelola Admin** (khusus admin)

## Fitur User

User hanya memiliki akses baca (read-only):

### ✅ Dapat Dilakukan:

- Melihat Dashboard
- Melihat Data Stok Barang
- Melihat Data Barang Masuk
- Melihat Data Barang Keluar
- Export Data (Stok, Barang Masuk, Barang Keluar)

### ❌ Tidak Dapat Dilakukan:

- Menambah Barang
- Edit Stok Barang
- Hapus Barang
- Tambah Barang Masuk/Keluar
- Edit Barang Masuk/Keluar
- Hapus Barang Masuk/Keluar
- Mengelola Admin/User
- Mengakses menu "Kelola Admin"

### 📍 Menu yang Tersedia:

- Dashboard
- Stock Barang
- Barang Masuk
- Barang Keluar
- Logout
- _(Menu "Kelola Admin" tidak akan muncul untuk user)_

## Cara Menambah User Baru

1. Login sebagai Admin
2. Masuk ke menu **Kelola Admin**
3. Klik tombol **"Tambah Admin"**
4. Isi form:
   - **Email**: Email user
   - **Password**: Password user
   - **Role**: Pilih "User" (bukan "Admin")
5. Klik **"Simpan"**

## Cara Mengedit Role User

1. Login sebagai Admin
2. Masuk ke menu **Kelola Admin**
3. Klik tombol **"Edit"** pada user yang ingin diubah
4. Ubah dropdown **Role**:
   - Pilih "Admin" untuk memberikan akses penuh
   - Pilih "User" untuk akses baca saja
5. Klik **"Update"**

## Cara Menghapus User

1. Login sebagai Admin
2. Masuk ke menu **Kelola Admin**
3. Klik tombol **"Delete"** pada user yang ingin dihapus
4. Konfirmasi penghapusan

## Perbedaan Tampilan

### Tampilan Admin:

- Tombol "Tambah Barang" muncul di halaman Stock Barang
- Tombol "Tambah Barang Masuk" muncul di halaman Barang Masuk
- Tombol "Tambah Barang Keluar" muncul di halaman Barang Keluar
- Kolom "Aksi" muncul di tabel dengan tombol Edit/Delete
- Menu "Kelola Admin" muncul di sidebar navigasi

### Tampilan User:

- Tidak ada tombol "Tambah Barang" (hanya bisa melihat data)
- Tidak ada tombol "Tambah Barang Masuk/Keluar"
- **Kolom "Aksi" TIDAK MUNCUL sama sekali di tabel**
- Menu "Kelola Admin" **tidak** muncul di sidebar
- Hanya bisa melihat data dan melakukan export

## Keamanan

- User dengan role "User" akan di-redirect jika mencoba mengakses halaman admin.php secara langsung
- Semua operasi penambahan, pengeditan, dan penghapusan dilindungi oleh pengecekan role
- Session menyimpan informasi role untuk setiap pengguna

## Contoh Penggunaan

### Skenario 1: Manager Gudang (Admin)

- Role: Admin
- Tugas: Mengelola seluruh operasi gudang
- Akses: Semua fitur aktif

### Skenario 2: Staff Pengawas (User)

- Role: User
- Tugas: Memantau stok dan melakukan laporan
- Akses: Hanya melihat data dan export laporan

### Skenario 3: Akuntan (User)

- Role: User
- Tugas: Membuat laporan dari data inventaris
- Akses: Hanya melihat data dan export

## Catatan Penting

1. **Minimal 1 Admin**: Sistem harus memiliki minimal 1 akun admin untuk mengelola user
2. **Password Minimal**: Password minimal 6 karakter
3. **Role Default**: Semua user yang sudah ada sebelum migrasi otomatis menjadi 'admin'
4. **Hapus Akun**: Hati-hati saat menghapus admin yang sedang login

## Troubleshooting

### Masalah: User tidak bisa login setelah migrasi

**Solusi**: Pastikan Anda sudah menjalankan `run_migration.php` untuk menambahkan kolom role.

### Masalah: User masih bisa melihat menu admin

**Solusi**: Logout dan login ulang untuk refresh session role.

### Masalah: User bisa mengakses admin.php secara langsung

**Solusi**: File `admin.php` sudah memiliki proteksi, user akan di-redirect ke `index.php`.

## File yang Dimodifikasi

1. **function.php** - Menambah fungsi `isAdmin()` dan update query admin
2. **login.php** - Menyimpan role ke session
3. **admin.php** - Menambahkan form role dan proteksi akses
4. **index.php** - Menyembunyikan kolom "Aksi" dan tombol edit/delete untuk user
5. **masuk.php** - Menyembunyikan kolom "Aksi" dan tombol tambah/edit/delete untuk user
6. **keluar.php** - Menyembunyikan kolom "Aksi" dan tombol tambah/edit/delete untuk user
7. **dashboard.php** - Menyembunyikan menu admin untuk user

## File Baru

1. **migration_add_role.sql** - Script SQL untuk menambahkan kolom role
2. **run_migration.php** - Script PHP untuk menjalankan migrasi
3. **USER_ROLE_GUIDE.md** - Dokumentasi ini

## Dukungan

Jika mengalami masalah atau memiliki pertanyaan, hubungi tim pengembang.
