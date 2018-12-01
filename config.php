<?php
$conn = mysqli_connect("localhost", "root", "", "db_tahsih");
mysqli_set_charset($conn,"utf8");

function query ($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
	return $rows;
}

function tambah ($data) {
	global $conn;

	$gambar = upload();

	if (!$gambar) {
		return false;
	}

	mysqli_query($conn, "INSERT INTO temp_file VALUES(null, '$gambar')");

	return mysqli_affected_rows($conn);

	}


function upload() {

	$namaFile = $_FILES['file']['name'];
	$ukuranFile = $_FILES['file']['size'];
	$error = $_FILES['file']['error'];
	$tmpName = $_FILES['file']['tmp_name'];

	// cek ekstensi gambar
	$ekstensiGambarValid = ['pdf'];
	$ekstensiGambar = explode('.', $namaFile);
	$ekstensiGambar = strtolower(end($ekstensiGambar));

	if ( !in_array($ekstensiGambar, $ekstensiGambarValid)) {
		echo "<script>
				alert('Ekstensi file harus pdf');
			</script>";

			header("Location: login.php?to=daftar&nimhastaken&nama=salah&email=salah");
			exit();
	}

	// cek ukuran gambar
	if ($ukuranFile > 10000000) {
		echo "<script>
				alert('Ukuran gambar harus dibawah 1 Mb');
			</script>";
			return false;
	}
	//cek

	move_uploaded_file($tmpName, 'doc/' . $namaFile);

	return $namaFile;

}
