<?php 

	function connectDb(){
	
	}

	function readmore($idberita,$judul,$kota,$isiberita,$namalengkap){ ?>
		<div id="<?=$idberita;?>" class="modal hide fade" tabindex="-1" data-width="900">
			<div class="modal-header">
				<div class="row-fluid">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3><?=$judul;?></h3>
				</div>
			</div>
			<div class="modal-body">
				<b><?=$kota;?> (HN)</b><?=$isiberita;?><?=$namalengkap;?> 
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn">Close</button>
				<!-- a href="index.php?screen=edit&idBerita=<?=$idberita;?>" class="btn btn-primary">Edit</a -->
			</div>
		</div>
		<button class="demo btn btn-primary btn-small" data-toggle="modal" href="#<?=$idberita;?>">Read more</button></br>
	<?php 
	}

	function edit_button($idberita) {
		echo "<a href='index.php?screen=edit&idBerita=<?=$idberita;?>' class='btn btn-primary'>Edit</a>";
	}
	
	function tgl_create_news($con,$idberita){
		$queryCreated = "SELECT createdAt FROM tblberita_log 
						WHERE idBerita='$idberita'
						ORDER BY createdAt ASC	
						LIMIT 1";
		$sqlExecute = mysqli_query($con,$queryCreated);
		$record1 = mysqli_fetch_array ($sqlExecute);
		$createdat = $record1['createdAt'];
		echo $createdat;
	}
		
	function fileHtmlName($hal,$judul){
		$filename = str_replace(" ", "_", $judul);	
		$filename = str_replace("?", "[tandatanya]", $filename);
		$filename = str_replace('"', "[tandapetik]", $filename);
		$filename = str_replace(":", "[titikdua]", $filename);
		$filename = $hal."-".str_replace(":", "", $filename);
		return $filename;
	}
?>
