<?php
function generateLicenceKey() {
$length = 19;
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$trenner ="-";
$randomString = '';
$counterForTrenner = 0;
for ($i = 0; $i < $length; $i++) {
	if ($counterForTrenner == 4){
		$counterForTrenner = 0;
		$randomString .= $trenner;
	}else{
		$randomString .= $characters[rand(0, $charactersLength - 1)];
		$counterForTrenner = $counterForTrenner+1;
	}
}
echo $randomString;
return $randomString;
}
?>