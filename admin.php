<?php
session_start();
include 'koneksi.php';

$id_admin = $_SESSION['id_user'] ?? null;
$admin_name = 'Admin';

if ($id_admin) {
    $query_admin = mysqli_query($koneksi, "SELECT nama FROM db_pengguna WHERE id_user = '$id_admin'");
    if ($data = mysqli_fetch_assoc($query_admin)) {
        $admin_name = $data['nama'];
    }
}

// Ambil semua pengguna non-admin
$query = "SELECT * FROM db_pengguna WHERE role != 'admin' ORDER BY id_user ASC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CraftConnect - Admin Panel</title>
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="container">
    <?php include 'components/sidebar.php'; ?>

    <main class="main-content">
      <h1>Hai min 👋, <?php echo htmlspecialchars($admin_name); ?>!</h1>

      <figure>
        <blockquote class="blockquote">
          <p>Memberi role/ban User.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
          Admin Panel <cite title="Source Title">Manage Users</cite>
        </figcaption>
      </figure>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>No.</th>
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
              <td><?php echo $no++; ?>.</td>
              <td><?php echo htmlspecialchars($row['nama']); ?></td>
              <td><?php echo htmlspecialchars($row['username']); ?></td>
              <td class="aksi">
                <a href="ban.php?id_user=<?php echo intval($row['id_user']); ?>" class="btn-danger" onclick="return confirm('Apakah anda yakin ingin memban user ini?')">
                  <i class="fa fa-ban"></i>
                </a>
                <a href="promote.php?id_user=<?php echo intval($row['id_user']); ?>" class="btn-success" onclick="return confirm('Apakah anda yakin ingin mempromosikan user ini menjadi admin ?')">
                  <i class="fa fa-arrow-up"></i>
                </a>
                <?php if ($row['role'] == 'banned') { ?>
                <a href="unban.php?id_user=<?php echo intval($row['id_user']); ?>" class="btn-warning" onclick="return confirm('Apakah anda yakin ingin meng-unban user ini?')">
                  <i class="fa fa-unlock"></i>
                </a>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>


