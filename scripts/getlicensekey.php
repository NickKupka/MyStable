<?php
   include('dbconnect.php');
   error_reporting(0); //(E_ALL ^  E_NOTICE);
   exec("java -jar licensekeygenerator/dist/LicenseKeyGenerator.jar ",$output);
   $key = $output[0];
   echo $key;
   // write $key into db
?>