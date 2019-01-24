<?php
echo "<h2>CLAIMS ATTRIBUTES</h2>";
$claims_count = getTotalClaimCount($dbConn);
$claims_value = getTotalClaimValue($dbConn);
$avg_claim = calcAverageClaimCost($claims_count, $claims_value);
echo "<h2>CURRENT PROJECTED DATA SET</h2>";
calcTotalApproxClaimCost($claims_count, $avg_claim);
$phoneAgeDataPoints = calcPhoneAgeCorrelation($dbConn);
$userAgeDataPoints = calcUserAgeCorrelation($dbConn);
//calcAreaRiskCorrelation();

function getTotalClaimCount($dbConn){
	$get_claim_count_stmt = $dbConn->prepare("SELECT COUNT(*) FROM claims");
	$get_claim_count_stmt->execute();
	$get_claim_count_stmt->bind_result($claims_count);
	$get_claim_count_stmt->fetch();
	$get_claim_count_stmt->close();
	echo "<p>Got total claim count of: $claims_count</p>";
	return $claims_count;
}

function getTotalClaimValue($dbConn){
	$get_claim_value_stmt = $dbConn->prepare("SELECT SUM(claimValue) FROM claims");
	$get_claim_value_stmt->execute();
	$get_claim_value_stmt->bind_result($claim_value);
	$get_claim_value_stmt->fetch();
	$get_claim_value_stmt->close();
	echo "<p>Got total claim value of: $claim_value</p>";
	return $claim_value;
}	

function calcAverageClaimCost($claims_count, $claims_value){
	$avg_claim = $claims_value / $claims_count;
	echo "<p><strong>Average Claim Value: $avg_claim</strong></p>";
	return $avg_claim;
}

function calcTotalApproxClaimCost($claims_count, $avg_claim){
	echo "<p><strong>Expanded data set with 5% error attributes:</strong></p>";
	$projected_claims = $claims_count * 100;
	$projected_claims_uprbnd = $projected_claims * 1.05;
	$projected_claims_lwrbnd = $projected_claims * 0.95;
	echo "<p>Projected total claims: $projected_claims</p>";
	echo "<p>Projected total claim range: $projected_claims_lwrbnd --> $projected_claims_uprbnd</p>";
	$projected_claim_value = $projected_claims * $avg_claim;
	$projected_val_uprbnd = $projected_claim_value * 1.05;
	$projected_val_lwrbnd = $projected_claim_value * 0.95;
	echo "<p>Projected total claim value: $projected_claim_value</p>";
	echo "<p>Projected total claim value range: $projected_val_lwrbnd --> $projected_val_uprbnd</p>";
}

function calcPhoneAgeCorrelation($dbConn){
	$phoneAgeDataPoints = array();
	echo "<div class = 'row m-0'><h2 class = 'w-100 section-title'><hr>PHONE AGE / CLAIM VALUE DATA<hr></h2><div class = 'col-lg-6 p-0'>";
    $get_max_phone_age_stmt = $dbConn->prepare("SELECT MAX(yearsOld) as 'max_age' FROM claims");
    $get_max_phone_age_stmt->execute();
    $get_max_phone_age_stmt->bind_result($max_age);
    $get_max_phone_age_stmt->fetch();
	$get_max_phone_age_stmt->close();
	$average_claim_values = array();
	for($i = 1; $i < $max_age; $i++){
		$get_claim_sum_stmt = $dbConn->prepare("SELECT COUNT(*), SUM(claimValue) FROM claims WHERE yearsOld = ?");
		$get_claim_sum_stmt->bind_param("i", $i);
		$get_claim_sum_stmt->execute();
		$get_claim_sum_stmt->bind_result($claim_count, $total_claim_value);
		$get_claim_sum_stmt->fetch();
		$get_claim_sum_stmt->close();
		$avg_claim_value = $total_claim_value / $claim_count;
		echo "<p>$i yr old phone: $claim_count records; total value: $total_claim_value; avg claim value: $avg_claim_value</p>";
		array_push($average_claim_values, $avg_claim_value);
		$thisClaimPoints = array("x"=>$i, "y"=>$avg_claim_value);
		array_push($phoneAgeDataPoints, $thisClaimPoints);
	}
	$age_value_multiplier = 0;
	$last_val = $average_claim_values[0];
	foreach($average_claim_values as $val){
		$age_value_multiplier += ($val - $last_val) / $last_val;
		$last_val = $val;
	}
	$age_value_multiplier /= count($average_claim_values);
	$age_value_multiplier *= 100;
	echo "<p>As a phone ages one year, its claim value changes by an average of: $age_value_multiplier%</p>";
	echo "</div><div class = 'col-lg-6 p-0 chart-container' id = 'phoneAgeContainer'></div></div>";
	return $phoneAgeDataPoints;
}

function calcUserAgeCorrelation($dbConn){
	echo "<div class = 'row m-0'><h2 class = 'w-100 section-title'><hr>USER AGE / CLAIM VALUE DATA</h2><div class = 'col-lg-6 p-0'>";
	$get_age_range_stmt = $dbConn->prepare("SELECT MAX(userAge), MIN(userAge), AVG(userAge) FROM claims");
	$get_age_range_stmt->execute();
	$get_age_range_stmt->bind_result($max_age, $min_age, $avg_age);
	$get_age_range_stmt->fetch();
	$get_age_range_stmt->close();
	echo "<p>The user age range is $min_age to $max_age with an average user age of: $avg_age years</p>";
	$age_range = $max_age - $min_age;
	$age_sub_range = floor($age_range / 3);
	$lwr_range = $min_age + $age_sub_range;
	$mid_range = $lwr_range + $age_sub_range;
	$upr_range = $mid_range + $age_sub_range;
	echo "<p>The 3 main user age groups are: $min_age to $lwr_range, $lwr_range to $mid_range, $mid_range to $max_age";
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
		echo "<p>Average age group claim value: $group_avg_claim</p>";
	}
	echo "</div><div class = 'col-lg-6 p-0 chart-container' id = 'userAgeContainer'></div</div>";
	return $userAgeDataPoints;
}