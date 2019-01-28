<?php
$dbConn = new mysqli('staging-mavtest.mavericksystems.us', 'mavadmin', 'spa_He4o!_tr1!rE!Rok', 'cas', '3306');
$claims_count = getTotalClaimCount($dbConn);
$claims_value = getTotalClaimValue($dbConn);
$avg_claim = calcAverageClaimCost($claims_count, $claims_value);
calcTotalApproxClaimCost($claims_count, $avg_claim);
$phoneAgeDataPoints = calcPhoneAgeCorrelation($dbConn);
$userAgeDataPoints = calcUserAgeCorrelation($dbConn);
list($areaPieDataPoints, $areaBarDataPoints) = calcAreaRiskCorrelation($dbConn, $claims_value);

function getTotalClaimCount($dbConn){
	echo "<div class = 'row m-0'><div class = 'col-lg-6 content-col'><h3 class = 'w-100 section-title'><hr>CLAIMS ATTRIBUTES<hr></h3>";
	$get_claim_count_stmt = $dbConn->prepare("SELECT COUNT(*) FROM claims");
	$get_claim_count_stmt->execute();
	$get_claim_count_stmt->bind_result($claims_count);
	$get_claim_count_stmt->fetch();
	$get_claim_count_stmt->close();
	$fclaims_count = number_format($claims_count);
	echo "<p class = 'w-100'>Total Set Claim Count: $fclaims_count</p>";
	return $claims_count;
}

function getTotalClaimValue($dbConn){
	$get_claim_value_stmt = $dbConn->prepare("SELECT SUM(claimValue) FROM claims");
	$get_claim_value_stmt->execute();
	$get_claim_value_stmt->bind_result($claim_value);
	$get_claim_value_stmt->fetch();
	$get_claim_value_stmt->close();
	$fclaim_value = number_format($claim_value);
	echo "<p class = 'w-100'>Total Set Claim Loss: $$fclaim_value</p>";
	return $claim_value;
}	

function calcAverageClaimCost($claims_count, $claims_value){
	$avg_claim = $claims_value / $claims_count;
	$favg_claim = number_format($avg_claim, 2);
	echo "<p><strong>Average Claim Loss: $$favg_claim</strong></p></div>";
	return $avg_claim;
}

function calcTotalApproxClaimCost($claims_count, $avg_claim){
	echo "<div class = 'col-lg-6 content-col'><h3 class = 'w-100 section-title'><hr>CURRENT PROJECTED DATA SET<hr></h3><p><strong>Expanded data set with 5% variance:</strong></p>";
	$projected_claims = $claims_count * 100;
	$projected_claims_uprbnd = $projected_claims * 1.05;
	$projected_claims_lwrbnd = $projected_claims * 0.95;
	$fprojected_claims_lwrbnd = number_format($projected_claims_lwrbnd);
	$fprojected_claims_uprbnd = number_format($projected_claims_uprbnd);
	echo "<p>Projected total claim count range: $fprojected_claims_lwrbnd → $fprojected_claims_uprbnd</p>";
	$projected_claim_value = $projected_claims * $avg_claim;
	$projected_val_uprbnd = $projected_claim_value * 1.05;
	$projected_val_lwrbnd = $projected_claim_value * 0.95;
	$fprojected_val_lwrbnd = number_format($projected_val_lwrbnd, 2);
	$fprojected_val_uprbnd = number_format($projected_val_uprbnd, 2);
	echo "<p>Projected total claim loss range: $$fprojected_val_lwrbnd → $$fprojected_val_uprbnd</p></div></div>";
}

function calcPhoneAgeCorrelation($dbConn){
	$phoneAgeDataPoints = array();
	echo "<div class = 'row m-0 content-col'><h3 class = 'w-100 section-title'><hr>PHONE AGE / CLAIM VALUE DATA<hr></h3><div class = 'col-lg-6 content-col'>";
    $get_max_phone_age_stmt = $dbConn->prepare("SELECT MAX(yearsOld) as 'max_age' FROM claims");
    $get_max_phone_age_stmt->execute();
    $get_max_phone_age_stmt->bind_result($max_age);
    $get_max_phone_age_stmt->fetch();
	$get_max_phone_age_stmt->close();
	$average_claim_values = array();
	echo "<table class = 'w-100 table-hover table-bordered'><thead><th>Phone Age</th><th>Claims Made</th><th>Total Loss</th><th>Avg. Loss / Claim</th></thead><tbody>";
	for($i = 1; $i < $max_age; $i++){
		$get_claim_sum_stmt = $dbConn->prepare("SELECT COUNT(*), SUM(claimValue) FROM claims WHERE yearsOld = ?");
		$get_claim_sum_stmt->bind_param("i", $i);
		$get_claim_sum_stmt->execute();
		$get_claim_sum_stmt->bind_result($claim_count, $total_claim_value);
		$get_claim_sum_stmt->fetch();
		$get_claim_sum_stmt->close();
		$avg_claim_value = $total_claim_value / $claim_count;
		array_push($average_claim_values, $avg_claim_value);
		$thisClaimPoints = array("x"=>$i, "y"=>number_format($avg_claim_value, 2));
		array_push($phoneAgeDataPoints, $thisClaimPoints);
		$claim_count = number_format($claim_count);
		$total_claim_value = number_format($total_claim_value, 2);
		$avg_claim_value = number_format($avg_claim_value, 2);
		echo "<tr><td>$i</td><td>$claim_count</td><td>$$total_claim_value</td><td>$$avg_claim_value</td></tr>";
	}
	echo "</tbody></table>";
	$age_value_multiplier = 0;
	$last_val = $average_claim_values[0];
	foreach($average_claim_values as $val){
		$age_value_multiplier += ($val - $last_val) / $last_val;
		$last_val = $val;
	}
	$age_value_multiplier /= count($average_claim_values);
	$age_value_multiplier *= 100;
	$age_value_multiplier = number_format($age_value_multiplier, 2);
	echo "<br><p>As a phone ages one year, its claim value changes by an average of <strong>$age_value_multiplier%</strong></p>";
	echo "</div><div class = 'col-lg-6 chart-container' id = 'phoneAgeContainer'></div></div>";
	return $phoneAgeDataPoints;
}

function calcUserAgeCorrelation($dbConn){
	echo "<div class = 'row m-0 content-col'><h3 class = 'w-100 section-title'><hr>USER AGE / CLAIM VALUE DATA<hr></h3><div class = 'col-lg-6 content-col'>";
	$get_age_range_stmt = $dbConn->prepare("SELECT MAX(userAge), MIN(userAge), AVG(userAge) FROM claims");
	$get_age_range_stmt->execute();
	$get_age_range_stmt->bind_result($max_age, $min_age, $avg_age);
	$get_age_range_stmt->fetch();
	$get_age_range_stmt->close();
	$avg_age = number_format($avg_age);
	echo "<p>The user age range is $min_age to $max_age with an average user age of <strong>$avg_age</strong> years</p>";
	$age_range = $max_age - $min_age;
	$age_sub_range = floor($age_range / 3);
	$lwr_range = $min_age + $age_sub_range;
	$mid_range = $lwr_range + $age_sub_range;
	$upr_range = $mid_range + $age_sub_range;
	$age_ranges = array($min_age, $lwr_range, $mid_range, $max_age);
	$userAgeDataPoints = array();
	for($i = 1; $i < 4; $i++) {
		$i_index = $i - 1;
		echo "<h5>Age Group $i, $age_ranges[$i_index] to $age_ranges[$i]:</h4>";
		$get_this_grp_stmt = $dbConn->prepare("SELECT AVG(claimValue) FROM claims WHERE userAge > ? AND userAge < ?");
		$get_this_grp_stmt->bind_param("ii", $age_ranges[$i_index], $age_ranges[$i]);
		$get_this_grp_stmt->execute();
		$get_this_grp_stmt->bind_result($group_avg_claim);
		$get_this_grp_stmt->fetch();
		$get_this_grp_stmt->close();
		$age_range_avg = ($age_ranges[$i] + $age_ranges[$i_index]) / 2;
		array_push($userAgeDataPoints, array("x"=> $age_range_avg, "y"=>$group_avg_claim));
		$group_avg_claim = number_format($group_avg_claim, 2);
		echo "<ul><li><p>Average age group claim value: $$group_avg_claim</p></li></ul>";
	}
	echo "</div><div class = 'col-lg-6 p-0 chart-container' id = 'userAgeContainer'></div></div>";
	return $userAgeDataPoints;
}

function calcAreaRiskCorrelation($dbConn, $claims_value){
	echo "<div class = 'row m-0 content-col'><h3 class = 'w-100 section-title'><hr>AREA RISK / CLAIM VALUE DATA<hr></h3>";
	$risk_labels = array("Low", "Medium", "High");
	$arearisk_avgs = array();
	for($i = 1; $i < 4; $i++){
		$get_arearisk_avg = $dbConn->prepare("SELECT AVG(claimValue) FROM claims WHERE areaRisk = ?");
		$get_arearisk_avg->bind_param("i", $i);
		$get_arearisk_avg->execute();
		$get_arearisk_avg->bind_result($area_risk_avg);
		$get_arearisk_avg->fetch();
		$get_arearisk_avg->close();
		array_push($arearisk_avgs, array("y"=>$area_risk_avg, "label"=>$risk_labels[$i-1]));
	}
	$arearisk_shares = array();
	for($i = 1; $i < 4; $i++){
		$get_arearisk_share = $dbConn->prepare("SELECT SUM(claimValue) FROM claims WHERE areaRisk = ?");
		$get_arearisk_share->bind_param("i", $i);
		$get_arearisk_share->execute();
		$get_arearisk_share->bind_result($area_risk_sum);
		$get_arearisk_share->fetch();
		$get_arearisk_share->close();
		$arearisk_share = $area_risk_sum / $claims_value * 100;
		array_push($arearisk_shares, array("label"=>$risk_labels[$i -1], "y"=>$arearisk_share));
	}
	echo "<div class = 'col-lg-6 chart-container' id = 'areaRiskBarContainer'></div><div class = 'col-lg-6 p-0 chart-container' id = 'areaRiskPieContainer'></div></div>";
	return array($arearisk_shares, $arearisk_avgs);
}