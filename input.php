<?php 
	// ob_start();	
	// $con=mysqli_connect("localhost","root","","newsroom");
	// $con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");

	// if (mysqli_connect_errno()){
		  // echo "Failed to connect to MySQL: " . mysqli_connect_error();
	// }

?>

<form class="form-horizontal" method="post" enctype="multipart/form-data">  

	<div>
		<?php 
				if ($_SESSION['jabatan']!='Reporter') {
		echo "<select name='halaman' style='width:auto;' required>";	
				}
				else {
		echo "<select name='halaman' style='width:auto;' >";
				} ?>
				
			<option></option>
			<?php	
				$con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");

				$queryUser="SELECT halaman, idHalaman FROM tblhalaman WHERE aktif='1' ORDER BY halaman ASC";
				$sql = mysqli_query($con,$queryUser);	
				while ($record = mysqli_fetch_array ($sql)) {							
				
			?>
			<option value='<?=$record['idHalaman'];?>'><?=$record['halaman'];?></option>
			<?php 
				} 
				// mysqli_close($con);
			?>
		</select> 
		<select name="rubrik" style="width:auto;" required>
			<option></option>
			<?php	
				// $con=mysqli_connect("localhost","root","","newsroom");

				$queryUser="SELECT namaRubrik, idRubrik FROM tblrubrik WHERE aktif='1'";
				$sql = mysqli_query($con,$queryUser);	
				while ($record = mysqli_fetch_array ($sql)) {							
				
			?>
			<option value='<?=$record['idRubrik'];?>'><?=$record['namaRubrik'];?></option>
			<?php 
				} 
				// mysqli_close($con);
			?>
		</select>
	</div> 
	<div style="margin-top:10px;">  
		<input type="text" class="input-xlarge" name="judul"  placeholder="Judul" required>  
	</div>  
	<div style="margin-top:10px;">  
		<input type="text" class="input-medium" name="kota" placeholder="Kota" required>  
	</div> 
	<div style="margin-top:10px;">  
		<textarea class="input-block-level" rows="25" name="content"></textarea> 		
	</div>  
	<div class="form-actions">  
		<?php 
			//echo $_SESSION['jabatan'];
			if ($_SESSION['jabatan']=='Reporter' or $_SESSION['jabatan']=='Redaktur Pelaksana' or $_SESSION['jabatan']=='Pemimpin Redaksi') {
				echo "<button type='submit' class='btn btn-primary' name='upload' >Submit</button> ";
				echo "<button type='button' class='btn btn-primary' name='' onclick='history.go(-1)'>Cancel</button>";				
			}
			elseif ($_SESSION['jabatan']=='Redaktur') {
				echo "<button type='submit' class='btn btn-primary' name='upload' >Submit</button> ";
				echo "<button type='submit' class='btn btn-primary' name='uploadDataFinal' >Submit Final</button> ";
				echo "<button type='button' class='btn btn-primary pull-right' name='' onclick='history.go(-1)' >Cancel</button>";
			}
		?>   
	</div>  

</form>

	<?php
		//proses input berita
		if (isset($_POST['upload'])) {
			$isiBerita = htmlentities(addslashes($_POST['content']));
			$judul = htmlentities(addslashes($_POST['judul']));
			
			if ($_SESSION['jabatan']=='Reporter') {
				$statusBerita = '0';
			} elseif ($_SESSION['jabatan']=='Redaktur'){
				$statusBerita = '1';
			} else {
				$statusBerita = '3';
			}
			
			$tglBerita = date('Y-m-d', time()+86400);

			//$con = mysqli_connect("localhost","k2459657_nruser","@harianNasional","k2459657_newsroom");

			if (mysqli_connect_errno()){
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
			
			$query="INSERT INTO tblberita (tglBerita, halaman, judulBerita, rubrikBerita, kota, isiBerita, penulisBerita, statusBerita) VALUES
			('$tglBerita','$_POST[halaman]','$judul','$_POST[rubrik]','$_POST[kota]','$isiBerita','$_SESSION[iduser]', '$statusBerita')";
			
			// echo $query;
			if (!mysqli_query($con,$query))
				{
				  die('Error: ' . mysqli_error($con));
				}
			echo "<div class='alert alert-success'>  
					<a class='close' data-dismiss='alert'>x</a>  
					<strong>Berhasil!</strong>.  
				</div>";

			// mysqli_close($con);
			
			header("location:index.php?screen=listByUser");
			
		}
		elseif (isset($_POST['uploadDataFinal'])) {
			$isiBerita = htmlentities(addslashes($_POST['content']));
			$judul = htmlentities(addslashes($_POST['judul']));
			
			$statusBerita = '3';
						
			$tglBerita = date('Y-m-d', time()+86400);

			if (mysqli_connect_errno()){
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
			
			$query="INSERT INTO tblberita (tglBerita, halaman, judulBerita, rubrikBerita, kota, isiBerita, penulisBerita, statusBerita) VALUES
			('$tglBerita','$_POST[halaman]','$judul','$_POST[rubrik]','$_POST[kota]','$isiBerita','$_SESSION[iduser]', '$statusBerita')";
			
			// echo $query;
			if (!mysqli_query($con,$query))
				{
				  die('Error: ' . mysqli_error($con));
				}
			echo "<div class='alert alert-success'>  
					<a class='close' data-dismiss='alert'>x</a>  
					<strong>Berhasil!</strong>.  
				</div>";

			// mysqli_close($con);
			
			header("location:index.php?screen=listByUser");
		}
		elseif (isset($_POST['cancel'])) {
			header("location:index.php?screen=listByUser");
		}
	?>