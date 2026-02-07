<?php
$conn = mysqli_connect("localhost", "root", "root", "UKK_stokbarang");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Add role column
$sql1 = "ALTER TABLE login ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'admin'";
if (mysqli_query($conn, $sql1)) {
    echo "Role column added successfully.<br>";
} else {
    echo "Error adding role column: " . mysqli_error($conn) . "<br>";
}

// Update existing users
$sql2 = "UPDATE login SET role = 'admin' WHERE role IS NULL OR role = ''";
if (mysqli_query($conn, $sql2)) {
    echo "Existing users updated to admin role successfully.<br>";
} else {
    echo "Error updating users: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
echo "<br><a href='index.php'>Back to Home</a>";
