<?php 
	require_once 'plugin/pathToPHPDocX/classes/CreateDocx.inc';
	require_once 'inc/function.php';
	date_default_timezone_set("Asia/Jakarta");
	
	$con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");
	
	if (isset($_GET['idBerita']) and isset($_GET['idUser'])) {
		$idBerita = $_GET['idBerita'];
		$idLayout = $_GET['idUser'];
	} else {
		die ("Error. No idBerita Selected! ");	
	}

	$query = "INSERT INTO tbllayout (idBerita, userLayout) values ('$idBerita','$idLayout')";
	$sql = mysqli_query ($con,$query);
	
	$query = "UPDATE tblberita SET layouter='1' WHERE idBerita='$idBerita'";
	$sql = mysqli_query ($con,$query);
	
	$query = "SELECT tblhalaman.Halaman as Hal, tblrubrik.namaRubrik AS Rubrik, ". 
				"tblberita.judulBerita AS Judul, tblberita.kota AS Kota, ". 
				"tblberita.isiBerita AS Naskah, tbluser.uname As Penulis, ".
				"tblberita.penulisBerita AS idPenulis ".
				"FROM tblberita ".
				"LEFT JOIN tblhalaman ON tblberita.halaman = tblhalaman.idHalaman ".
				"LEFT JOIN tblrubrik ON tblberita.rubrikBerita = tblrubrik.idRubrik ".
				"LEFT JOIN tbluser ON tblberita.penulisBerita = tbluser.iduser ".
				"WHERE idBerita='$idBerita'";
	$sql = mysqli_query ($con,$query);
	$record = mysqli_fetch_array ($sql);
	
	$idpenulis = $record['idPenulis'];
	
	$query1 = "SELECT firstName, midleName, lastName FROM tbluser WHERE iduser='$idpenulis'";
	$sql1 = mysqli_query ($con,$query1);
	$record1 = mysqli_fetch_array ($sql1);
	
	$fname = $record1['firstName'];
	$mname = $record1['midleName'];
	$lname = $record1['lastName'];
	$namaLengkap = $fname." ".$mname." ".$lname;
	
	$string = $record['Naskah'];
	$string1 = html_entity_decode($string);
	
	//html content
	$content = "<H2>".$record['Judul']."</H2>"."<br/><br/><b>".$record['Kota']." (HN)</b>".$string1."".$namaLengkap;
	
	$periode = date('Y-m'); //periode tahun bulan
	$tglBerita = date('d', time()+86400); //tanggal besok
	
	// create folder if not exist
	if (!file_exists('naskah')) {
   		mkdir('naskah', 0777, true);
	}
	$dailyNaskah = 'naskah/'.$periode.'/'.$tglBerita;
	
	if (!file_exists($dailyNaskah)) {
   		mkdir($dailyNaskah, 0777, true);
	}
	
	$fp = fopen($dailyNaskah."/".fileHtmlName($record['Hal'],$record['Judul']).".html","wb");
	fwrite($fp,$content);
	fclose($fp);
	
	header("location:index.php");
?>