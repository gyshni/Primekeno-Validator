<?php
/* Calculate multipliers for n-spot games (hard difficulty) */
/* For manually calculating multipliers for n-spot games, input necessary values in the 4th and 5th lines of code! */
function calculateMultipliers($n = 1, $a = 40){ /* Instead of $n = 1 and $a = 40, input values that correspond with values you want to calculate multipliers for. */
	$p_values = ['75.00%', '25.00%']; /* Instead of $p_values = ['75.00%', '25.00%'], input values that correspond with values you want to calculate multipliers for. */
	$BC = 1;
	$targetReturn = 0.9925;
	$rawMultipliers = [];
	$zeroMultiplierProbSum = 0;
	$validProbSum = 0;
	$P = array_map(function($value){
		return (float)str_replace('%', '', $value) / 100;
	}, $p_values);
	foreach ($P as $hits => $probability){
		$multiplier = $BC / $probability;
		if ($multiplier < 1){
			$rawMultipliers[$hits] = 0;
			$zeroMultiplierProbSum += $probability;
		} else {
			$rawMultipliers[$hits] = $multiplier;
			$validProbSum += $probability;
		}
	}
	$previousMultiplier = 0;
	foreach ($rawMultipliers as $hits => &$multiplier){
		if ($multiplier <= $previousMultiplier){
			$multiplier = $previousMultiplier * 1.1;
		}
		$previousMultiplier = $multiplier;
	}
	$CER = array_sum(array_map(function($probability, $multiplier){
		return ($multiplier >= 1) ? $probability * $multiplier : 0;
	}, $P, $rawMultipliers));
	$adjustedTargetReturn = $targetReturn / $validProbSum;
	$SF = ($CER != 0) ? ($adjustedTargetReturn / $CER) : 0;
	$finalMultipliers = array_map(function($multiplier) use ($SF){
		if ($multiplier < 1){
			return 0.0000;
		} else {
			$scaledMultiplier = $multiplier * $SF;
			return ($scaledMultiplier < 1) ? 0.0000 :  round($scaledMultiplier, 4);
		}
	}, $rawMultipliers);
	$previousMultiplier = 0;
	foreach ($finalMultipliers as $hits => &$multiplier){
		if ($multiplier <= $previousMultiplier){
			$multiplier = $previousMultiplier * 1.1;
		}
		$previousMultiplier = $multiplier;
	}
	$expectedValue = array_sum(array_map(function($probability, $multiplier){
		return $probability * $multiplier;
	}, $P, $finalMultipliers));
	if (abs((1 - $expectedValue) * 100 - 0.75) > 0.0001){
		$correction = 0.9925 / $expectedValue;
		$finalMultipliers = array_map(function($multiplier) use ($correction){
			return ($multiplier >= 1) ? round($multiplier * $correction, 4) : $multiplier;
		}, $finalMultipliers);
		$expectedValue = array_sum(array_map(function($probability, $multiplier){
			return $probability * $multiplier;
		}, $P, $finalMultipliers));
	}
	return [
		'multipliers' => $finalMultipliers,
		'probabilities' => $P,
		'expectedValue' => $expectedValue,
		'houseEdge' => (1 - $expectedValue) * 100,
		'validProbabilitySum' => $validProbSum * 100
	];
}
$result = calculateMultipliers();
foreach ($result['multipliers'] as $hits => $multiplier){
	echo sprintf("%4d match(es) | %7.4fx\n", $hits, $multiplier);
}
?>