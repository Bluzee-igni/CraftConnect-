<!DOCTYPE html>
<?php
include 'koneksi.php';
session_start();

$id_produk = '';
$nama_produk = '';
$penjelasan = '';
$link_pembelian = '';
$foto_produk = '';

if (isset($_GET['ubah'])) {
    $id_produk = $_GET['ubah'];
    $query = "SELECT * FROM posting WHERE id_produk = '$id_produk';";
    $sql = mysqli_query($koneksi, $query);
    $result = mysqli_fetch_assoc($sql);

    $nama_produk = $result['nama_produk'];
    $penjelasan = $result['penjelasan'];
    $link_pembelian = $result['link_pembelian'];
    $foto_produk = $result['foto_produk'];
}
?>

<html>
<head>
    <meta charset="utf-8">
    <!-- Bootstrap -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="fontawsome/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/kelola.css">

    <title>Kelola Produk</title>
</head>
<body>
    <nav class="navbar navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                Tambah Produk
            </a>
        </div>
    </nav>
    <div class="container">
        <form method="POST" action="proses.php" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $id_produk; ?>" name="id_produk">
            <div class="mb-3 row">
                <label for="nama_produk" class="col-sm-2 col-form-label">Nama Produk</label>
                <div class="col-sm-10">
                    <input required type="text" name="nama_produk" class="form-control" id="nama_produk" placeholder="Nama Produk" value="<?php echo $nama_produk; ?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="penjelasan" class="col-sm-2 col-form-label">Penjelasan</label>
                <div class="col-sm-10">
                    <textarea required class="form-control" id="penjelasan" name="penjelasan" rows="3"><?php echo $penjelasan; ?></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="link_pembelian" class="col-sm-2 col-form-label">Link Pembelian</label>
                <div class="col-sm-10">
                    <input required type="text" name="link_pembelian" class="form-control" id="link_pembelian" placeholder="Link Pembelian" value="<?php echo $link_pembelian; ?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="foto_produk" class="col-sm-2 col-form-label">Foto Produk</label>
                <div class="col-sm-10">
                    <input <?php if (!isset($_GET['ubah'])) { echo "required"; } ?> class="form-control" type="file" name="foto_produk" id="foto_produk" accept="image/*">
                </div>
            </div>
            <div class="mb-3 row mt-4">
                <div class="col">   
                    <?php
                    if (isset($_GET['ubah'])) {
                    ?>  
                        <button type="submit" name="aksi" value="edit" class="btn btn-primary">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Simpan Perubahan
                        </button>
                    <?php
                    } else {
                    ?>  
                        <button type="submit" name="aksi" value="add" class="btn btn-primary">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Tambahkan 
                        </button>
                    <?php
                    } 
                    ?>      
                    <a href="index.php" type="button" class="btn btn-danger">
                        <i class="fa fa-reply" aria-hidden="true"></i>
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>