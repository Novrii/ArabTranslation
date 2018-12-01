<?php

  session_start();

  if (isset($_POST['masuk'])) {
    include_once 'config.php';

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($username) || empty($password)) {
      header("Location: login.php?kosong");
      exit();
    }
    else {
      $sql = "SELECT * FROM users WHERE no_akademik='$username'";
      $result = mysqli_query($conn, $sql);
      $resultCek = mysqli_num_rows($result);

      if ($resultCek < 1) {
        header("Location: login.php?invalidnim");
        exit();
      }
      else{
        if ($row = mysqli_fetch_assoc($result)) {
          //de hashing
          $hashedpasswordcek = password_verify($password, $row['password']);
          if ($hashedpasswordcek == false) {
            header("Location: login.php?invalidpwd&u=$username");
            exit();
          }
          elseif($hashedpasswordcek == true){
            $_SESSION['u_nama'] = $row['name'];
            $_SESSION['u_nim'] = $row['no_akademik'];
            header("Location: index.php");
            exit();
          }
        }
      }
    }
  }
  else {
    header("Location: login.php?error");
    exit();
  }

 ?>
