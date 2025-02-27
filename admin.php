<?php
include 'koneksi.php';

$query = "SELECT * FROM user";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Panel Admin</title>
</head>
<body>
    <nav class="navbar bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
        </div>
    </nav>
    
    <div class="container-fluid">
        <h1 class="mt-4">Manage User</h1>
        <figure>
            <blockquote class="blockquote">
                <p>Memberi role/ban User.</p>
            </blockquote>
            <figcaption class="blockquote-footer">
                Admin Panel <cite title="Source Title">Manage Users</cite>
            </figcaption>
        </figure>

        <div class="table-responsive">
            <table class="table align-middle table-bordered table-hover">
                <thead>
                    <tr>
                        <th><center>No.</center></th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) { ?>  
                    <tr>
                        <td><center><?php echo $no++; ?>.</center></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td>
                            <a href="BanUser.php?id_user=<?php echo intval($row['id_user']); ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure you want to ban this user?')">
                                <i class="fa fa-ban"></i>
                            </a>
                            <a href="PromoteUser.php?id_user=<?php echo intval($row['id_user']); ?>" 
                               class="btn btn-success btn-sm">
                                <i class="fa fa-arrow-up"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>  
                </tbody>
            </table>
            <a href="logout.php" type="button"  class="btn btn-danger mb-3">
        <i class="fa fa-minus" aria-hidden="true"></i>
		Logout ?
		</a>
        </div>
    </div>
</body>
</html>