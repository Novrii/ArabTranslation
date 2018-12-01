<?php

 session_start();

 ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Masuk | Tashih Arabic</title>
    <link type="text/css" rel="stylesheet" href="css/style.css"/>
    <!-- <link rel="stylesheet" href="bulma/css/bulma.min.css"> -->
    <link rel="shortcut icon" type="image/png" href="images/arabic-icon.png"/>
    <script src="JavaScript/jquery-3.1.1.min.js"></script>
    <script src="JavaScript/jquery-validasi.js"></script>
    <script src="JavaScript/jquery.js"></script>
    <script src="JavaScript/jQuerysheet.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>


</head>
<body>

  <ul>
    <li style="float: right;"><a href="index.php"><i class="fas fa-arrow-left"></i>&nbsp Kembali</a></li>
  </ul>

    <div id="box-form">
            <?php
              if (isset($_GET["to"]) == "daftar") {

            ?>
            <form method="POST" action="daftar_control.php">
              <img class="img" src="images/arabic-icon.png"/>
              <header class="montserrat">Daftar</header>
                <div class="input-group">

                  <?php if (isset($_GET["kosong"])): ?>
                    <input type="text" class="form-control" name="username" style="border-color: red;"  />
                    <label>Nama </label>
                    <small style="color: red;">Tidak boleh kosong</small>
                  <?php elseif (isset($_GET["invalidnama"])): ?>
                    <input type="text" class="form-control" name="username" style="border-color: red;"/>
                    <label>Nama</label>
                    <small style="color: red;">Tidak Boleh Angka</small>
                  <?php elseif (isset($_GET["nama"])): $nama = $_SESSION['s_nama'];  ?>
                    <input type="text" class="form-control" name="username" value="<?= $nama; ?>" />
                    <label>Nama</label>
                  <?php else: ?>
                    <input type="text" class="form-control" name="username" />
                    <label>Nama</label>
                  <?php endif ?>

                </div>
                <div class="input-group">
<!-- nim masih teks -->
                  <?php if (isset($_GET["kosong"])): ?>
                    <input type="text" class="form-control" name="nim" style="border-color: red;" />
                    <label>NIM </label>
                    <small style="color: red;">Tidak boleh kosong</small>
                  <?php elseif (isset($_GET["invalidnim"])): ?>
                    <input type="text" class="form-control" name="nim" style="border-color: red;"/>
                    <label>NIM</label>
                    <small style="color: red;">Hanya boleh angka</small>
                  <?php elseif (isset($_GET["nimhastaken"])): ?>
                    <input type="text" class="form-control" name="nim" style="border-color: red;"/>
                    <label>NIM</label>
                    <small style="color: red;">NIM sudah terdaftar</small>
                  <?php elseif (isset($_GET["nim"])): $nim = $_SESSION['s_nim']; ?>
                    <input type="text" class="form-control" name="nim" value="<?= $nim; ?>" />
                    <label>NIM</label>
                  <?php else: ?>
                    <input type="text" class="form-control" name="nim" />
                    <label>NIM</label>
                  <?php endif ?>


                </div>
                <div class="input-group">
<!-- email masih teks -->
                  <?php if (isset($_GET["kosong"])): ?>
                    <input type="text" class="form-control" name="email" style="border-color: red;" />
                    <label>E-mail </label>
                    <small style="color: red;">Tidak boleh kosong</small>
                  <?php elseif (isset($_GET["invalidemail"])): ?>
                    <input type="text" class="form-control" name="email" style="border-color: red;"/>
                    <label>E-mail</label>
                    <small style="color: red;">Koreksi e-mail</small>
                  <?php elseif (isset($_GET["email"])): $email = $_SESSION['s_email']; ?>
                    <input type="text" class="form-control" name="email" value="<?= $email; ?>" />
                    <label>NIM</label>
                  <?php else: ?>
                    <input type="text" class="form-control" name="email" />
                    <label>E-mail</label>
                  <?php endif ?>


                </div>
                <div class="input-group">

                  <?php if (isset($_GET["kosong"])): ?>
                    <input type="password" class="form-control" name="password" style="border-color: red;"  />
                    <label>Password </label>
                    <small style="color: red;">Tidak boleh kosong</small>
                  <?php elseif (isset($_GET["invalidpwd"])): ?>
                    <input type="password" class="form-control" name="password" style="border-color: red;"/>
                    <label>Password</label>
                  <?php else: ?>
                    <input type="password" class="form-control" name="password" />
                    <label>Password</label>
                  <?php endif ?>


                </div>
                <div class="input-group">

                  <?php if (isset($_GET["kosong"])): ?>
                    <input type="password" class="form-control" name="repassword" style="border-color: red;"  />
                    <label>Ulangi Password </label>
                    <small style="color: red;">Tidak boleh kosong</small>
                  <?php elseif (isset($_GET["invalidpwd"])): ?>
                    <input type="password" class="form-control" name="repassword" style="border-color: red;"/>
                    <label>Ulangi Password</label>
                    <small style="color: red;">Password tidak sama</small>
                  <?php else: ?>
                    <input type="password" class="form-control" name="repassword" />
                    <label>Ulangi Password</label>
                  <?php endif ?>

                </div>
                <a href="login.php">Masuk</a>
              <button type="submit" name="daftar" >Daftar</button>
            </form>

            <?php
              }
              else{
             ?>
            <form method="POST" action="login_control.php">
              <img src="images/arabic-icon.png"/>
              <header class="montserrat">Masuk</header>
              <?php if (isset($_GET["regsucc"])): ?>
                <small style="color: rgb(31, 157, 108);">Pendaftaran berhasil. Silahkan Login</small>
              <?php endif; ?>

              <div class="input-group">
                <?php if (isset($_GET["kosong"])): ?>
                  <input type="text" class="form-control" name="username" style="border-color: red;" />
                  <label >Username</label>
                  <small style="color: red; font-size: 10px;margin-top: 3px;float:right;">Tidak boleh kosong</small>
                <?php elseif (isset($_GET["invalidnim"])): ?>
                  <input type="text" class="form-control" name="username" style="border-color: red;" />
                  <label >Username</label>
                  <small style="color: red; font-size: 10px;margin-top: 3px;float:right;">Username Salah</small>
                <?php elseif(isset($_GET["u"])): $usrnm = $_GET["u"]; ?>
                  <input type="text" class="form-control" name="username" value="<?= $usrnm; ?>" />
                  <label >Username</label>
                <?php else: ?>
                  <input type="text" class="form-control" name="username"/>
                  <label >NIM</label>

                <?php endif; ?>


              </div>
              <div class="input-group">
                  <!-- <i class="fas fa-key"></i> -->
                  <?php if (isset($_GET["kosong"])): ?>
                    <input type="password" class="form-control" name="password" style="border-color: red;"/>
                    <label >Password</label>
                    <small style="color: red; font-size: 10px;margin-top: 3px;float:right;">Tidak boleh kosong</small>
                  <?php elseif (isset($_GET["invalidpwd"])): ?>
                    <input type="password" class="form-control" name="password" style="border-color: red;"/>
                    <label >Password</label>
                    <small style="color: red; font-size: 10px;margin-top: 3px;float:right;">Password Salah</small>
                  <?php else: ?>
                    <input type="password" class="form-control" name="password"/>
                    <label >Password</label>
                  <?php endif; ?>

              </div>

              <a href="login.php?to=daftar">Daftar</a>
              <button name="masuk" type="submit">Masuk</button>
            </form>
            <?php
              }
            ?>

        </div>





     <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="JavaScript/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="JavaScript/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="JavaScript/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="JavaScript/js/custom.js"></script>

</body>
</html>
