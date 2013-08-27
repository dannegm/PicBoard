<?php
include_once('../config.php');
include_once('../php/functions.php');

$domain = "http://dannegm.pro/picboard/";
$p = isset($_GET['p']) ? $_GET['p'] : 'DRUStktZ';

$pic = file_get_contents($domain . $p . '?info');
	$pic = json_decode($pic);

$colors = colorPalette($domain . $p . '?thumb', 5);


foreach ($colors as $c) {
?>
    #<?php echo $c; ?>;
<?php
}

foreach ($colors as $c) {
?>
    <div style="display: inline-block; width: 40px; height: 40px; background: #<?php echo $c; ?>;"></div>
<?php
}
?>

<img src="<?php echo $domain . $p . '?resize'; ?>" style="display:block;" />