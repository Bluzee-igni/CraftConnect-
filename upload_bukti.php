<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $id_pesanan = intval($_POST['id_transaksi']); // Ini adalah ID pesanan
    $metode_pembayaran = $_POST['metode_pembayaran'] ?? 'Tidak diketahui';

    // Validasi file bukti
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === 0) {
        $file = $_FILES['bukti'];
        $nama_file = $file['name'];
        $tmp_name = $file['tmp_name'];
        $tipe = mime_content_type($tmp_name);

        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($tipe, $allowed_types)) {
            exit("File tidak valid. Harap unggah gambar (JPG/PNG).");
        }

        // Simpan file
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_baru = 'bukti_' . time() . '_' . uniqid() . '.' . $ext;
        $path = 'uploads/' . $nama_baru;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($tmp_name, $path)) {
            // Simpan ke tabel db_pembayaran
            $sql = "INSERT INTO db_pembayaran (id_pesanan, metode_pembayaran, bukti_pembayaran)
                    VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iss", $id_pesanan, $metode_pembayaran, $nama_baru);
            mysqli_stmt_execute($stmt);

            echo "<script>
                    alert('Bukti pembayaran berhasil diupload!');
                    window.location.href = 'pesanan.php?id=$id_pesanan';
                  </script>";
        } else {
            echo "Upload gagal. Coba lagi.";
        }
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "Akses tidak sah.";
}
?>
