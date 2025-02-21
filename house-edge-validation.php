<?php
$ER = ''; /* ER = acronym for expected return; enter your ER value that you calculated previously. */
if ($ER == ''){
    echo "Fill in ER value.";
    return;
}
if ($ER < 0 || ER > 1){
    echo "'ER' value must be in range: 0 < ER < 1. Check your 'ER' value.";
    return;
}
$HE = (1 - $ER) * 100;
echo "House edge for $ER ER is " . number_format($HE, 2, '.', '') . "%";
?>