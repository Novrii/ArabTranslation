<?php
session_start();
require 'config.php';
// Include Composer autoloader if not already done.
include 'vendor/autoload.php';
use Stichoza\GoogleTranslate\TranslateClient;
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
date_default_timezone_set("Asia/Jakarta");


if ( isset($_POST["submit"]) ) {
    global $conn;
    // $hasil="";
    // var_dump("haha");
    // exit();

    if ( tambah ($_POST) > 0 ) {
        $date = date('Y-m-d H:i:s');

        $file = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM temp_file"));
        $data = $file["file"];

        $parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile('doc/'.$data);

        $text = $pdf->getText();
        $sourceText = strtolower(trim($text));

        $temp[]="";

        $kalimat = explode(". ", $sourceText);

        // var_dump($kalimat);
        // exit();

        if (count($kalimat) > 1) {

          if (isset($_SESSION['u_nim'])) {
            $date = date('Y-m-d H:i:s');
            $nim = $_SESSION['u_nim'];
            $bhsindo = strtolower(trim($sourceText));
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

                mysqli_query($conn, "INSERT INTO indonesias VALUES('', '$indo', NULL,  '', '','','$date','$date')");
                
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

        unlink('doc/'.$data);

        mysqli_query($conn, "TRUNCATE temp_file");

    }
}
elseif (isset($_POST['buka'])) {
  if (isset($_GET['id'])) {
    $temp[] = "";
    // code...
    $id = $_GET['id'];
    $tabelindo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE id = '$id'"));
    $abstrak = $tabelindo["indo"];

    $kalimat = explode(". ", $abstrak);

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
            //masukkan di array hasil
            $temp[$key] = $hasilarab;
        }

      }
      //gabungkan array ke kalimat

      unset($value);
      $hasil = implode(". ", $temp);
    }
    else {
      $bahasa = strtolower(trim($abstrak, "."));
      $query = mysqli_query($conn, "SELECT id FROM indonesias WHERE indo = '$bahasa' ");

      if(mysqli_num_rows($query) === 1 ) {

        // init id indo
        $panggilid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM indonesias WHERE indo = '$bahasa'"));
        $idindo = $panggilid["id"];

        $panggilhasilarab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM arabs WHERE id_indo = '$idindo'"));
        $hasil = $panggilhasilarab["arab"];

      }
    }


  }

}

    //buat file pdf
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


    $pdf->SetTitle('Hasil Terjemahan');

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    // $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language dependent data:
    $lg = Array();
    $lg['a_meta_charset'] = 'UTF-8';
    $lg['a_meta_dir'] = 'rtl';
    $lg['a_meta_language'] = 'fa';
    $lg['w_page'] = 'page';

    // set some language-dependent strings (optional)
    $pdf->setLanguageArray($lg);

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('dejavusans', '', 16);

    // add a page
    $pdf->AddPage();

    // Persian and English content
    // $htmlpersian = '<span color="#660000">Persian example:</span><br />سلام بالاخره مشکل PDF فارسی به طور کامل حل شد. اینم یک نمونش.<br />مشکل حرف \"ژ\" در بعضی کلمات مانند کلمه ویژه نیز بر طرف شد.<br />نگارش حروف لام و الف پشت سر هم نیز تصحیح شد.<br />با تشکر از  "Asuni Nicola" و محمد علی گل کار برای پشتیبانی زبان فارسی.';
    $pdf->WriteHTML($hasil, true, 0, true, 0);
    $pdf->Output('Terjemahan.pdf', 'I');
?>
