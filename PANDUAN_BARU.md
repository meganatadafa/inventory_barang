# Panduan Penggunaan Menu Baru - Sistem Inventory PT. Bondvast

## 📋 Tanggal: 4 Februari 2026

## 👨‍💻 Developer: Cline AI Assistant

---

## 🎯 OVERVIEW

Telah ditambahkan **2 menu baru** ke dalam sistem inventory:

1. **Dashboard Analytics** - Visualisasi data dan statistik
2. **Kelola Supplier** - Manajemen database supplier

---

## 🚀 INSTALASI

### Langkah 1: Buat Tabel Supplier

Jalankan perintah SQL berikut di database `UKK_stokbarang`:

**Opsi 1: Menggunakan phpMyAdmin**

1. Buka phpMyAdmin
2. Pilih database `UKK_stokbarang`
3. Klik tab "SQL"
4. Copy dan paste isi file `create_supplier_table.sql`
5. Klik "Go"

**Opsi 2: Menggunakan Command Line**

```bash
mysql -u root -p UKK_stokbarang < create_supplier_table.sql
```

### Langkah 2: Akses Menu Baru

Menu baru sudah otomatis tersedia di sidebar navigasi semua halaman:

- Dashboard Analytics (ikon grafik)
- Kelola Supplier (ikon truk)

---

## 📊 MENU 1: DASHBOARD ANALYTICS

### Lokasi File

- `dashboard.php`

### Fitur Utama

#### 1. **KPI Cards (4 Kartu Informasi)**

- **Total Barang**: Jumlah keseluruhan stok di gudang
- **Barang Masuk**: Total transaksi barang masuk
- **Barang Keluar**: Total transaksi barang keluar
- **Stok Sedikit**: Jumlah barang dengan stok < 5 unit

Warna Kartu:

- 🔵 Biru - Total Barang
- 🟢 Hijau - Barang Masuk
- 🟡 Kuning - Barang Keluar
- 🔴 Merah - Stok Sedikit (Alert)

#### 2. **Top 5 Barang Terbanyak**

Tabel menampilkan:

- Nama Barang
- Jumlah Stok
- Progress Bar (visualisasi proporsi stok)

#### 3. **Ringkasan Transaksi (Pie Chart)**

Chart berbentuk pie yang menampilkan:

- Persentase Barang Masuk vs Barang Keluar
- Warna: Hijau (Masuk) dan Kuning (Keluar)
- Hover untuk melihat detail angka

#### 4. **Peringatan Stok Rendah**

Alert otomatis untuk barang dengan stok < 5:

- 🟡 Warning: Daftar barang yang perlu restock
- 🟢 Success: Pesan jika semua stok aman

### Cara Menggunakan

1. Login ke sistem
2. Klik menu "Dashboard Analytics" di sidebar
3. Otomatis tampilkan data real-time dari database
4. Data diupdate otomatis setiap halaman direfresh

### Kegunaan Dashboard

- ✅ Monitoring real-time stok gudang
- ✅ Identifikasi barang yang perlu restock
- ✅ Analisis tren transaksi masuk/keluar
- ✅ Pengambilan keputusan berbasis data
- ✅ Presentasi ke management

---

## 🚚 MENU 2: KELOLA SUPPLIER

### Lokasi File

- `supplier.php`
- Logic di `function.php` (baris 285-337)

### Fitur Utama

#### 1. **Tambah Supplier**

**Lokasi:** Tombol "Tambah Supplier" di pojok kanan atas

**Field yang Diisi:**

- **Nama Supplier** (Wajib): Nama perusahaan/supplier
- **Kontak** (Wajib): No. Telepon/WhatsApp
- **Email** (Opsional): Alamat email supplier
- **Alamat** (Opsional): Alamat lengkap supplier

#### 2. **Edit Supplier**

**Cara:**

1. Klik tombol "Edit" (warna kuning) di baris supplier yang ingin diubah
2. Modal akan muncul dengan data existing
3. Ubah informasi yang diperlukan
4. Klik "Update" untuk menyimpan perubahan

#### 3. **Hapus Supplier**

**Cara:**

1. Klik tombol "Delete" (warna merah) di baris supplier
2. Modal konfirmasi akan muncul
3. Klik "Hapus" untuk menghapus secara permanen
4. ⚠️ Peringatan: Tindakan tidak dapat dibatalkan!

#### 4. **Tabel Supplier**

Kolom yang ditampilkan:

- **No**: Nomor urut
- **Nama Supplier**: Nama perusahaan supplier
- **Kontak**: No. telepon/kontak
- **Email**: Alamat email (jika ada)
- **Aksi**: Tombol Edit dan Delete

### Cara Menggunakan

#### Menambah Supplier Baru

1. Buka menu "Kelola Supplier"
2. Klik tombol "Tambah Supplier"
3. Isi form modal yang muncul
4. Klik "Simpan"
5. Supplier baru otomatis muncul di tabel

#### Mengedit Data Supplier

1. Cari supplier di tabel (gunakan search DataTables)
2. Klik tombol kuning "Edit"
3. Ubah informasi di modal
4. Klik "Update"

#### Menghapus Supplier

1. Cari supplier yang ingin dihapus
2. Klik tombol merah "Delete"
3. Baca peringatan di modal
4. Klik "Hapus" jika yakin

### Fitur Tambahan

- **DataTables**: Sorting, searching, dan pagination otomatis
- **Responsive**: Tampilan menyesuaikan ukuran layar
- **Modal Form**: Form input dalam popup untuk UX yang baik

---

## 🔗 INTEGRASI DENGAN SISTEM EXISTING

### Update di Semua Halaman

Sidebar navigasi telah diupdate di semua halaman:

- ✅ `index.php` (Stock Barang)
- ✅ `masuk.php` (Barang Masuk)
- ✅ `keluar.php` (Barang Keluar)
- ✅ `admin.php` (Kelola Admin)
- ✅ `dashboard.php` (Dashboard Analytics)
- ✅ `supplier.php` (Kelola Supplier)

### Urutan Menu

1. Stock Barang
2. Barang Masuk
3. Barang Keluar
4. **Dashboard Analytics** (BARU)
5. **Kelola Supplier** (BARU)
6. Kelola Admin
7. LogOut

### Logic Backend

Semua logic untuk menu baru ada di `function.php`:

- Baris 285-337: CRUD Supplier (Create, Read, Update, Delete)
- Baris 1-50: Analytics queries untuk dashboard

---

## 📝 DATABASE SCHEMA

### Tabel Supplier

```sql
CREATE TABLE supplier (
    idsupplier INT AUTO_INCREMENT PRIMARY KEY,
    namasupplier VARCHAR(100) NOT NULL,
    kontak VARCHAR(50) NOT NULL,
    email VARCHAR(100) NULL,
    alamat TEXT
)
```

### Relasi dengan Tabel Lain

- **Tabel `masuk`**: Field `keterangan` = nama supplier
- **Tabel `supplier`**: Database terpisah untuk management supplier

---

## 🎨 DESAIN & UI/UX

### Konsistensi Desain

- Menggunakan Bootstrap 4 seperti halaman lain
- Font Awesome icons
- Warna sesuai tema lemon (kuning-hijau)
- DataTables untuk tabel interaktif

### Animasi & Interaksi

- Hover effects pada tombol
- Modal popup dengan backdrop
- Progress bar untuk visualisasi
- Chart.js untuk grafik interaktif

---

## ⚠️ CATATAN PENTING

### Security

- Password admin masih dalam plaintext (perlu di-hash di masa depan)
- Tidak ada prepared statements (vulnerable SQL injection)

### Recommendations

1. **Gunakan Prepared Statements** untuk mencegah SQL injection
2. **Implement Password Hashing** dengan `password_hash()`
3. **Add Input Validation** untuk sanitasi data
4. **Add Session Timeout** untuk security session
5. **Add CSRF Protection** untuk form submissions

### Data Integrity

- Pastikan database `UKK_stokbarang` sudah tersedia
- Tabel `supplier` harus dibuat sebelum menggunakan menu
- Backup database sebelum implementasi produksi

---

## 🐛 TROUBLESHOOTING

### Masalah: Dashboard tidak muncul data

**Solusi:**

- Pastikan tabel `stock`, `masuk`, dan `keluar` ada
- Cek koneksi database di `function.php`

### Masalah: Tabel Supplier kosong

**Solusi:**

- Jalankan file `create_supplier_table.sql`
- Cek apakah database `UKK_stokbarang` dipilih

### Masalah: Chart tidak tampil

**Solusi:**

- Pastikan internet aktif (CDN Chart.js)
- Cek console browser untuk JavaScript errors
- Clear browser cache

### Masalah: Modal tidak muncul

**Solusi:**

- Pastikan jQuery dan Bootstrap JS ter-load
- Cek konflik JavaScript
- Cek browser compatibility

---

## 📚 REFERENSI FILE

### Files yang Dibuat Baru

1. `dashboard.php` - Halaman dashboard analytics
2. `supplier.php` - Halaman kelola supplier
3. `create_supplier_table.sql` - Script SQL untuk tabel supplier

### Files yang Dimodifikasi

1. `function.php` - Tambah logic supplier (baris 285-337)
2. `index.php` - Update sidebar
3. `masuk.php` - Update sidebar
4. `keluar.php` - Update sidebar
5. `admin.php` - Update sidebar

---

## 🎓 TIPS PENGGUNAAN

### Untuk Manager

- Gunakan Dashboard Analytics setiap hari untuk monitoring
- Perhatikan peringatan stok rendah
- Analisis pie chart untuk tren transaksi

### Untuk Staff Gudang

- Tambah supplier baru saat bekerja dengan vendor baru
- Update informasi supplier jika berubah
- Gunakan DataTables search untuk cepat menemukan supplier

### Untuk Owner

- Lihat KPI cards untuk snapshot bisnis
- Gunakan Top 5 Barang untuk planning restock
- Export dashboard data untuk laporan bulanan

---

## 🔄 UPDATE FUTURE

### Fitur yang Bisa Ditambahkan

1. **Export Dashboard** ke PDF/Excel
2. **Performance Rating** untuk supplier
3. **Purchase Order** otomatis dari stok rendah
4. **Multi-warehouse** support
5. **Advanced Analytics** dengan chart lebih detail
6. **Email Notifications** otomatis

---

## 📞 SUPPORT

Jika mengalami masalah:

1. Cek troubleshooting section di atas
2. Lihat error log PHP
3. Cek console browser untuk JS errors
4. Pastikan semua dependensi ter-load

---

## ✅ CHECKLIST IMPLEMENTASI

- [ ] Database `UKK_stokbarang` tersedia
- [ ] Tabel `supplier` sudah dibuat
- [ ] File `dashboard.php` sudah ada
- [ ] File `supplier.php` sudah ada
- [ ] `function.php` sudah diupdate
- [ ] Sidebar semua halaman sudah diupdate
- [ ] Testing menu Dashboard Analytics
- [ ] Testing menu Kelola Supplier
- [ ] Testing CRUD supplier (Add, Edit, Delete)
- [ ] Verifikasi charts tampil dengan benar
- [ ] Backup database sebelum produksi

---

## 📅 VERSION HISTORY

**v1.0 - 4 Februari 2026**

- ✅ Initial release Dashboard Analytics
- ✅ Initial release Supplier Management
- ✅ Update semua sidebar navigasi
- ✅ Add SQL script untuk tabel supplier
- ✅ Dokumentasi lengkap

---

## 🎉 KESIMPULAN

Dua menu baru ini memberikan:

- **Dashboard Analytics**: Visualisasi data untuk decision making
- **Supplier Management**: Database lengkap untuk vendor management
- **Foundation Solid**: Untuk fitur lanjutan di masa depan
- **Consistent UI**: Sesuai desain sistem yang sudah ada
- **User-Friendly**: Mudah digunakan dengan modal dan DataTables

Sistem sekarang lebih fungsional dan siap untuk scale-up bisnis! 🚀

---

**Dibuat oleh:** Cline AI Assistant
**Untuk:** PT. Bondvast
**Tanggal:** 4 Februari 2026
