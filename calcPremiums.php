<?php
$dbConn = new mysqli('staging-mavtest.mavericksystems.us', 'mavadmin', 'spa_He4o!_tr1!rE!Rok', 'cas', '3306');
	
$riskAreaSamples = getRiskAreaSamples($dbConn);
calcPremiums($riskAreaSamples);
	
function getRiskAreaSamples($dbConn) {
	$riskAreaSamples = array();
	for($i = 1; $i < 4; $i++){
		$thisRiskAreaSample = array();
		$getRiskSamplesStmt = $dbConn->query("SELECT phoneType, phoneCase, yearsOld, userAge, areaRisk, claimValue FROM claims WHERE areaRisk = " . ($i) . " LIMIT 50");
		while($claimSample = $getRiskSamplesStmt->fetch_object()){
			array_push($thisRiskAreaSample, $claimSample);
		}
		array_push($riskAreaSamples, $thisRiskAreaSample);
	}
	return $riskAreaSamples;
}

function calcPremiums($riskAreaSamples){
	$riskAreaNames = ["", "Low", "Moderate", "High"];
	foreach($riskAreaSamples as $riskAreaSample){
		$riskAreaSum = 0;
		$riskAreaId = 0;
		$sturdyRatio = 0;
		foreach($riskAreaSample as $sampleClaim){
			$riskAreaId = $sampleClaim->areaRisk;
			$riskAreaSum += $sampleClaim->claimValue;
			if($sampleClaim->phoneType == 1){
				$sturdyRatio ++;
			}
		}
		$sturdyRatio /= 50;
		$premiumRiskMultiplier = 0;
		if($riskAreaId == 3){
			$premiumRiskMultiplier = 1.3;
		}
		elseif($riskAreaId == 2){
			$premiumRiskMultiplier = 1.2;
		}
		else{
			$premiumRiskMultiplier = 1.15;
		}
		$avgClaimFromArea = $riskAreaSum / 50;
		$phoneNames = ["Sturdy Premium", "Sturdy Midtier", "Oops Premium", "Oops Midtier"];
		for($i = 0; $i < 4; $i++){
			$areaPremium =  number_format($avgClaimFromArea * $premiumRiskMultiplier - ($i *5), 2);
			$combinedRatio = number_format($avgClaimFromArea / $areaPremium, 3);
			$totalPrice = $areaPremium + 100;
			echo "<tr><td>$riskAreaNames[$riskAreaId]</td><td>$phoneNames[$i]</td><td>$100</td><td>$$areaPremium</td><td>$$totalPrice</td><td>$combinedRatio</td></tr>";
		}
	}
}