<!DOCTYPE html>
<?php
include 'koneksi.php';
session_start();

$id_produk = '';
$nama_produk = '';
$id_kategori = '';
$penjelasan = '';
$harga = '';
$foto_produk = '';

if (isset($_GET['ubah']) || isset($_GET['edit'])) {
    $id_produk = isset($_GET['ubah']) ? $_GET['ubah'] : $_GET['edit'];
    $query = "SELECT * FROM db_produk WHERE id_produk = '$id_produk';";
    $sql = mysqli_query($koneksi, $query);
    $result = mysqli_fetch_assoc($sql);

    $nama_produk = $result['nama_produk'];
    $id_kategori = $result['id_kategori'];
    $penjelasan = $result['penjelasan'];
    $harga = $result['harga'];
    $foto_produk = $result['foto_produk'];
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="fontawsome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/kelola.css">
    <title>Kelola Produk</title>
</head>
<body>
    <nav class="navbar navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Tambah Data Produk</a>
        </div>
    </nav>

    <div class="container">
        <form method="POST" action="proses/proses_admin.php" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $id_produk; ?>" name="id_produk">

            <div class="mb-3 row">
                <label for="nama_produk" class="col-sm-2 col-form-label">Nama Produk</label>
                <div class="col-sm-10">
                    <input required type="text" name="nama_produk" class="form-control" id="nama_produk" placeholder="Nama Produk" value="<?= htmlspecialchars($nama_produk); ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="id_kategori" class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-10">
                    <select required class="form-select" id="id_kategori" name="id_kategori">
                        <option selected disabled value="">Pilih Kategori</option>
                        <?php
                            $query = "SELECT * FROM db_kategori";
                            $sql = mysqli_query($koneksi, $query);
                            while ($result = mysqli_fetch_assoc($sql)) {
                                $selected = ($result['id_kategori'] == $id_kategori) ? "selected" : "";
                                echo "<option value='" . $result['id_kategori'] . "' $selected>" . $result['nama_kategori'] . "</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="penjelasan" class="col-sm-2 col-form-label">Penjelasan</label>
                <div class="col-sm-10">
                    <textarea required class="form-control" id="penjelasan" name="penjelasan" rows="3" placeholder="Deskripsi"><?= htmlspecialchars($penjelasan); ?></textarea>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                <div class="col-sm-10">
                    <input required type="text" name="harga" class="form-control" id="harga" placeholder="100000" value="<?= htmlspecialchars($harga); ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="foto_produk" class="col-sm-2 col-form-label">Foto Produk</label>
                <div class="col-sm-10">
                    <input <?php if (!isset($_GET['edit']) && !isset($_GET['ubah'])) echo "required"; ?> class="form-control" type="file" name="foto_produk" id="foto_produk" accept="image/*">
                </div>
            </div>

            <div class="mb-3 row mt-4">
                <div class="col">
                    <?php if (isset($_GET['ubah']) || isset($_GET['edit'])) { ?>
                        <button type="submit" name="aksi" value="edit" class="btn btn-primary">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Simpan Perubahan
                        </button>
                    <?php } else { ?>
                        <button type="submit" name="aksi" value="add" class="btn btn-primary">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i> Tambahkan
                        </button>
                    <?php } ?>
                    <a href="kelola_kerajinan.php" type="button" class="btn btn-danger">
                        <i class="fa fa-reply" aria-hidden="true"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>

