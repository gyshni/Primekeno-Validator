<?php
/* Fill in values for 'a', 'k' and 'n'.
   (b = 10 or 20; if a = 40, b = 10; if a = 80; b = 20)
   (n = {1, ..., 20}; if a = 40, n = [1, 10]; if a = 80, n = [1, 20])
*/
$a = ''; /* a = total number of possible spots (either 40 or 80) */
$a_int = (int) $a;
$b = $a_int / 4;
$k = '';
$n = '';
if ($a == '' || $k == '' || $n == ''){
    echo "Fill in all values.";
    return;
}
if ($n < 0){
    echo "'n' value must be in range: 0 ≤ n ≤ ¼(a). Check your 'n' value.";
    return;
}
if ($a_int !== 40 && $a_int !== 80){
    echo "'a' value must be either 40 or 80. Check your 'a' value.";
    return;
}
if ($k > $n || $k < 0){
    echo "'k' value must be in range: 0 ≤ k ≤ n. Check your 'k' value.";
    return;
}
if ($a_int == 40 && $n > 10){
    echo "'n' value cannot exceed a value greater than ¼ of 'a'. Check your 'n' value (n = {1, ..., 20}; if a = 40, n = [1, 10]; if a = 80, n = [1,20]).";
    return;
}
function factorial($factorialN){
    if ($factorialN == 0){
        return 1;
    }
    $result = 1;
    for ($i = 1; $i <= $factorialN; $i++){
        $result *= $i;
    }
    return $result;
}
function combination($x, $y){
    if ($y > $x || $y < 0 || $x < 0){
        return 0;
    }
    return factorial($x) / (factorial($y) * factorial($x - $y));
}
function probabilityMatchingK($n, $k, $a_int, $b){
    $c1 = combination($n, $k);
    $c2 = combination($a_int - $n, $b - $k);
    $c3 = combination($a_int, $b);
    return ($c1 * $c2) / $c3;
}
$probability = probabilityMatchingK($n, $k, $a_int, $b);
$probabilityInPercentage = $probability * 100;
if ($a !== 40){
    echo "The probability of matching $k/$n spots is " . number_format($probabilityInPercentage, 30, '.', '') . "%";
    return;
} else {
    echo "The probability of matching $k/$n spots is " . number_format($probabilityInPercentage, 15, '.', '') . "%";
    return;
}
?>