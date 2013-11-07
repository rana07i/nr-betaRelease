<?php
$con=mysqli_connect("localhost","root","","newsroom");
	
	if (isset($_GET['idBerita'])) {
		$idBerita = $_GET['idBerita'];
	} else {
		die ("Error. No idBerita Selected! ");	
	}
	
	$query = "SELECT tblHalaman.Halaman as Hal,  
				tblberita.judulBerita AS Judul
				FROM tblberita 
				LEFT JOIN tblhalaman ON tblberita.halaman = tblhalaman.idHalaman 
				WHERE idBerita='$idBerita'";
	$sql = mysqli_query ($con,$query);
	$record = mysqli_fetch_array ($sql);
	
	$filename = $record['Hal']."-".str_replace(" ", "_", $record['Judul']);
	$drivepath = getenv("SystemDrive");	
	$homepath = getenv("HOMEPATH");
	$folderpath = str_replace(" ","","\Downloads\ ");
	$folderpath = $drivepath.$homepath.$folderpath.$filename.".html";
echo $folderpath;

require_once 'tes/CreateDocx.inc';

$docx = new CreateDocx();

$html='<p>This is a simple paragraph with <strong>text in bold</strong>.</p>
<p>Now we include a list:</p>
<ul>
    <li>First item.</li>
    <li>Second item with subitems:
        <ul>
            <li>First subitem.</li>
            <li>Second subitem.</li>
        </ul>
    </li>
    <li>Third subitem.</li>
</ul>
<p>And now a table:</p>
<table>
    <tbody><tr>
        <td>Cell 1 1</td>
        <td>Cell 1 2</td>
    </tr>
    <tr>
        <td>Cell 2 1</td>
        <td>Cell 2 2</td>
    </tr>
</tbody></table>';

$docx->embedHTML($html);

$docx->createDocx('simpleHTML');


?>