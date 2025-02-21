<?php
/* Fill in values for 'p_values' and 'mp_values'.
   Example for a 3-spot game in Keno with a 1-40 range of possible spots;
   $p_values = ['41.09311741%', '44.02834008%', '13.66396761%', '1.21457490%'];
   $mp_values = [0.00, 1.3804, 2.1718, 7.2426];
   ER of these values should match 0.9925.
*/
$p_values = []; /* p_values = probabilities of all events happening (note: don't forget to put '' between each seperate value, or you will get an error in output.) */
$mp_values = []; /* mp_values = all multipliers for all possible events */
function convertToDecimal($value){
    if (strpos($value, '%') !== false){
        $value = (float)str_replace('%', '', $value) / 100;
    } else {
        $value = (float)$value;
        return;
    }
    return $value;
}
$P = array_map('convertToDecimal', $p_values);
function calculateER($P, $mp_values){
    $n = count($P);
    $ER = 0;
    for ($k = 0; $k < $n; $k++){
        $ER += $P[$k] * $mp_values[$k];
    }
    return $ER;
}
$ER = calculateER($P, $mp_values);
echo "The calculated ER is: " . $ER;
?>