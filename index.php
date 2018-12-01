<?php
  session_start();

  require 'vendor/autoload.php';
  use Stichoza\GoogleTranslate\TranslateClient;
  require 'config.php';

  $teks = "";
  $styleBtn = "is-primary";
  $hasil = "";
  $button = "translateInd";
  $btnsaran = "suggestInd";
  $saran1 = "";
  $saran2 = "";
  $saran3 = "";
  $visibility = "hidden";
  $notif = "";
  $temp[] = "";
  date_default_timezone_set("Asia/Jakarta");

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['translateInd'])) {

    $id = strtolower(trim($_POST["sourceText"]));

    if(!empty($id)){

      global $conn;
      $visibility = "none";

      $teks = test_input($_POST["sourceText"]);
      $btnsaran = test_input("suggestInd");
      $kalimat = explode(". ", $id);

      if (count($kalimat) > 1) {
        //gabungkan array ke kalimat
        if (isset($_SESSION['u_nim'])) {
          $date = date('Y-m-d H:i:s');
          $nim = $_SESSION['u_nim'];
          $bhsindo = strtolower(trim($_POST["sourceText"]));
          $querycek = "SELECT indo FROM indonesias WHERE indo ='$bhsindo'";
          if (mysqli_num_rows(mysqli_query($conn, $querycek)) === 0) {

            mysqli_query($conn, "INSERT INTO indonesias VALUES('', '$bhsindo', '$nim','','','','$date','$date')");
          }
        }

        foreach ($kalimat as $key => $value) {
          //trim & strtolower
          $indo = strtolower(trim($value,"."));
          //query cek isi di db
          $queryindo = "SELECT id FROM indonesias WHERE indo = '$indo'";
          $cekisi = mysqli_query($conn, $queryindo);

          if (mysqli_num_rows($cekisi) === 1) { //jika ada isi maka ambil di db
              //ambil isi tabel
              $tabelindo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM indonesias WHERE indo = '$indo'"));
              $idindo = $tabelindo["id"];

              $queryarab = "SELECT * FROM arabs WHERE id_indo = '$idindo'";
              $tabelarab = mysqli_fetch_assoc(mysqli_query($conn, $queryarab));
              $hasilarab = $tabelarab["arab"];
              //masukkan di array hasil
              $temp[$key] = $hasilarab;
          }
          else { //jika tidak ada di db maka ambil di API
              $date = date('Y-m-d H:i:s');

              $tr = new TranslateClient("id","ar");
              $tr->setUrlBase('https://translate.googleapis.com/translate_a/single');
              $terjemaharab = $tr->translate($indo);

              //masukkan di array hasil
              $temp[$key] = $terjemaharab;

              mysqli_query($conn, "INSERT INTO indonesias VALUES('', '$indo', NULL, '', '','','$date','$date')");
              // }

              $panggilid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE indo = '$indo'"));
              $idindo = $panggilid["id"];
              // var_dump($idindo);
              // exit();

              mysqli_query($conn, "INSERT INTO arabs VALUES('', '$idindo', '$terjemaharab', '','','','$date','$date')");

          }
        }

        unset($value);
        $hasil = implode(". ", $temp);
      }
      else { //jika satu kalimat
          $bahasa = trim($id, ".");
          $query = mysqli_query($conn, "SELECT id FROM indonesias WHERE indo = '$bahasa' ");

          if(mysqli_num_rows($query) === 1 ) {

            // init id indo


            $panggilid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE indo = '$bahasa'"));
            $idindo = $panggilid["id"];

            $panggilhasilarab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM arabs WHERE id_indo = '$idindo'"));
            $hasil = $panggilhasilarab["arab"];
            $saran1 = test_input($panggilhasilarab["suggest_one"]);
            $saran2 = test_input($panggilhasilarab["suggest_two"]);
            $saran3 = test_input($panggilhasilarab["suggest_three"]);
            $button = test_input("translateInd");

          } else {

            $date = date('Y-m-d H:i:s');
            // Translate API

            $sourceText = trim(strtolower($_POST['sourceText']));
            $tr = new TranslateClient("id","ar");
            $tr->setUrlBase('https://translate.googleapis.com/translate_a/single');
            $hasil = $tr->translate($sourceText);


            if (isset($_SESSION['u_nim'])) {
              //ambil session nim
              $nim = $_SESSION['u_nim'];
              mysqli_query($conn, "INSERT INTO indonesias VALUES('',  '$sourceText', '$nim', '', '','','$date','$date')");
            }else{
              // Simpan ke database tanpa nim

              mysqli_query($conn, "INSERT INTO indonesias VALUES('', '$sourceText', NULL, '', '','','$date','$date')");
            }

            $panggilid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE indo = '$sourceText'"));
            $idindo = $panggilid["id"];
            // var_dump($idindo);
            // exit();

            mysqli_query($conn, "INSERT INTO arabs VALUES('', '$idindo', '$hasil', '','','','$date','$date')");
            $button = test_input("translateInd");
          }
      }

    }
    else{
      $notif = "Terjemahan Kosong";
    }

  }

  elseif (isset($_POST['translateArb'])) {

    $id = $_POST["sourceText"];

    if(!empty($id)){

    global $conn;
    $visibility = "none";

    $teks = test_input($_POST["sourceText"]);
    // $sourceLang = test_input("ar");
    $btnsaran = test_input("suggestArb");

    $query = mysqli_query($conn, "SELECT * FROM arabs WHERE arab = '$id' ");

    if(mysqli_num_rows($query) === 1 ) {

      // init id arab
      $text = $id;
      $panggilid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM arabs WHERE arab = '$text'"));
      $idindo = $panggilid["id_indo"];

      $panggilhasilindo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE id = '$idindo'"));
      $hasil = $panggilhasilindo["indo"];
      $saran1 = test_input($panggilhasilindo["suggest_one"]);
      $saran2 = test_input($panggilhasilindo["suggest_two"]);
      $saran3 = test_input($panggilhasilindo["suggest_three"]);
      $button = test_input("translateArb");

    } else {

    $date = date('Y-m-d H:i:s');
    // Translate API

    $sourceText = trim($_POST['sourceText']);
    $tr = new TranslateClient("ar","id");
    $tr->setUrlBase('https://translate.googleapis.com/translate_a/single');
    $result = $tr->translate($sourceText);
    $hasil = strtolower($result);

    $nim = $_SESSION['u_nim'];
    if (isset($_SESSION['u_nim'])) {
      mysqli_query($conn, "INSERT INTO indonesias VALUES('','', '$hasil', '', '','','$date','$date')");
    }
    else{
      // Simpan ke database tanpa nim

      mysqli_query($conn, "INSERT INTO indonesias VALUES('',  '$hasil', NULL, '', '','','$date','$date')");
    }

    $panggilid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE indo = '$hasil'"));
    $idindo = $panggilid["id"];

    mysqli_query($conn, "INSERT INTO arabs VALUES('', '$idindo', '$sourceText', '', '','','$date','$date')");
    $button = test_input("translateArb");
      }
    }

    else {
      $notif = "Terjemahan Kosong";
    }

  }

}

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

?>

<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tahsih Arabic</title>
  <link rel="icon" href="images/arabic-icon.png">
  <link rel="stylesheet" href="bulma/css/bulma.min.css">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" > -->
  <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>


  <!-- preloaded keyboard layout -->
  <script src="arabickeyboard/layouts/keyboard-layouts-greywyvern.js" charset="utf-8"></script>

  <style >

    @import url('https://fonts.googleapis.com/css?family=Montserrat:500,600');

    @import url(https://fonts.googleapis.com/earlyaccess/droidarabickufi.css);
    @import url(https://fonts.googleapis.com/earlyaccess/droidarabicnaskh.css);


    ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #f0f0f0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 10;
    }

    li {
        float: left;
    }

    li a {
        display: block;
        color: white;
        background-color: #2ccc99;
        text-align: center;
        padding: 5px 16px;
        text-decoration: none;
        margin: 9px;
        border-radius: 3px;
    }

    li a:hover:not(.active) {
        background-color: #1fa278;
        color: white;
    }


    .footer {
       position: fixed;
       left: 0;
       bottom: 0;
       width: 100%;
       padding: 10px;
       height: 40px;
       background-color: #f2f2f2;
       color: white;
       text-align: center;
       font-size: 12px;
    }
    .footer a{
      font-weight: bold;
      font-family: 'Roboto', sans-serif;
    }

    /* untuk textarea */


    .rtl {
      text-align: right;
    }

    .droid-arabic-kufi{font-family: 'Droid Arabic Kufi', serif;}
    .droid-arabic-naskh{font-family: 'Droid Arabic Naskh', serif;}
    .montserrat{font-family: 'Montserrat', sans-serif; font-size: 14px;}
  </style>

</head>

<body>

  <ul>
    <li><a href="https://tashih-arabic.com" style="background-color: transparent; margin-bottom: 2px; margin-top: 1px;"><img src="images/arabic-icon.png" style="width: 40px;"></a></li>
    <li style="margin-top: 13px; margin-left: -13px;">
      <h1 class="montserrat subtitle is-5">

      <?php
        if (isset($_SESSION['u_nim'])) {
          echo $_SESSION['u_nama'];
        }
        else {
          echo "Terjemahan";
        }
      ?>
      </h1>
    </li>
    <?php
      if (isset($_SESSION['u_nim'])) {

        echo '
          <li style="float: right; margin-right: 10px;"><a href="logout.php" class="montserrat"> Keluar &nbsp<i class="fas fa-sign-out-alt"></i></a></li>
            ';
      }
      else {
        echo '
          <li style="float: right; margin-right: 10px;"><a href="login.php" class="montserrat">Masuk &nbsp<i class="fas fa-sign-in-alt"></i></a></li>
            ';
      }
     ?>

  </ul>

  <section class="hero" style="margin-bottom:10px; margin-top: 50px;">
  <div class="hero-body" style="padding-top:10px; padding-bottom: 20px;">
    <div class="container">
      <!-- <h1 class="montserrat subtitle is-4">
        Terjemahan
      </h1> -->

    </div>
  </div>
</section>

  <div class="container" style="padding-bottom: 30px;">

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="columns" style="padding: 10px;">

        <div class="column">
          <div class="level" style="margin-bottom: 10px;">
            <div class="buttons has-addons" id="divBtn">
              <a class="montserrat button <?php if($button == "translateInd"){echo $styleBtn;} ?>" name="ButtonSource" id="btn-ind" >Indonesia</a>
              <a class="montserrat button <?php if($button == "translateArb"){echo $styleBtn;} ?>" name="ButtonSource" id="btn-ar" >Arab</a>
            </div>

          </div>

          <!-- sumber -->
          <textarea class="montserrat textarea has-fixed-size" id="textareaLang" name="sourceText" dir="" style="margin-bottom: 10px;"><?= $teks ?></textarea>


          <!-- Box Abstrak -->
          <?php
            if(isset($_SESSION['u_nim'])){
          ?>
          <div class="montserrat media" style="margin-top: 10px;">
             <p class="content is-small">Ketik teks atau <a data-target = "modal" id="mybtn">Terjemahkan file <i class="fa fa-upload" aria-hidden="true" style="padding-left: 3px;"></i></a></p>
          </div>
            <?php
          }
          ?>

        </div>

        <div class="column">

          <div class="level" style="margin-bottom: -14px; ">
            <div class="level-left" style=" margin-bottom: 8px;">
              <div class="buttons" >
                <div class="buttons has-addons">
                  <a class="montserrat button <?php if($button == "translateArb"){echo $styleBtn;} ?>" id="btn-ind2">Indonesia</a>
                  <a class="montserrat button <?php if($button == "translateInd"){echo $styleBtn;} ?>" id="btn-ar2">Arab</a>
                </div>
                <div class="buttons" style="margin-top: -23px; margin-left: 25px;">
                  <button class="montserrat button is-primary" id="translate" name="<?= $button; ?>" >Terjemahkan</button>
                </div>
              </div>
            </div>

          </div>

          <textarea  name="textareaTarget" class="droid-arabic-naskh textarea has-fixed-size" dir="rtl"><?= $hasil ?></textarea>

          <?php if (!empty($notif)) {
          ?>
          <p class="montserrat title is-6" style="margin: 7px;  visibility: none;">Terjemahan tidak ada</p>
          <?php
          } ?>

          <br>

          <article class="message is-primary" style="visibility: <?= $visibility; ?>;">
            <div class="message-body" >

              <?php
              if(empty($saran1)){
                ?>
              <p class="montserrat subtitle is-6">Saran tidak ada</p>

            <?php }else{  ?>
              <p class="montserrat subtitle is-6">Saran </p>
              <p class="<?php if (isset($_POST['translateInd'])) { ?>droid-arabic-naskh <?php }else{ ?>montserrat <?php } ?> subtitle is-6" dir="rtl">- &nbsp <?php echo $saran1; ?></p>
              <p class="<?php if (isset($_POST['translateInd'])) { ?>droid-arabic-naskh <?php }else{ ?>montserrat <?php } ?> subtitle is-6" dir="rtl">- &nbsp <?php echo $saran2; ?></p>
              <p class="<?php if (isset($_POST['translateInd'])) { ?>droid-arabic-naskh <?php }else{ ?>montserrat <?php } ?> subtitle is-6" dir="rtl">- &nbsp <?php echo $saran3; ?></p>
              <?php } ?>
            </div>
          </article>


          <!-- Box Revisi -->



        </div>

      </div>

    </form>

<?php

    if (isset($_SESSION['u_nim'])){

    $nim = $_SESSION['u_nim'];
    $result = mysqli_query($conn, "SELECT * FROM indonesias WHERE no_akademik = '$nim'");

    if (mysqli_num_rows($result)>0){
      while ($row = mysqli_fetch_assoc($result)) {
  ?>
  <div class="columns" style="padding: 10px;">
    <div class="column">

    <div class="montserrat box">
      <article class="media">

        <div class="media-content">
          <div class="content">
            <p>
              <strong>Abstrak</strong>
              <small style="float: right; color:rgb(31, 157, 108); font-weight: bold;"><?= $row['created_at']; ?></small>
              <br>
              <p style="text-align: justify; direction: ltr;">
              <?php
                echo $row['indo'];
               ?>
              </p>
            </p>
          </div>

        </div>
      </article>
    </div>

  </div>

    <div class="column">
  <?php
            $arab[] = "";
            $abstrakarb = "";
            $revisi = "";
            $idindo = $row['id'];
            $indonesia = $row['indo'];

            $kalimat = explode(". ", $indonesia);

            // var_dump($kalimat);
            // exit();

            if (count($kalimat) > 1) {
              foreach ($kalimat as $key => $value) {
                //trim & strtolower
                $indo = strtolower(trim($value,"."));
                //query cek isi di db
                $queryindo = "SELECT id FROM indonesias WHERE indo = '$indo'";
                $cekisi = mysqli_query($conn, $queryindo);

                if (mysqli_num_rows($cekisi) === 1) { //jika ada isi maka ambil di db
                    //ambil isi tabel
                    $tabelindo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM indonesias WHERE indo = '$indo'"));
                    $idindo = $tabelindo["id"];

                    $queryarab = "SELECT * FROM arabs WHERE id_indo = '$idindo'";
                    $tabelarab = mysqli_fetch_assoc(mysqli_query($conn, $queryarab));
                    $hasilarab = $tabelarab["arab"];
                    $create = $tabelarab["created_at"];
                    $update = $tabelarab["updated_at"];
                    if ($create != $update) {
                      $revisi = $update;
                    }
                    //masukkan di array hasil
                    $arab[$key] = $hasilarab;
                }

              }
              //gabungkan array ke kalimat

              unset($value);
              $abstrakarb = implode(". ", $arab);
            }
            else {
              $bahasa = strtolower(trim($indonesia, "."));
              $query = mysqli_query($conn, "SELECT id FROM indonesias WHERE indo = '$bahasa' ");

              if(mysqli_num_rows($query) === 1 ) {

                // init id indo


                $panggilid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE indo = '$bahasa'"));
                $idindo = $panggilid["id"];

                $panggilhasilarab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM arabs WHERE id_indo = '$idindo'"));
                $abstrakarb = $panggilhasilarab["arab"];


              }
            }


            // $panggilhasilarab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM arabs WHERE id_indo = '$idindo'"));
            // $recentarab = $panggilhasilarab["arab"];
            // $revisi = $panggilhasilarab["suggest_one"];
            $tglcreate = $row["created_at"];
            $tglupdate = $row["updated_at"];

  ?>
      <div class="box">
        <article class="media">

          <div class="media-content">
            <div class="montserrat content">
              <p>

                <strong class="montserrat">Revisi</strong>
                <small style="margin-left: 10px;">
                <?php if (!empty($revisi)): echo '<span class="tag is-success">Sudah direvisi</span>';

                else: echo '<span class="tag is-warning">Revisi tidak ada</span>';

                endif; ?>
                </small>
                <?php if (!empty($revisi)): ?>
                  <small style="float: right; color:rgb(31, 157, 108); font-weight: bold;"><?= $revisi; ?></small>
                <?php else: ?>
                  <small style="float: right; color:rgb(31, 157, 108); font-weight: bold;"><?= $row["created_at"]; ?></small>
                <?php endif; ?>
                <br>
                <p class="droid-arabic-naskh" style="font-size: 16px; line-height: 30px; direction: rtl; text-align: justify;">
                  <?php

                  if (count($kalimat) > 1) {
                    foreach ($kalimat as $key => $value) {
                      //trim & strtolower
                      $indo = strtolower(trim($value,"."));
                      //query cek isi di db
                      $queryindo = "SELECT id FROM indonesias WHERE indo = '$indo'";
                      $cekisi = mysqli_query($conn, $queryindo);

                      if (mysqli_num_rows($cekisi) === 1) { //jika ada isi maka ambil di db
                          //ambil isi tabel
                          $tabelindo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM indonesias WHERE indo = '$indo'"));
                          $idindo = $tabelindo["id"];

                          $queryarab = "SELECT * FROM arabs WHERE id_indo = '$idindo'";
                          $tabelarab = mysqli_fetch_assoc(mysqli_query($conn, $queryarab));
                          $hasilarab = $tabelarab["arab"];
                          $create = $tabelarab["created_at"];
                          $update = $tabelarab["updated_at"];
                          //seleksi jika diedit
                          if ($create != $update) {
                            echo '<mark style="background-color: hsl(141, 71%, 48%); color: white;">'.$hasilarab.'</mark>.&nbsp';

                          } else {
                            echo $hasilarab.'.&nbsp';
                          }

                          //masukkan di array hasil
                          // $arab[$key] = $hasilarab;
                      }

                    }
                      //gabungkan array ke kalimat

                      unset($value);
                      // $abstrakarb = implode(". ", $arab);
                    }
                    else {
                      $bahasa = strtolower(trim($indonesia, "."));
                      $query = mysqli_query($conn, "SELECT id FROM indonesias WHERE indo = '$bahasa' ");

                      if(mysqli_num_rows($query) === 1 ) {

                        // init id indo


                        $panggilid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE indo = '$bahasa'"));
                        $idindo = $panggilid["id"];

                        $panggilhasilarab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM arabs WHERE id_indo = '$idindo'"));
                        $hasilarab = $panggilhasilarab["arab"];
                        $create = $panggilhasilarab["created_at"];
                        $update = $panggilhasilarab["updated_at"];
                        //seleksi jika diedit
                        if ($create != $update) {
                          echo '<mark>'.$hasilarab.'</mark>.&nbsp';
                        } else {
                          echo $hasilarab.'.&nbsp';
                        }

                      }
                    }

                    // echo $abstrakarb;

                  ?>
                </p>
                <br>
                <form action="doc.php?id=<?= $row['id']; ?>" method="POST" target="_blank">
                  <button class="montserrat button is-white is-small" name="buka" style="color:rgb(31, 157, 108); font-weight: bold;" >Buka file</button>
                </form>
              </p>
            </div>

          </div>
        </article>
      </div>
    </div>

  </div>
    <?php
      }
    }
  }
?>


<?php if(isset($_SESSION['u_nim'])): ?>
    <form action="doc.php" method="POST" enctype="multipart/form-data" target="_blank">
      <div class="modal" id="mymodal">
        <div class="modal-background"></div>
        <div class="modal-content">
          <div class="box is-info" style="margin-top: 200px">
            <section class="modal-card-body">
              <div class="field">
                <label class="label">Masukkan Berkas (.pdf)</label>
                <div class="control">
                  <input class="input" name="file" id="file" type="file" placeholder="File">
                  <button class="button is-link is-small" style="margin-top: 5px;" type="submit" name="submit">Submit</button>
                </div>
              </div>
            </section>
          </div>
        </div>
        <a class="modal-close is-large" aria-label="close" id="btnclose"></a>
      </div>
    </form>
<?php endif; ?>

  </div>

  <div class="montserrat footer" style="z-index: 10;">
    <p style="color: #515151; font-size: 12px;"><a href="tashih-arabic.com" style="color: hsl(171, 100%, 41%);">Tashih Arabic &nbsp</a><i class="fas fa-code"></i>&nbsp with &nbsp<i class="fas fa-heart"></i>&nbsp by <a href="https://uin-suska.ac.id" style="color: hsl(171, 100%, 41%);">UIN Suska RIAU</a></p>
  </div>

  <script src="main.js"></script>
  <script type="text/javascript">

    let btn = document.getElementById('mybtn');
    let modal = document.getElementById('mymodal');
    let btnclose = document.getElementById('btnclose');

    btn.onclick = function() {
      modal.style.display = "block";
    }

    btnclose.onclick = function(){
      modal.style.display = "none";
    }

    // modal.onclick = function(){
    //   modal.style.display = "none";
    // }

	</script>

</body>
</html>
