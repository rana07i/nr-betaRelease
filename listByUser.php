<?php
	$con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");
	
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$query = "SELECT jabatan FROM tbluser WHERE uname='$_SESSION[username]'";
	$sql = mysqli_query($con,$query);
	$recorduser = mysqli_fetch_array ($sql);
	$jabatan = $recorduser['jabatan'];
	
	$tomorow = date('Y-m-d', time()+86400);		
	$query = "SELECT tblhalaman.Halaman as Hal, tblrubrik.namaRubrik AS Rubrik, ".
				"tblberita.judulBerita AS Judul, tblberita.isiBerita AS Naskah, ".
				"tblberita.idBerita As idBerita, tblberita.statusBerita AS statusBerita, ".
				"tblberita.kota AS Kota ".
				"FROM tblberita ".
				"LEFT JOIN tblhalaman ON tblberita.halaman = tblhalaman.idHalaman ".
				"LEFT JOIN tblrubrik ON tblberita.rubrikBerita = tblrubrik.idRubrik ".
				"WHERE penulisBerita='$_SESSION[iduser]' AND tglBerita='$tomorow'".
				"ORDER BY tblberita.statusBerita, tblhalaman.Halaman ASC ".
				"LIMIT 0, 10";	
	// echo $query;
	$sql = mysqli_query($con,$query);
	
?>
	<!-- legend>List Berita </legend -->  
	<table  class="table table-striped" style="width:auto">    
        <tbody>  
			<?php
				while ($record = mysqli_fetch_array ($sql)) {	
					$queryCreated = "SELECT createdAt FROM tblberita_log 
						WHERE idBerita='$record[idBerita]'
						ORDER BY createdAt ASC	
						LIMIT 1";
					$sqlExecute = mysqli_query($con,$queryCreated);
					$record1 = mysqli_fetch_array ($sqlExecute);
					$createdat = $record1['createdAt'];
					$createdat = strtotime($createdat ) + 25200; // Add 7 hour
					$createdat = date('Y-m-d H:i:s',$createdat);			 
		    ?>
			<tr>
				<td>
					<?php 
						if (($jabatan=="Reporter" and $record['statusBerita']==0) or ($jabatan=="Redaktur" and $record['statusBerita']==1) or (($jabatan=="Redaktur Pelaksana" or $jabatan=="Pemimpin Redaksi") and $record['statusBerita']==2)){ ?>
							<a href="index.php?screen=edit&idBerita=<?=$record['idBerita'];?>" rel="tooltip" data-original-title="Click to Edit"><?=$record['Rubrik']." | ".$record['Judul']." | ".$record['Hal'];?></a> </br>
							
							<?php
							$isiberita = $record['Naskah'];
							$isiberita = html_entity_decode($isiberita);
							$paragrap1 = (explode("</p>",$isiberita));
							echo $paragrap1[0]." [<font style='color:red;'>".$createdat."</font>]";
								readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,$_SESSION['namaLengkap'])."</div>";
						} 
						else { ?>
							<?=$record['Rubrik']." | ".$record['Judul']." | ".$record['Hal'];?></br>
							<?php
							$isiberita = $record['Naskah'];
							$isiberita = html_entity_decode($isiberita);
							$paragrap1 = (explode("</p>",$isiberita));
							echo $paragrap1[0]." [<font style='color:red;'>".$createdat."</font>]";
							readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,$_SESSION['namaLengkap']);
						}
					?>
				</td>
			</tr>
				
			<?php 
				}	
			?>
        </tbody>  
    </table>
		

