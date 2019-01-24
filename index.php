<!DOCTYPE html>
<head>
    <title>CAS Case Comp Client</title>
</head>
<body>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="stylesheet.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <?php require 'calcMetrics.php' ?>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script>
		window.onload = function() {
			var phoneAgeChart = new CanvasJS.Chart("phoneAgeContainer", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light1",
				title: {
					text: "Phone Age vs Avg Claim Value"
				},
				axisX: {
					title: "Phone Age",
					suffix: "yrs"
				},
				axisY: {
					title: "Avg Claim Value",
					suffix: "$"
				},
				data: [{
					type: "spline",
					markerType: "square",
					markerSize: 10,
					toolTipContent: "Phone Age: {x} yrs<br>Avg Claim Value $ {y}",
					dataPoints: <?php echo json_encode($phoneAgeDataPoints, JSON_NUMERIC_CHECK); ?>
				}]
			});
			phoneAgeChart.render();
			var userAgeChart = new CanvasJS.Chart("userAgeContainer", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light1",
				title: {
					text: "User Age vs Avg Claim Value"
				},
				axisX: {
					title: "User Age",
					suffix: "yrs"
				},
				axisY: {
					title: "Avg Claim Value",
					suffix: "($)",
					includeZero: false
				},
				data: [{
					type: "spline",
					markerType: "square",
					markerSize: 10,
					toolTipContent: "User Age: {x} yrs<br>Avg Claim Value $ {y}",
					dataPoints: <?php echo json_encode($userAgeDataPoints, JSON_NUMERIC_CHECK); ?>
				}]
			});
			userAgeChart.render();
		}
	</script>
</body>