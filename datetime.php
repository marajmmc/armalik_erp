<?php
$timezone = new DateTimeZone("Asia/Dhaka" );
$date = new DateTime();
$date->setTimezone($timezone );
echo  $time=$date->format( 'h:i:s A' )."<br />";
echo  $date=$date->format( 'D, M jS, Y' );
?>
