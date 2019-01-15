<?php
date_default_timezone_set("Asia/Jakarta");
$config = parse_ini_file('config.ini', true);
$db_host = $config['config']['server']; // Nama Server
$db_user = $config['config']['username']; // User Server
$db_pass = $config['config']['password']; // Password Server
$db_name = $config['config']['dbname']; // Nama Database

//Test
//$db_host = 'localhost'; // Nama Server
//$db_user = 'root'; // User Server
//$db_pass = ''; // Password Server
//$db_name = 'db_mrs'; // Nama Database

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
	die ('Gagal terhubung MySQL: ' . mysqli_connect_error());	
}
//Raspberry Input / Serial RFID Input
$eeq = $argv;
$response = explode(":",$eeq[1]);
$id  = $response[0];
$alat = $response[1];
$uid = hexdec($id);
//Manual Input
//$uid = '1581542966';
//$alat = '3';
echo "in dec : ";
echo ($uid);
echo " alat no: ";
echo ($alat);

$date = date('Y-m-d');
$jam = date('H:i:s');

$sql = 'query'; //type query


$data ="";		
$query = mysqli_query($conn, $sql);
if (!$query) {
	die ('SQL Error: ' . mysqli_error($conn));
}
if ( $cek = mysqli_num_rows($query) > 0 ){
	while ($row = mysqli_fetch_array($query))
		{
			//Action
		}
}
else {
	$data .= '<center><h2 class="text-red"><i class="fa fa-times"></i>BELUM PESAN MAKAN</h2><hr />';
	$data .= '<center><small></small>';
	$data .="</center>";
}
$filena = 'data-'.$alat.'.txt';
$handle = fopen($filena,'w') or die ('teukabuka');
//$datana = $uid.' alatna:'.$alat;
fwrite($handle,$data);
mysqli_close($conn);
?>
