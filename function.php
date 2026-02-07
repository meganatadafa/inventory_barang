<?php
session_start();

// Koneksi
$conn = mysqli_connect("localhost", "root", "root", "UKK_stokbarang");

// Helper function to check if user is admin
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Membuat barang baru
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
};

// menambah barang masuk
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

    if ($addtomasuk && $updatestockmasuk) {
        header('Location: masuk.php');
        exit();
    } else {
        echo "Gagal menambah barang: " . mysqli_error($conn);
    }
} // menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    // Validasi: cek apakah stok mencukupi
    if ($qty > $stocksekarang) {
        // Jika stok tidak mencukupi, tampilkan pesan error dan hentikan proses
        echo "<script>alert('Stok tidak mencukupi! Stok tersedia: $stocksekarang'); window.location.href='keluar.php';</script>";
        exit();
    }

    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

    $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty, tanggal) VALUES ('$barangnya', '$penerima', '$qty', NOW())");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");

    if ($addtokeluar && $updatestockmasuk) {
        header('Location:keluar.php');
        exit();
    } else {
        echo "Gagal menambah barang: " . mysqli_error($conn);
    }
}

//update info barang
if (isset($_POST["updatebarang"])) {
    $idb = $_POST["idb"];
    $namabarang = $_POST["namabarang"];
    $deskripsi = $_POST["deskripsi"];

    $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi ='$deskripsi' where idbarang = '$idb'");
    if ($update) {
        header('Location:index.php');
        exit();
    } else {
        echo "Gagal" . mysqli_error($conn);
    }
}
//menghapus barang dari stock
if (isset($_POST["hapusbarang"])) {
    $idb = $_POST["idb"];

    // Hapus referensi di tabel masuk terlebih dahulu
    $hapus_masuk = mysqli_query($conn, "delete from masuk where idbarang = '$idb'");

    // Hapus referensi di tabel keluar terlebih dahulu
    $hapus_keluar = mysqli_query($conn, "delete from keluar where idbarang = '$idb'");

    // Baru hapus dari tabel stock
    $hapus = mysqli_query($conn, "delete from stock where idbarang = '$idb'");

    if ($hapus) {
        header('Location:index.php');
        exit();
    } else {
        echo "Gagal" . mysqli_error($conn);
    }
}

//mengubah data barang masuk
if (isset($_POST["updatebarangmasuk"])) {  // ✅ Ganti dari "updatebarang" ke "updatebarangmasuk"
    $idb = $_POST["idb"];
    $idm = $_POST["idm"];
    $keterangan = $_POST["keterangan"];
    $qty = $_POST["qty"];

    // Ambil stock saat ini
    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // Ambil qty lama dari tabel masuk
    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");  // ✅ Tambahkan SELECT
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtylama = $qtynya['qty'];

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
    $updatestock = mysqli_query($conn, "update stock set stock='$stockbaru' where idbarang='$idb'");
    $updatemasuk = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$keterangan' where idmasuk='$idm'");

    if ($updatestock && $updatemasuk) {
        header('Location:masuk.php');
        exit();
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}

//menghapus barang masuk
if (isset($_POST["hapusbarangmasuk"])) {
    $idb = $_POST["idb"];
    $idm = $_POST["idm"];
    $qty = $_POST["kty"];

    // Ambil stock saat ini
    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    // Kurangi stock dengan qty yang dihapus
    $stockbaru = $stockskrg - $qty;

    // Update stock dan hapus data masuk
    $updatestock = mysqli_query($conn, "update stock set stock='$stockbaru' where idbarang='$idb'");
    $hapusmasuk = mysqli_query($conn, "delete from masuk where idmasuk='$idm'");

    if ($updatestock && $hapusmasuk) {
        header('Location:masuk.php');
        exit();
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
}



// ==================== BARANG KELUAR ====================

//mengubah data barang keluar
if (isset($_POST["updatebarangkeluar"])) {
    $idb = $_POST["idb"];
    $idk = $_POST["idk"];
    $penerima = $_POST["penerima"];
    $qty = $_POST["qty"];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtylama = $qtynya['qty'];

    if ($qty > $qtylama) {
        $selisih = $qty - $qtylama;
        $stockbaru = $stockskrg - $selisih;

        if ($stockbaru < 0) {
            echo "<script>alert('Stok tidak mencukupi! Stok tersedia: $stockskrg'); window.location.href='keluar.php';</script>";
            exit();
        }
    } else {
        $selisih = $qtylama - $qty;
        $stockbaru = $stockskrg + $selisih;
    }

    $updatestock = mysqli_query($conn, "update stock set stock='$stockbaru' where idbarang='$idb'");
    $updatekeluar = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");

    if ($updatestock && $updatekeluar) {
        header('Location:keluar.php');
        exit();
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}

//menghapus barang keluar
if (isset($_POST["hapusbarangkeluar"])) {
    $idb = $_POST["idb"];
    $idk = $_POST["idk"];
    $qty = $_POST["kty"];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $stockbaru = $stockskrg + $qty;

    $updatestock = mysqli_query($conn, "update stock set stock='$stockbaru' where idbarang='$idb'");
    $hapuskeluar = mysqli_query($conn, "delete from keluar where idkeluar='$idk'");

    if ($updatestock && $hapuskeluar) {
        header('Location:keluar.php');
        exit();
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
}

// menambah admin baru
if (isset($_POST['addadmin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'admin';

    $queryinsert = mysqli_query($conn, "insert into login (email, password, role) VALUES ('$email','$password','$role')");

    if ($queryinsert) {
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}

// edit data admin
if (isset($_POST['updateadmin'])) {
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $rolebaru = $_POST['role'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn, "update login set email='$emailbaru', password='$passwordbaru', role='$rolebaru' where iduser='$idnya'");

    if ($queryupdate) {
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}

// hapus admin
if (isset($_POST['hapusadmin'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "delete from login where iduser='$id'");

    if ($querydelete) {
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}
