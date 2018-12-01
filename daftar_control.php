<?php
session_start();

  if (isset($_POST['daftar'])) {
    date_default_timezone_set("Asia/Jakarta");
    include_once 'config.php';

    $nama = mysqli_real_escape_string($conn, $_POST['username']);
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $repassword = mysqli_real_escape_string($conn, $_POST['repassword']);

    $_SESSION['s_nama'] = $nama;

    $_SESSION['s_nim'] = $nim;
    $_SESSION['s_email'] = $email;
    // $nimoke = intval($nim);
    // var_dump($nimoke);
    // exit();
    //cek jika kosong
    if (empty($nama) || empty($nim) || empty($email) || empty($password)) {

      header("Location: login.php?to=daftar&kosong");
      exit();
    }
    else {
      //cek inputan nama
      if (!preg_match("/^[a-zA-Z ]*$/", $nama)) {
        header("Location: login.php?to=daftar&invalidnama&nim=salah&email=salah");
        exit();
      }
      else{
        //cek ketersediaan nim
        $sql = "SELECT * FROM users WHERE no_akademik=$nim";
        $result = mysqli_query($conn, $sql);
        $resultCek = mysqli_num_rows($result);
        if ($resultCek > 0) {

          header("Location: login.php?to=daftar&nimhastaken&nama=salah&email=salah");
          exit();
        }
        else{

            //cek inputan nim adalah nomor
            if (!is_numeric($nim)) { //cek nim belum bisa !

              header("Location: login.php?to=daftar&invalidnim&nama=salah&email=salah");
              exit();
            }
            else{
            //cek email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              header("Location: login.php?to=daftar&invalidemail&nama=salah&nim=salah");
              exit();
            }
            else{
              //cek konfirmasi password
              if ($password != $repassword) {
                header("Location: login.php?to=daftar&invalidpwd&nama=salah&nim=salah&email=salah");
                exit();
              } else {
                //hash password
                $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
                $date = date('Y-m-d H:i:s');
                //masukkan data daftar ke database
                var_dump($nama);
                exit();
                $sql = "INSERT INTO users (name, no_akademik, email, password, created_at, updated_at) VALUES ('$nama', '$nim', '$email', '$hashedpassword', '$date', '$date')";
                mysqli_query($conn, $sql);
                header("Location: login.php?regsucc");
                exit();

              }
            }
          }
        }
      }

    }
  }
  else {
    header("Location: login.php?to=daftar");
    exit();
  }

 ?>
