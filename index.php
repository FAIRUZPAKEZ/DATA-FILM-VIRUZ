<?php
$host  = "localhost";
$user  = "root";
$pass  = "";
$db    = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
  die("Tidak bisa terkoneksi ke database");
}
$kode_tiket   = "";
$nama_film    = "";
$nama_studio  = "";
$harga_tiket  = "";
$sukses       ="";
$error        ="";

if(isset($_GET['op'])) {
  $op =$_GET['op'];
}else{
  $op = "";
}
if($op == 'delete') {   
  $id   = $_GET['id'];
  $sql1 = "delete from film where id ='$id'";
  $q1   =mysqli_query($koneksi,$sql1);
  if ($q1) {
    $sukses = "Berhasil hapus data";
  }else {
    $error = "Gagal melakukan delete data";
  }
}

if($op == 'edit'){
 $id          = $_GET['id'];
 $sql1        = "select * from film where id = '$id'";
 $q1          =mysqli_query($koneksi,$sql1);
 $r1          =mysqli_fetch_array($q1);
 $kode_tiket  = $r1['kode_tiket'];
 $nama_film   = $r1['nama_film'];
 $nama_studio = $r1['nama_studio'];
 $harga_tiket = $r1['harga_tiket'];
 if($kode_tiket ==''){
  $error = "Data tidak ditemukan";
 }
}

if (isset($_POST['simpan'])) { //untuk create
  $kode_tiket  = $_POST['kode_tiket'];
  $nama_film   = $_POST['nama_film'];
  $nama_studio = $_POST['nama_studio'];
  $harga_tiket = $_POST['harga_tiket'];

  if ($kode_tiket && $nama_film && $nama_studio && $harga_tiket) {
    if($op == 'edit'){ //untuk update
     $sql1   = "update film set kode_tiket = '$kode_tiket',nama_film='$nama_film',nama_studio='$nama_studio',harga_tiket = '$harga_tiket' where id = '$id'";
     $q1     = mysqli_query($koneksi,$sql1);
     if($q1){
      $sukses = "Data berhasil diupdate";
     }else {
      $error = "Data gagal di update";
     }
    }else{ // untuk insert
      $sql1 = "insert into film(kode_tiket,nama_film,nama_studio,harga_tiket) values ('$kode_tiket','$nama_film','$nama_studio','$harga_tiket')";
      $q1  = mysqli_query($koneksi, $sql1);
      if ($q1) {
        $sukses = "Berhasil memasukkan data baru";
      } else {
        $error = "gagal memasukkan data";
      }
    }
    
  }else{
    $error = "silahkan masukkan semua data";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Film</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    .mx-auto {
      width: 800px
    }

    .card {
      margin-top: 10px;
    }
  </style>
</head>


<body>
  <div class="max-auto">
    <!-- untuk memasukkan data -->
    <div class="card">
      <div class="card-header">
        Create / Edit Data
      </div>
      <div class="card-body">
        <?php
        if ($error) {
        ?>
          <div class="alert alert-danger" role="alert">
            <?php echo $error ?>
          </div>
        <?php
        header("refresh:5;url=index.php"); //5:detik
        }
        ?>
        <?php
        if ($sukses) {
        ?>
          <div class="alert alert-success" role="alert">
            <?php echo $sukses ?>
          </div>
        <?php
        header("refresh:5;url=index.php"); //5:detik
        }
        ?>
        <form action="" method="POST">
          <div class="mb-3 row">
            <label for="kode_tiket" class="col-sm-2 col-form-label">kode tiket</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="kode_tiket" name="kode_tiket" value="<?php echo $kode_tiket ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="nama_film" class="col-sm-2 col-form-label">nama film</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="nama_film" name="nama_film" value="<?php echo $nama_film ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="nama_studio" class="col-sm-2 col-form-label">nama studio</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="nama_studio" name="nama_studio" value="<?php echo $nama_studio ?>">
            </div>
          </div>
          <div class="mb-3 row">
            <label for="harga_tiket" class="col-sm-2 col-form-label">harga tiket</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="harga_tiket" name="harga_tiket" value="<?php echo $harga_tiket ?>">
            </div>
          </div>
          <div class="col-12">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
    <!-- untuk mengeluarkan data -->
    <div class="card">
      <div class="card-header text-white bg-secondary">
        Data film
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">kode tiket</th>
              <th scope="col">nama film</th>
              <th scope="col">nama studio</th>
              <th scope="col">harga tiket</th>
              <th scope="col">Aksi</th>
            </tr>
            <tbody>
              <?php
              $sql2 = "select * from film order by id desc";
              $q2   = mysqli_query($koneksi,$sql2); 
              $urut = 1;
              while($r2 = mysqli_fetch_array($q2)) {
                $id           = $r2['id'];
                $kode_tiket   = $r2['kode_tiket'];
                $nama_film    = $r2['nama_film'];
                $nama_studio  = $r2['nama_studio'];
                $harga_tiket  = $r2['harga_tiket'];

                ?>
                  <tr>
                    <th scope="row"><?php echo $urut++ ?></th>
                    <td scope="row"><?php echo $kode_tiket ?></td>
                    <td scope="row"><?php echo $nama_film ?></td>
                    <td scope="row"><?php echo $nama_studio ?></td>
                    <td scope="row"><?php echo $harga_tiket ?></td>
                    <td scope="row">
                      <a href="index.php?op=edit&id=<?php echo $id?>"><button type="button" class="btn btn-warning">Edit</button></a>
                    <a href="index.php?op=delete&id=<?php echo $id?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">delete</button></a>
                    
                    </td>
                  </tr>
                <?php
              }
              ?>
            </tbody>
          </thead>
        </table>

      </div>
    </div>
  </div>
</body>

</html>