<?php

	$con=mysqli_connect("localhost","root","","newsroom");
	
	if (isset($_GET['idBerita'])) {
		$idBerita = $_GET['idBerita'];
	} else {
		die ("Error. No idBerita Selected! ");	
	}
	
	// validasi lockedit for berita
	$query = "SELECT id as idlock, lockStatus,username from tblberita_lock 
			  WHERE idBerita='$idBerita' 
			  ORDER BY tgl DESC LIMIT 1";
	$sql = mysqli_query($con,$query);
	$record = mysqli_fetch_array ($sql);

	//proses update berita
	if (isset($_POST['updateData'])) {
		
		$isiBerita = htmlentities(addslashes($_POST['content']));
		$judul = htmlentities(addslashes($_POST['judul']));
		// $data = explode (';;',$naskah);
		// //echo count($data);
		// if (count($data)!=3) {
			// echo 	"<div class='alert alert-error'>  
					  // <a class='close' data-dismiss='alert'>&times;</a>  
					  // <strong>Ulangi!</strong> Ada Kelasahan Pada Input Naskah.  
					// </div>"; 
			// exit;
		// } else {
			// $judul = ltrim($data[0]);
			// $kota = ltrim($data[1]);
			// $isiBerita = ltrim($data[2]);
		// }
		
		if ($_SESSION['jabatan']=='Reporter') {
				$statusBerita = '0';
			} elseif ($_SESSION['jabatan']=='Redaktur'){
				$statusBerita = '1';
			} else {
				$statusBerita = '3';
			}
			
		$con=mysqli_connect("localhost","root","","newsroom");
		
		$query = "UPDATE tblberita SET halaman='$_POST[hal]', rubrikBerita='$_POST[rub]', judulBerita='$judul', kota='$_POST[kota]', isiBerita='$isiBerita', editor='$_SESSION[iduser]', statusBerita='$statusBerita' WHERE idBerita='$idBerita'";
		$sql = mysqli_query ($con,$query);
		// echo $query."<br>";
		
		$query = "UPDATE tblberita_lock SET lockStatus='0' WHERE idBerita='$idBerita'";
		$sql = mysqli_query ($con,$query);

		// echo $query."<br>";
		header("location:index.php?screen=list");
	}
	elseif (isset($_POST['updateDataFinal'])) {
		
		$isiBerita = htmlentities(addslashes($_POST['content']));
		$judul = htmlentities(addslashes($_POST['judul']));
		$statusBerita = '3';
			
		$con=mysqli_connect("localhost","root","","newsroom");
		
		$query = "UPDATE tblberita SET halaman='$_POST[hal]', rubrikBerita='$_POST[rub]', judulBerita='$judul', kota='$_POST[kota]', isiBerita='$isiBerita', editor='$_SESSION[iduser]', statusBerita='$statusBerita' WHERE idBerita='$idBerita'";
		$sql = mysqli_query ($con,$query);
		// echo $query."<br>";
		
		$query = "UPDATE tblberita_lock SET lockStatus='0' WHERE idBerita='$idBerita'";
		$sql = mysqli_query ($con,$query);

		// echo $query."<br>";
		header("location:index.php?screen=list");
	}
	elseif (isset($_POST['cancel'])) {
	
		$query = "UPDATE tblberita_lock SET lockstatus='0', username='$record[username]' WHERE id='$record[idlock]'";
		$sql = mysqli_query ($con,$query);
		// echo $query;
		// mysqli_close($con);
		header("location:index.php?screen=list");
	}
	
	// Validasi page
	if ($record['lockStatus']==null){
	// Insert record to tblberita_lock
	$query = "INSERT INTO tblberita_lock (username, idBerita, page, ip)
			VALUES ('$_SESSION[iduser]','$idBerita','index.php?screen=edit&idBerita=$idBerita','$_SERVER[REMOTE_ADDR]')";
	$sql = mysqli_query($con,$query);
	}
	elseif ($record['lockStatus']==1){
		// Berita sedang di edit
		if ($record['username']!=$_SESSION['iduser']) {
		echo 	"<div class='alert alert-error'>  
				  <a class='close' data-dismiss='alert'>x</a>  
				  <strong>Berita</strong> sedang diedit oleh pengguna lain.  |  <a onclick='history.go(-1)'>Go Back</a>
				</div>"; 
		exit;
		}
	}
	elseif ($record['lockStatus']==0){
		if ($record['username']==$_SESSION['iduser']){
			// Update record lockStatus
			$query = "UPDATE tblberita_lock SET ip='$_SERVER[REMOTE_ADDR]', lockStatus='1'
					  WHERE id='$idBerita'";
			$sql = mysqli_query($con,$query);
		}
		elseif ($record['username']!=$_SESSION['iduser']){
			// Insert record to tblberita_lock
			$query = "INSERT INTO tblberita_lock (username, idBerita, page, ip)
					VALUES ('$_SESSION[iduser]','$idBerita','index.php?screen=edit&idBerita=$idBerita','$_SERVER[REMOTE_ADDR]')";
			$sql = mysqli_query($con,$query);
		}
	}
	
	$query1 = "SELECT tblhalaman.Halaman as Hal,  tblberita.halaman AS idHalaman,
				tblrubrik.namaRubrik AS Rubrik, tblberita.rubrikBerita as idRubrik,  
				tblberita.judulBerita AS Judul, tblberita.kota AS Kota, 
				tblberita.isiBerita AS Naskah, tblberita.idBerita As idBerita				
				FROM tblberita 
				LEFT JOIN tblhalaman ON tblberita.halaman = tblhalaman.idHalaman 
				LEFT JOIN tblrubrik ON tblberita.rubrikBerita = tblrubrik.idRubrik 
				WHERE idBerita='$idBerita'";
	$sql1 = mysqli_query ($con,$query1);
	$record1 = mysqli_fetch_array ($sql1);
	$isiBerita = $record1['Naskah'];
	$isiBerita = html_entity_decode($isiBerita);
?>

	<form class="form-horizontal" method="post" enctype="multipart/form-data">  
		<div>
			<?php 
				if ($_SESSION['jabatan']!='Reporter') {
		echo "<select name='hal' style='width:auto;' required>";	
				}
				else {
		echo "<select name='hal' style='width:auto;' >";
				} ?>
				<option value="<?=$record1['idHalaman']?>"><?=$record1['Hal'];?></option>
				<?php	

					$query2="SELECT halaman, idHalaman FROM tblhalaman WHERE aktif='1' ORDER BY halaman ASC";
					$sql2 = mysqli_query($con,$query2);	
					while ($record2 = mysqli_fetch_array ($sql2)) {							
					
				?>
				<option value="<?=$record2['idHalaman'];?>"  <?php $sele = ($record1['Hal']==$record2['halaman']) ? "disabled" : ""; echo $sele; ?>><?=$record2['halaman'];?></option>
				<?php 
					} 
					
				?>
			</select> 
			<select name="rub" style="width:auto;" required>
				<option value="<?=$record1['idRubrik']?>"><?=$record1['Rubrik'];?></option>			
				<?php 
					$query3="SELECT namaRubrik, idRubrik FROM tblrubrik WHERE aktif='1'";
					$sql3 = mysqli_query($con,$query3);
					while ($record3 = mysqli_fetch_array ($sql3)) {							
				?>
					<option value="<?=$record3['idRubrik'];?>" <?php $sele = ($record1['Rubrik']==$record3['namaRubrik']) ? "disabled" : ""; echo $sele; ?>><?=$record3['namaRubrik'];?></option>
				<?php } ?>
			</select>
		</div>

		<div style="margin-top:10px;">  
			<input type="text" class="input-xlarge" name="judul"  placeholder="Judul" value="<?=$record1['Judul'];?>" required>  
		</div>  
		<div style="margin-top:10px;">  
			<input type="text" class="input-medium" name="kota" placeholder="Kota" value="<?=$record1['Kota'];?>" required>  
		</div> 
		<div style="margin-top:10px;">  
			<textarea class="input-block-level" rows="25" name="content" required><?=$isiBerita;?>
			</textarea> 	
		</div>  
		 
		<div class="form-actions">  
			<?php 
				if ($_SESSION['jabatan']=='Reporter' or $_SESSION['jabatan']=='Redaktur Pelaksana' or $_SESSION['jabatan']=='Pemimpin Redaksi') {
					echo "<button type='submit' class='btn btn-primary' name='updateData' >Submit</button> ";
					echo "<button type='submit' class='btn btn-primary' name='cancel' >Cancel</button>";		
				}
				else {
					echo "<button type='submit' class='btn btn-primary' name='updateData' >Submit</button> ";
					echo "<button type='submit' class='btn btn-primary' name='updateDataFinal' >Submit to Layout</button> ";
					echo "<button type='submit' class='btn btn-primary pull-right' name='cancel' >Cancel</button>";	
				}
			?>
		</div>  

	</form>

	