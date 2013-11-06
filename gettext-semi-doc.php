<?php 
	// source code 
	// http://stackoverflow.com/questions/11313098/display-mode-of-word-document-generated-in-php
	$con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");
	
	if (isset($_GET['idBerita'])) {
		$idBerita = $_GET['idBerita'];
	} else {
		die ("Error. No idBerita Selected! ");	
	}
	
	$query = "SELECT tblHalaman.Halaman as Hal, tblrubrik.namaRubrik AS Rubrik,  
				tblberita.judulBerita AS Judul, tblberita.kota AS Kota, 
				tblberita.isiBerita AS Naskah, tbluser.uname As Penulis,
				tblberita.penulisBerita AS idPenulis
				FROM tblberita 
				LEFT JOIN tblhalaman ON tblberita.halaman = tblhalaman.idHalaman 
				LEFT JOIN tblrubrik ON tblberita.rubrikBerita = tblrubrik.idRubrik 
				LEFT JOIN tbluser ON tblberita.penulisBerita = tbluser.iduser
				WHERE idBerita='$idBerita'";
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
	$string = html_entity_decode($string);
	$filename = $record['Hal']."-".str_replace(" ", "_", $record['Judul']);
	$content = $record['Judul']."<br/><br/><b>".$record['Kota']."</b><br/>".$string."".$namaLengkap;
	
	
	// save as html
	// header("Content-Type: text/html");
	// header("Content-Disposition: attachment; filename='$filename.html'");
	// echo "<html xmlns:o='urn:schemas-microsoft-com:office:office'xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><body>";
	// echo $content;
	// echo "</body></html>";
	
	
	
	// save as doc
	// header("Content-Description: File Transfer");
	header("Content-Type: application/vnd.ms-word");
	header("Content-Disposition: attachment; filename=$filename.doc");
	// header("Content-Transfer-Encoding: binary");
	// header("Expires: 0");
	// header("Cache-Control: must-revalidate");
	// header("Pragma: public");
	// header("Content-Length: " . filesize($doc));
	// ob_clean();
	// flush();
	?>
	<html>
	</head>
	<body>
	<?=$record['Judul'];?></br>
	<?=$content;?>
	</body>
	</html>
	