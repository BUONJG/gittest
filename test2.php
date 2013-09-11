<?php
include "../../include/include.html.php";

ggipsi()->settitle('Test');

//Ici mon test

$iQuery = gdb('ips_ps','t_achats_recep')->query('select');
$iQuery->select('DATE_RECEP_J');
$iQuery->where("RLNRCP = '101033235'");
$tableau = $iQuery->gethtbl();

$tableau->display();

echo nhtbl($tableau->getvalue(0))->getvalue('DATE_RECEP_J');
echo nhtbl($tableau->getvalue(1))->getvalue('DATE_RECEP_J');

////////////////////
ggipsi()->display();
////////////////////
?>