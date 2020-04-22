<?php
  session_start();
  if (!isset($_SESSION["id_admin"])) {
    header("location:login_admin.php");
  }

  // mengambil file config.php
  // agar tidak perlu membuat koneksi baru
  include("config.php");
  include("counter/counter.php");
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Toko Buku</title>
    <!-- css-bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- js-bootstrap -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script type="text/javascript">
      Add = () =>{
        document.getElementById('action').value = "insert";
        document.getElementById('id_admin').value = "";
        document.getElementById('nama').value = "";
        document.getElementById('kontak').value = "";
        document.getElementById('username').value = "";
        document.getElementById('password').value = "";
      }
      Edit = (item) =>{
        document.getElementById('action').value = "update";
        document.getElementById('id_admin').value = item.id_admin;
        document.getElementById('nama').value = item.nama;
        document.getElementById('kontak').value = item.kontak;
        document.getElementById('username').value = item.username;
        document.getElementById('password').value = item.password;
      }
    </script>
    <!-- js-chart -->
    <script type="text/javascript" src="chartjs/Chart.js"></script>

    <script>
      $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
      });
    </script>
  </head>
  <body>
    <!-- Start-Navbar -->
    <?php
      include("navbar_admin.php");
    ?>
    <!-- End-Navbar -->

    <?php
      // Perintah SQL untuk Menampilkan Data Admin
      if (isset($_POST["find"])) {
        // Query jika Melakukan Pencarian
        $find = $_POST["find"];
        $sql = "select * from admin
                where id_admin like '%$find%'
                or kontak like '%$find%'
                or nama like '%$find%'
                or username like '%$find%'
                or password like '%$find%'";
      } else {
        // Query Jika tidak mencari
        $sql = "select * from admin";
      }
      // eksekusi perintah sql
      // $connect -> mengambil dari config.php
      $query = mysqli_query($connect, $sql);
     ?>

    <div class="container">
      <!-- Start-chart -->
      <div class="mb-2 row">
        <div class="col-sm-6" style="position: relative; width:35vw">
          <canvas id="user"></canvas>
          <p class="text-center">Chart jumlah <a href="counter" data-toggle="popover"
            data-trigger="hover" data-placement="right" data-html="true"
            data-content="
            <?php
             $jumlah_admin = mysqli_query($connect,"select * from admin");
             echo "Admin : ".mysqli_num_rows($jumlah_admin)."&nbsp;";
             $jumlah_customer = mysqli_query($connect,"select * from customer");
             echo "Customer : ".mysqli_num_rows($jumlah_customer)."&nbsp;";
             $jumlah_buku = mysqli_query($connect,"select * from buku");
             echo "Buku : ".mysqli_num_rows($jumlah_buku);
            ?>">
            #User</a>
          </p>
        </div>
        <div class="col-sm-6" style="position: relative; width:35vw">
          <canvas id="visitor"></canvas>
          <p class="text-center">Chart jumlah <a href="counter" data-toggle="popover"
            data-trigger="hover" data-placement="right" data-html="true"
            data-content="
            <?php
              if ($hari_ini['hari_ini'] == null || $hari_ini['hari_ini'] == 0) {
                echo "Hari Ini : 0 ";
              } else {
                echo "Hari Ini : ".$hari_ini['hari_ini']."&nbsp;";
              }
              echo "Kemarin : ".$kemarin['kemarin']."&nbsp;";
              $jumlah_totalpengunjung = mysqli_query($connect,"select * from counterdb");
              echo "Total : ".mysqli_num_rows($jumlah_totalpengunjung);
            ?>">
            #visitor</a>
        </p>
        </div>
      </div>

      <!-- js-user -->
    	<script>
    		var ctx = document.getElementById("user").getContext('2d');
    		var myChart = new Chart(ctx, {
    			type: 'doughnut',
    			data: {
    				labels: ["Admin","Customer","Buku"],
    				datasets: [{
    					label: 'Chart jumlah user',
    					data: [
                <?php
					       $jumlah_admin = mysqli_query($connect,"select * from admin");
					       echo mysqli_num_rows($jumlah_admin);
					     ?>,
                <?php
					       $jumlah_customer = mysqli_query($connect,"select * from customer");
					       echo mysqli_num_rows($jumlah_customer);
					     ?>,
               <?php
                $jumlah_buku = mysqli_query($connect,"select * from buku");
                echo mysqli_num_rows($jumlah_buku);
              ?>
              ],
    					backgroundColor: [
    					'rgba(255, 99, 132, 0.2)',
    					'rgba(54, 162, 235, 0.2)',
    					'rgba(255, 206, 86, 0.2)',
    					'rgba(75, 192, 192, 0.2)',
    					'rgba(153, 102, 255, 0.2)',
    					'rgba(255, 159, 64, 0.2)'
    					],
    					borderColor: [
    					'rgba(255,99,132,1)',
    					'rgba(54, 162, 235, 1)',
    					'rgba(255, 206, 86, 1)',
    					'rgba(75, 192, 192, 1)',
    					'rgba(153, 102, 255, 1)',
    					'rgba(255, 159, 64, 1)'
    					],
    					borderWidth: 1
    				}]
    			},
    			options: {
    				scales: {
    					yAxes: [{
    						ticks: {
    							beginAtZero:true
    						}
    					}]
    				}
    			}
    		});
    	</script>

      <!-- js-visitor -->
      <script>
    		var ctx = document.getElementById("visitor").getContext('2d');
    		var myChart = new Chart(ctx, {
    			type: 'pie',
    			data: {
    				labels: ["Total Pengunjung","Kemarin","Hari Ini"],
    				datasets: [{
    					label: 'Jumlah Visitor',
    					data: [
                <?php
					       $jumlah_totalpengunjung = mysqli_query($connect,"select * from counterdb");
					       echo mysqli_num_rows($jumlah_totalpengunjung);
					      ?>,
                <?php
                 echo $kemarin['kemarin'];
                ?>,
                <?php
                  echo $hari_ini['hari_ini'];
                ?>
              ],
    					backgroundColor: [
    					'rgba(255, 99, 132, 0.2)',
    					'rgba(54, 162, 235, 0.2)',
    					'rgba(255, 206, 86, 0.2)',
    					'rgba(75, 192, 192, 0.2)',
    					'rgba(153, 102, 255, 0.2)',
    					'rgba(255, 159, 64, 0.2)'
    					],
    					borderColor: [
    					'rgba(255,99,132,1)',
    					'rgba(54, 162, 235, 1)',
    					'rgba(255, 206, 86, 1)',
    					'rgba(75, 192, 192, 1)',
    					'rgba(153, 102, 255, 1)',
    					'rgba(255, 159, 64, 1)'
    					],
    					borderWidth: 1
    				}]
    			},
    			options: {
    				scales: {
    					yAxes: [{
    						ticks: {
    							beginAtZero:true
    						}
    					}]
    				}
    			}
    		});
    	</script>
      <!-- End-chart -->

      <!-- card -->
      <div class="card">
        <div class="card-header bg-success text-light text-center">
          <h4>User Admin</h4>
        </div>
        <!-- start-body -->
        <div class="card-body">
          <div class="overflow-auto">
            <!-- start-search -->
            <form action="admin.php" method="post">
              <input type="text" name="find" class="form-control mb-2 col-sm-3 float-right" placeholder="Pencarian...">
            </form>
            <!-- end-search -->
            <table class="table border text-center">
              <thead>
                <th>No.</th>
                <th>ID Admin</th>
                <th>Nama</th>
                <th>Kontak</th>
                <th>Username</th>
                <th>Password</th>
                <th>Option</th>
              </thead>
              <tbody>
                <?php
                  $number = 1;
                  foreach ($query as $admin): ?>
                  <tr>
                    <td>
                      <?php echo $number ?>
                    </td>
                    <td><?php echo $admin["id_admin"] ?></td>
                    <td><?php echo $admin["nama"] ?></td>
                    <td><?php echo $admin["kontak"] ?></td>
                    <td><?php echo $admin["username"] ?></td>
                    <td><?php echo $admin["password"] ?></td>
                    <td>
                      <button type="button" name="Edit" class="btn btn-sm btn-info"
                              data-toggle="modal" data-target="#modal_admin"
                              onclick='Edit(<?php echo json_encode($admin);?>)'>Edit</button>

                      <a href="process_crud_admin.php?hapus=true&id_admin=<?php echo $admin["id_admin"];?>"
                          onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')">
                          <button type="button" name="Hapus" class="btn btn-sm btn-danger"
                                  data-toggle="modal" data-target="#modal_admin"
                                  onclick="Hapus(<?php ?>);">
                            Hapus
                          </button>
                      </a>
                    </td>
                  </tr>
                <?php $number++; endforeach; ?>
              </tbody>
            </table>
            </div>
          <button type="button" name="btnTambah" class="btn btn-sm btn-success float-right"
                  data-toggle="modal" data-target="#modal_admin" onclick="Add();">Tambah Data</button>
        </div>
        <!-- end-body -->

        <!-- start-footer -->
        <div class="card-footer text-center">
          <p>&copy;2020 <br> Gagassurya19</p>
        </div>
        <!-- end-footer -->
      </div>
      <!-- end card -->
      <!-- Start Modal -->
      <div class="modal fade" id="modal_admin">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <form action="process_crud_admin.php" method="post">
                <div class="modal-header bg-danger text-light">
                  <h4 class="modal-title">Form Admin</h4>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="action" id="action">
                  id_admin
                  <input type="number" name="id_admin" id="id_admin" class="form-control" required />
                  Nama
                  <input type="text" name="nama" id="nama" class="form-control" required />
                  Kontak
                  <input type="text" name="kontak" id="kontak" class="form-control" required />
                  Username
                  <input type="text" name="username" id="username" class="form-control" required />
                  Password
                  <input type="text" name="password" id="password" class="form-control" required />
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  <button type="submit" name="save_admin" class="btn btn-success">Simpan</button>
                </div>
            </form>
          </div>
        </div>
      </div>
      <!-- End Modal -->
    </div>
  </body>
</html>
