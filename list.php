<?php
	$con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");
	
	if (mysqli_connect_errno()){
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$query = "SELECT jabatan, aksesLevel FROM tbluser WHERE uname='$_SESSION[username]'";
	$sql = mysqli_query($con,$query);
	$recorduser = mysqli_fetch_array ($sql);
	$jabatan = $recorduser['jabatan'];
	$aksesLevel = $recorduser['aksesLevel'];
	
	if (isset($_GET['tgl'])) {
		$tgl = $_GET['tgl'];		
	} elseif (empty($_GET['tgl'])){
		$tgl = date('Y-m-d', time()+86400);
	} 
	
	if (isset($_GET['rub'])) {
		$rub = $_GET['rub'];
	} elseif (empty($_GET['rub'])){
		$rub = "";
	}
	
	$tomorow = date('Y-m-d', time()+86400);	
	$tomorow = ($tomorow==$tgl) ? $tomorow : $tgl;
	$query = "SELECT tblhalaman.Halaman as Hal, tblrubrik.namaRubrik AS Rubrik, 
			tblberita.judulBerita AS Judul, tblberita.isiBerita AS Naskah, 
			tblberita.idBerita As idBerita, tbluser.uname As Penulis, 	
			tblberita.statusBerita AS statusBerita, tblberita.editor AS Editor, 
			tblberita.kota AS Kota 
			FROM tblberita 
			LEFT JOIN tblhalaman ON tblberita.halaman = tblhalaman.idHalaman 
			LEFT JOIN tblrubrik ON tblberita.rubrikBerita = tblrubrik.idRubrik 
			LEFT JOIN tbluser ON tblberita.penulisBerita = tbluser.iduser 
			WHERE tglBerita='$tomorow' AND statusBerita <> 3 AND tblrubrik.namaRubrik like '%$rub%'
			ORDER BY tblberita.statusBerita, tblhalaman.Halaman ASC ";
	// echo $query;
	$sql = mysqli_query($con,$query);

	
?>
	<form class="form-inline" action="listFilter.php">
		<div class="controls input-append date datepicker" id="tgl">   
			<input type="text" name="tgl" required readonly><span class="add-on"><i class="icon-calendar"></i></span>
		</div>
		<select name="rub" style="width:auto;" >			
			<option></option>
			<?php 
			$query3="SELECT namaRubrik, idRubrik FROM tblrubrik WHERE aktif='1'";
			$sql3 = mysqli_query($con,$query3);
			while ($record3 = mysqli_fetch_array ($sql3)) {							
			?>
			<option value="<?=$record3['namaRubrik'];?>" ><?=$record3['namaRubrik'];?></option>
			<?php } ?>
		</select>	
		<button type="submit" class="btn btn-primary btn-small">Go</button>
	</form>	
	
	<table  class="table table-striped" style="width:auto">  
        <tbody>  
		<?php
			while ($record = mysqli_fetch_array ($sql)) {

			$queryEditor = "SELECT tbluser.uname AS Editor ".
					"FROM tbluser ".
					"LEFT JOIN tblberita_lock ON tbluser.iduser = tblberita_lock.username ".
					"WHERE tblberita_lock.idBerita = $record[idBerita] ".
					"ORDER BY tgl DESC ".
					"LIMIT 1";
			$sqled = mysqli_query ($con,$queryEditor);
			$recorded = mysqli_fetch_array ($sqled); 

			if ($record['Editor']==null) {
				$editor =  $record['Editor'];
			} else {
				$editor = $recorded['Editor'];
			}
			// echo $editor;
					
			$queryCreated = "SELECT createdAt FROM tblberita_log 
				WHERE idBerita='$record[idBerita]'
				ORDER BY createdAt ASC	
				LIMIT 1";
			$sqlcre = mysqli_query($con,$queryCreated);
			$recordcre = mysqli_fetch_array ($sqlcre);
			$createdat = $recordcre['createdAt'];
			$createdat = strtotime($createdat) + 25200; // Add 7 hour
			$createdat = date('Y-m-d H:i:s', $createdat );
			
			$queryLastEdit = "SELECT createdAt AS edited FROM tblberita_log 
				WHERE idBerita='$record[idBerita]' AND layouter='0'  
				ORDER BY edited DESC
				LIMIT 1";
			$sqlled = mysqli_query($con,$queryLastEdit);
			$recordled = mysqli_fetch_array ($sqlled);
			$tglLastEdit = $recordled['edited'];
			$tglLastEdit = strtotime($tglLastEdit ) + 25200; // Add 7 hour
			$tglLastEdit = date('Y-m-d H:i:s', $tglLastEdit );
		?>
			<tr>
			<td>
				<?php 
					if ($jabatan=='Reporter'){
						if ($record['Penulis']==$_SESSION['username'] and $record['statusBerita']==0){ ?>
						<b><a href="index.php?screen=edit&idBerita=<?=$record['idBerita'];?>" rel="tooltip" data-original-title="Click to Edit"><font size="4"><?=$record['Rubrik']."</font> | ".$record['Judul']." | ".$record['Hal'];?></a></b></br>
						<?php
							$isiberita = $record['Naskah'];
							$isiberita = html_entity_decode($isiberita);
							$paragrap1 = (explode("</p>",$isiberita));
							echo $paragrap1[0];
							//."1-1"."-".$record['statusBerita']."-";
							readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,$_SESSION['namaLengkap']);
							echo "<pre>Writer : ".$record['Penulis']." [".$createdat."] | "."Last Editor : ".$editor." [".$tglLastEdit."]</pre>";
						}
						elseif ($record['Penulis']!=$_SESSION['username']){ ?>
							<b><font size="4"><?=$record['Rubrik']."</font> | ".$record['Judul']." | ".$record['Hal'];?></b></br>
							<?php
							$isiberita = $record['Naskah'];
							$isiberita = html_entity_decode($isiberita);
							$paragrap1 = (explode("</p>",$isiberita));
							echo $paragrap1[0]."<br/>";//."1-1"."-".$record['statusBerita']."-";
							readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,namaLengkap($con,$record['idBerita']));
							echo "<pre>Writer : ".$record['Penulis']." [".$createdat."] | "."Last Editor : ".$editor." [".$tglLastEdit."]</pre>";
						}
						elseif ($record['statusBerita']!=0){ ?>
							<b><font size="4"><?=$record['Rubrik']."</font> | ".$record['Judul']." | ".$record['Hal'];?></b></br>
							<?php
							$isiberita = $record['Naskah'];
							$isiberita = html_entity_decode($isiberita);
							$paragrap1 = (explode("</p>",$isiberita));
							echo $paragrap1[0]."<br/>";//."1-1"."-".$record['statusBerita']."-";
							readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,namaLengkap($con,$record['idBerita']));
							echo "<pre>Writer : ".$record['Penulis']." [".$createdat."] | "."Last Editor : ".$editor." [".$tglLastEdit."]</pre>";
						}
					} 
					elseif ($jabatan=='Redaktur') {
						if (($record['Penulis']==$_SESSION['username'] and $record['statusBerita']!=2) or ($recorded['Editor']!=$_SESSION['username'] and $record['statusBerita']==0) or ($record['Penulis']!=$_SESSION['username'] and $recorded['Editor']==$_SESSION['username'] and $record['statusBerita']==1) or ($record['Penulis']!=$_SESSION['username'] and $record['statusBerita']==0)) { ?>
						<b><a href="index.php?screen=edit&idBerita=<?=$record['idBerita'];?>" rel="tooltip" data-original-title="Click to Edit"><font size="4"><?=$record['Rubrik']."</font> | ".$record['Judul']." | ".$record['Hal'];?></a></b></br>
						<?php
							$isiberita = $record['Naskah'];
							$isiberita = html_entity_decode($isiberita);
							$paragrap1 = (explode("</p>",$isiberita));
							echo $paragrap1[0]."<br/>";//."1-1"."-".$record['statusBerita']."-";
							readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,namaLengkap($con,$record['idBerita']));
							echo "<pre>Writer : ".$record['Penulis']." [".$createdat."] | "."Last Editor : ".$editor." [".$tglLastEdit."]</pre>";
						}
						elseif (($recorded['Editor']!=$_SESSION['username'] and $record['statusBerita']==1) or $record['statusBerita']==2){ ?>
							<b><font size="4"><?=$record['Rubrik']."</font> | ".$record['Judul']." | ".$record['Hal'];?></b></br>
							<?php
							$isiberita = $record['Naskah'];
							$isiberita = html_entity_decode($isiberita);
							$paragrap1 = (explode("</p>",$isiberita));
							echo $paragrap1[0]."<br/>";//."1-1"."-".$record['statusBerita']."-";
							readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,namaLengkap($con,$record['idBerita']));
							echo "<pre>Writer : ".$record['Penulis']." [".$createdat."] | "."Last Editor : ".$editor." [".$tglLastEdit."]</pre>";								
						}
					}
					elseif ($jabatan=='Redaktur Pelaksana' or $jabatan=='Pemimpin Redaksi') {
						if ($record['statusBerita']<=1){ ?>
							<b><a href="index.php?screen=edit&idBerita=<?=$record['idBerita'];?>" rel="tooltip" data-original-title="Click to Edit"><font size="4"><?=$record['Rubrik']."</font> | ".$record['Judul']." | ".$record['Hal'];?></a></b></br>
							<?php
								$isiberita = $record['Naskah'];
								$isiberita = html_entity_decode($isiberita);
								$paragrap1 = (explode("</p>",$isiberita));
								echo $paragrap1[0]."<br/>";//."1-1"."-".$record['statusBerita']."-";
								readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,namaLengkap($con,$record['idBerita']));
								echo "<pre>Writer : ".$record['Penulis']." [".$createdat."] | "."Last Editor : ".$editor." [".$tglLastEdit."]</pre>";
						}
						elseif ($record['statusBerita']==2){ ?>
							<b><font size="4"><?=$record['Rubrik']."</font> | ".$record['Judul']." | ".$record['Hal'];?></b></br>
							<?php
							$isiberita = $record['Naskah'];
							$isiberita = html_entity_decode($isiberita);
							$paragrap1 = (explode("</p>",$isiberita));
							echo $paragrap1[0]."<br/>";//."1-1"."-".$record['statusBerita']."-";
							readmore($record['idBerita'],$record['Judul'],$record['Kota'],$isiberita,namaLengkap($con,$record['idBerita']));
							echo "<pre>Writer : ".$record['Penulis']." [".$createdat."] | "."Last Editor : ".$editor." [".$tglLastEdit."]</pre>";
						}
					}
					elseif ($jabatan=='Layouter' or $jabatan=='Grafis') { ?>
						<b><font size="4"><?=$record['Rubrik']."</font> | ".$record['Judul']." | ".$record['Hal'];?></b></br>
						<?php
						$string = $record['Naskah'];
						$string = html_entity_decode($string);
						$string = (explode("</p>",$string));
						echo $string[0]."<br/>"."<br/>";//."4-1"."-".$record['statusBerita']."-";
						echo "<pre>Writer : ".$record['Penulis']." [".$createdat."] | "."Last Editor : ".$editor." [".$tglLastEdit."]</pre>";
						if ($record['statusBerita']==2) {
							echo "<br/><a href='#'>Get Text</a>";
						}
					}
				?>	
			</td>
		</tr>
			
		<?php 
			}
		?>
        </tbody>  
    </table>
		

