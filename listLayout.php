<?php
	
	$con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");
	
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	// get variabel jabatan
	$query = "SELECT jabatan, aksesLevel FROM tbluser WHERE uname='$_SESSION[username]'";
	$sql = mysqli_query($con,$query);
	$recorduser = mysqli_fetch_array ($sql);
	$jabatan = $recorduser['jabatan'];
	
	//$query = "SELECT tbluser.uname FROM tblberita
	//		INNER JOIN tbllayout ON tblberita.idBerita = tbllayout.idBerita
	//		INNER JOIN tbluser ON tbllayout.userLayout = tbluser.iduser
	//		WHERE tblberita.idBerita =''";
	//$sql = mysqli_query($con,$query);
	//$recordlayouter = mysqli_fetch_array ($sql);
	
	$tomorow = date('Y-m-d', time()+86400);		
	$query = "SELECT tblhalaman.Halaman as Hal, tblrubrik.namaRubrik AS Rubrik, ".
				"tblberita.judulBerita AS Judul, tblberita.isiBerita AS Naskah, ".
				"tblberita.idBerita As idBerita, tbluser.uname As Penulis, ".
				"tblberita.statusBerita AS statusBerita, tblberita.layouter as Layouter, ".
				"tblberita.createdAt AS Stamp ".
				"FROM tblberita ".
				"LEFT JOIN tblhalaman ON tblberita.halaman = tblhalaman.idHalaman ".
				"LEFT JOIN tblrubrik ON tblberita.rubrikBerita = tblrubrik.idRubrik ".
				"LEFT JOIN tbluser ON tblberita.penulisBerita = tbluser.iduser ".
				"WHERE tglBerita='$tomorow' and statusBerita='3' ".
				"ORDER BY tblberita.statusBerita, tblhalaman.Halaman ASC ";
	$sql = mysqli_query($con,$query);
	//echo $query;
?>
	<!-- legend>List Berita </legend -->  
	<table  class="table table-striped" style="width:auto">  
        <tbody>  
			<?php
				while ($record = mysqli_fetch_array ($sql)) {
					$stamp = $record['Stamp'];
					$stamp = strtotime($stamp ) + 25200; // Add 1 hour
					$stamp = date('Y-m-d H:i:s', $stamp); // Back to string
				
					$queryLastEdit = "SELECT createdAt as edited FROM tblberita_log 
						WHERE idBerita='$record[idBerita] AND editor is not null' 
						ORDER BY edited DESC
						LIMIT 1";
					$sqlled = mysqli_query($con,$queryLastEdit);
					$recordled = mysqli_fetch_array ($sqlled);
					$tglLastEdit = $recordled['edited'];
		    ?> 
			<tr>
				<td>
					<?php if ($record['Layouter']==0) { ?>
						<?=$record['Hal']." | ".$record['Rubrik']." <br/><b> ".$record['Judul'];?></b>
						<?php
						$string = $record['Naskah'];
						$string = html_entity_decode($string);
						$string = (explode("</p>",$string));
						echo $string[0]; 
						if ($jabatan=='Layouter' or $jabatan=='Grafis') { ?>
							<br/><a href="layoutStamp.php?idBerita=<?=$record['idBerita'];?>&idUser=<?=$_SESSION['iduser'];?>"><div>Stamp for Layout</div></a> 
						<?php 
						} else {
							echo "<br/><div style:'background-color:yellow;'><font style='color:red;text-decoration:blink;'>Menunggu Layout</font></div>";
						}
						echo "<pre>Penulis : ".$record['Penulis']." [".$tglLastEdit."]</pre>";
					} 
					elseif ($record['Layouter']==1){
						$query1 = "SELECT tbluser.uname FROM tblberita
								INNER JOIN tbllayout ON tblberita.idBerita = tbllayout.idBerita
								INNER JOIN tbluser ON tbllayout.userLayout = tbluser.iduser
								WHERE tblberita.idBerita ='$record[idBerita]'";
						$sql1 = mysqli_query($con,$query1);
						$recordlayouter = mysqli_fetch_array ($sql1);	
						$layouter = $recordlayouter['uname'];					
				
						if ($jabatan=='Layouter' or $jabatan=='Grafis'){ ?>
							 <a href="gettext.php?idBerita=<?=$record['idBerita'];?>" rel="tooltip" data-original-title="Click to Get Text"><?=$record['Hal']." | ".$record['Rubrik']." <br/><b> ".$record['Judul'];?></a></b>
						<?php
						} 
						else { ?>					
							<?=$record['Hal']." | ".$record['Rubrik']." <br/><b> ".$record['Judul'];?></b>
						<?php	
						}	
						
						$string = $record['Naskah'];
						$string = html_entity_decode($string);
						$string = (explode("</p>",$string));
						echo $string[0]."<br/>";
						
						echo "<pre>Penulis : ".$record['Penulis']." [".$tglLastEdit."]<div>Layouter : ".$layouter."<font style='color:red;'> [".$stamp."]</font></div></pre>";
						 
					}
					?>
					
					
					
				</td>
			</tr>
				
			<?php 
				}	
				// mysqli_close($con);
			?>
        </tbody>  
    </table>
		

