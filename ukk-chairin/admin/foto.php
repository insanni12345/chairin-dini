<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['status'] != 'login') {
  echo "<script>
    alert('Anda belum Login!');
    location.href='../index.php';
    </script>";
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" conctent="
  width=device-width, initial-scale=1">
  <title>Galeri Foto</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
  <style>
    body {
      background-color: beige;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="index.php">Website Galeri Foto</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse mt-2" id="navbarNavAltMarkup">
        <div class="navbar-nav me-auto">
          <a href="home.php" class="nav-link">Home</a>
          <a href="album.php" class="nav-link">Album</a>
          <a href="foto.php" class="nav-link">Foto</a>
        </div>

        <a href="../config/aksi_logout.php" class="btn btn-outline-danger m-1">Keluar</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="card mt-2">
          <div class="card-header">Tambah Foto</div>
          <div class="card-body">
            <form action="../config/aksi_foto.php" method="POST" enctype="multipart/form-data">
              <label class="form-label">Judul Foto</label>
              <input type="text" name="judul_foto" class="form-control" required>
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi_foto" required></textarea>
              <label class="form-label">Album</label>
              <select class="form-control" name="album_id" required>
                <?php
                $id = $_SESSION['id'];
                $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE user_id='$id'");
                while($data_album = mysqli_fetch_array($sql_album)){ ?>
                  <option value="<?php echo $data_album['id'] ?>"><?php echo 
                  $data_album['nama_album'] ?></option>
                <?php } ?>
              </select>
              <label class="form-label">File</label>
              <input type="file" class="form-control" name="lokasi_file" required>
              <button type="submit" class="btn btn-primary mt-2" name="tambah">Tambah Data</button>
            </form>
          </div>
        </div> 
      </div>

      <div class="col-md-8">
        <div class="card mt-2">
          <div class="card-header">Data Galeri Foto</div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Foto</th>
                  <th>Judul Foto</th>
                  <th>Deskripsi</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                
                <?php
                $no = 1;
                $user_id = $_SESSION['id'];


                $sql = mysqli_query($koneksi, "SELECT * FROM foto WHERE user_id='$user_id'");
                // $data = mysqli_fetch_array($sql);

                while ($data = mysqli_fetch_array($sql)) {
                ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><img src="../assets/img/<?php echo $data['lokasi_file'] ?>" width="100"></td>
                    <td><?php echo $data['judul_foto'] ?></td>
                    <td><?php echo $data['deskripsi_foto'] ?></td>
                    <td><?php echo $data['tanggal_unduh'] ?></td>
                    <td>
                      <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                      data-bs-target="#edit<?php echo $data['id'] ?>">
                        Edit
                      </button>

                      <div class="modal fade" id="edit<?php echo $data['id'] ?>" tabindex="-1" aria-labelledby="
                              exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form action="../config/aksi_foto.php" method="POST" enctype=
                              "multipart/form-data">
                              <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                                <input type="hidden" name="foto" value="<?php echo $data['judul_foto'] ?>">
                                <label class="form-label">Judul Foto</label>
                                <input type="text" name="judul_foto" value="<?php echo $data['judul_foto'] ?>" class="form-control" required>
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi_foto"
                                required><?php echo $data['deskripsi_foto']; ?></textarea>
                                <label class="form-label">Album</label>
                                <select class="form-control" name="album_id">
                                    <?php
                                    $id = $_SESSION['id'];
                                    $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE user_id='$id'");
                                    while($data_album = mysqli_fetch_array($sql_album)){ ?>
                                    <option <?php if($data_album['id'] == $data['id']) { ?> selected="selected" <?php } ?> value="<?php echo $data_album['id'] ?>"><?php echo 
                                    $data_album['nama_album'] ?></option>
                                    <?php } ?>
                                </select>
                                <label class="form-label">Foto</label>
                                <div class="row">
                                    <div class="col-md-4">
                                    <img src="../assets/img/<?php echo $data['lokasi_file'] ?>" width="100">
                                    </div>
                                    <div class="col-md-8">
                                    <label class="form-label">Ganti File</label>
                                    <input type="file" class="form-control" 
                                    name="lokasi_file">
                                    </div>
                                </div>
                                 

                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="edit" class="btn btn-primary">Edit Data
                              </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="
                              #hapus<?php echo $data['id'] ?>">
                        Hapus
                      </button>

                      <div class="modal fade" id="hapus<?php echo $data['id'] ?>" tabindex="-1" aria-labelledby="
                              exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form action="../config/aksi_foto.php" method="POST">
                              <input type="hidden" name="id" value="<?php echo $data['id'] ?>">

                              <input type="hidden" name="foto" value="<?php echo $data['judul_foto'] ?>">

                                <input type="hidden" name="foto" value="<?php echo $data['judul_foto'] ?>">
                                Apakah anda yakin akan menghapus data <strong> <?php echo $data['judul_foto'] ?> </strong>

                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="hapus" class="btn btn-primary">Hapus Data
                              </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>



  <footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
    <p>&copy; UKK RPL 2024 | CHAIRIN DHINI</p>
  </footer>


  <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</body>
</html>