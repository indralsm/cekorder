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

//$eeq = $argv;
//$response = explode(":",$eeq[1]);
//$id  = $response[0];
//$alat = $response[1];
//$uid = hexdec($id);

$uid = '1581542966';
$alat = '3';
echo "in dec : ";
echo ($uid);
echo " alat no: ";
echo ($alat);

$date = date('Y-m-d');
$jam = date('H:i:s');

if (( $jam > "00:00:00") && ($jam < "08:00:00")){
	$sql = 'SELECT * FROM t_order_menu a join users b on a.employee_nik = b.id
		join t_schedule_menu c on a.schedule_menu_id = c.schedule_menu_id
		join t_menu d on c.menu_id = d.menu_id
		join t_schedule_meal e on c.schedule_meal_id = e.schedule_meal_id
		where card='.$uid.'
		and c.schedule_date ="'.$date.'"
		and c.schedule_meal_id=1';
}
if (( $jam > "08:00:00") && ($jam < "14:00:00")){
	$sql = 'SELECT * FROM t_order_menu a join users b on a.employee_nik = b.id
		join t_schedule_menu c on a.schedule_menu_id = c.schedule_menu_id
		join t_menu d on c.menu_id = d.menu_id
		join t_schedule_meal e on c.schedule_meal_id = e.schedule_meal_id
		where card='.$uid.'
		and c.schedule_date ="'.$date.'"
		and c.schedule_meal_id=2';
}
if (( $jam > "14:00:00") && ($jam < "24:00:00")){
	$sql = 'SELECT * FROM t_order_menu a join users b on a.employee_nik = b.id
		join t_schedule_menu c on a.schedule_menu_id = c.schedule_menu_id
		join t_menu d on c.menu_id = d.menu_id
		join t_schedule_meal e on c.schedule_meal_id = e.schedule_meal_id
		where card='.$uid.'
		and c.schedule_date ="'.$date.'"
		and c.schedule_meal_id=3';
}


$data ="";		
$query = mysqli_query($conn, $sql);
if (!$query) {
	die ('SQL Error: ' . mysqli_error($conn));
}
if ( $cek = mysqli_num_rows($query) > 0 ){
	while ($row = mysqli_fetch_array($query))
		{
			if ($row['order_ambil']==0){
				if ($alat == $row['loket_name']){
					//Counting
					$finalorder= $row['final_order'];
					$countorder = mysqli_num_rows(mysqli_query($conn,"select * from t_order_menu where schedule_menu_id=".$row['schedule_menu_id']));
					$sisa = $finalorder - $countorder;
					$data .= $sisa;
					$data .= '<center><h2 class="text-green"><i class="fa fa-check"></i> Silahkan Ambil</h2><hr />';
					$data .= '<center><small>'.$jam.' | '.$row['first_name'].'</small>';
					$data .="</center>";
					//Update data
					$sql_update = 'update t_order_menu set order_ambil = 1 where order_id='.$row['order_id'];
					mysqli_query($conn, $sql_update);
					$sql_change = 'INSERT INTO t_get_menu VALUES (NULL, "'.$date.'", "'.$jam.'", '.$alat.', '.$uid.');';
					mysqli_query($conn, $sql_change);
				}
				else {
					$data .= '<center><h2 class="text-red"><i class="fa fa-times"></i>Loket Anda di Loket '.$row['loket_name'].'</h2><hr />';
					$data .= '<center><small>'.$jam.' | '.$row['first_name'].'</small>';
					$data .="</center>";
				}
			}
			else {
				$data .= '<center><h2 class="text-red"><i class="fa fa-times"></i> Makanan Sudah diambil</h2><hr />';
				$data .= '<center><small>'.$jam.' - '.$row['first_name'].'</small>';
			}
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