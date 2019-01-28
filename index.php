<!DOCTYPE html>
<head>
    <title>CAS Case Comp Client</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="stylesheet.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
</head>
<body>
    <?php require 'calcMetrics.php' ?>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	<script>
		window.onload = function() {
			var phoneAgeChart = new CanvasJS.Chart("phoneAgeContainer", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light2",
				title: {
					text: "Phone Age vs Avg Claim Value"
				},
				axisX: {
					title: "Phone Age",
					suffix: "yrs"
				},
				axisY: {
					title: "Avg Claim Value",
					suffix: "$",
					includeZero: false
				},
				data: [{
					type: "line",
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
				theme: "light2",
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
			var areaRiskBarChart = new CanvasJS.Chart("areaRiskBarContainer", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light2",
				title: {
					text: "Risk Area Avg Claim Loss"
				},
				axisY: {
					title: "Avg Claim Loss ($)"
				},
				data: [{
					type: "column",
					yValueFormatString: "$#,##0.##",
					dataPoints: <?php echo json_encode($areaBarDataPoints, JSON_NUMERIC_CHECK); ?>
				}]
			});
			areaRiskBarChart.render();
			var areaRiskPieChart = new CanvasJS.Chart("areaRiskPieContainer", {
				animationEnabled: true,
				exportEnabled: true,
				theme: "light2",
				title: {
					text: "Claim Loss Share of Risk Areas"
				},
				subtitles:[{
					text: "by claim value"
				}],
				data: [{
					type: "pie",
					yValueFormatString: "#,##0.00\"%\"",
					indexLabel: "{label} ({y})",
					dataPoints: <?php echo json_encode($areaPieDataPoints, JSON_NUMERIC_CHECK); ?>
				}]
			});
			areaRiskPieChart.render();
		}
	</script>
	<h1 class = "w-100 big-title"><hr>Device Correlation Matrices<hr></h1>
	<div class="row m-0 content-col">
		<div class="col-lg-6">
			<h2 class="w-100 section-title"><hr>User Age / Claim Value<hr></h2>
			<h4 class="w-100">Tabulated Statistics</h4>
			<table class="tab-stats w-100 table-hover table-bordered">
				<thead>
					<th>Age Group</th>
					<th>Battery</th>
					<th>Loss</th>
					<th>Other</th>
					<th>Screen</th>
					<th>Water</th>
					<th>All</th>
				</thead>
				<tbody>
					<tr class="section-row">
						<td>10 to 25</td>
						<td>241</td>
						<td>205</td>
						<td>555</td>
						<td>528</td>
						<td>582</td>
						<td class="total-val">2111</td>
					</tr>
					<tr>
						<td></td>
						<td>341.14</td>
						<td>122.65</td>
						<td>659.05</td>
						<td>503.68</td>
						<td>484.47</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>29.39</td>
						<td>55.29</td>
						<td>16.43</td>
						<td>1.17</td>
						<td>19.63</td>
						<td></td>
					</tr>
					<tr class="section-row">
						<td>26 to 40</td>
						<td>687</td>
						<td>149</td>
						<td>1471</td>
						<td>946</td>
						<td>1033</td>
						<td class="total-val">4286</td>
					</tr>
					<tr>
						<td></td>
						<td>692.62</td>
						<td>249.02</td>
						<td>1338.09</td>
						<td>1022.64</td>
						<td>983.64</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>0.05</td>
						<td>40.17</td>
						<td>13.20</td>
						<td>5.74</td>
						<td>2.48</td>
						<td></td>
					</tr>
					<tr class="section-row">
						<td>41 to 55</td>
						<td>552</td>
						<td>115</td>
						<td>907</td>
						<td>655</td>
						<td>580</td>
						<td class="total-val">2809</td>
					</tr>
					<tr>
						<td></td>
						<td>453.93</td>
						<td>163.20</td>
						<td>876.97</td>
						<td>670.23</td>
						<td>644.67</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>21.19</td>
						<td>14.24</td>
						<td>1.03</td>
						<td>0.35</td>
						<td>6.49</td>
						<td></td>
					</tr>
					<tr class="section-row">
						<td>56 to 70</td>
						<td>136</td>
						<td>107</td>
						<td>181</td>
						<td>248</td>
						<td>97</td>
						<td class="total-val">769</td>
					</tr>
					<tr>
						<td></td>
						<td>124.27</td>
						<td>44.68</td>
						<td>240.08</td>
						<td>183.48</td>
						<td>176.49</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>1.11</td>
						<td>86.93</td>
						<td>14.54</td>
						<td>22.69</td>
						<td>35.80</td>
						<td></td>
					</tr>
					<tr class="section-row">
						<td>71 to 85</td>
						<td>0</td>
						<td>5</td>
						<td>8</td>
						<td>9</td>
						<td>3</td>
						<td class="total-val">25</td>
					</tr>
					<tr>
						<td></td>
						<td>4.04</td>
						<td>1.45</td>
						<td>7.81</td>
						<td>5.97</td>
						<td>5.74</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>4.04</td>
						<td>8.66</td>
						<td>0.00</td>
						<td>1.54</td>
						<td>1.31</td>
						<td></td>
					</tr>
					<tr class="total-row">
						<td>All</td>
						<td>1616</td>
						<td>581</td>
						<td>3122</td>
						<td>2386</td>
						<td>2295</td>
						<td>10000</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-lg-6">
			<h2 class="w-100 section-title"><hr>Area Risk / Claim Value<hr></h1>
			<h4 class="w-100">Tabulated Statistics</h4>
			<table class="tab-stats w-100 table-hover table-bordered">
				<thead>
					<th>Area Risk</th>
					<th>Battery</th>
					<th>Loss</th>
					<th>Other</th>
					<th>Screen</th>
					<th>Water</th>
					<th>All</th>
				</thead>
				<tbody>
					<tr class="section-row">
						<td>High</td>
						<td>1342</td>
						<td>424</td>
						<td>2447</td>
						<td>1840</td>
						<td>1959</td>
						<td>8012</td>
					</tr>
					<tr>
						<td></td>
						<td>1304.35</td>
						<td>448.67</td>
						<td>2442.06</td>
						<td>2028.64</td>
						<td>1788.28</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>1.09</td>
						<td>1.36</td>
						<td>0.01</td>
						<td>17.54</td>
						<td>16.30</td>
						<td></td>
					</tr>
					<tr class="section-row">
						<td>Low</td>
						<td>117</td>
						<td>50</td>
						<td>351</td>
						<td>342</td>
						<td>118</td>
						<td>978</td>
					</tr>
					<tr>
						<td></td>
						<td>159.22</td>
						<td>54.77</td>
						<td>298.09</td>
						<td>247.63</td>
						<td>218.29</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>11.19</td>
						<td>0.42</td>
						<td>9.39</td>
						<td>35.96</td>
						<td>46.08</td>
						<td></td>
					</tr>
					<tr class="section-row">
						<td>Medium</td>
						<td>169</td>
						<td>86</td>
						<td>250</td>
						<td>350</td>
						<td>155</td>
						<td>1010</td>
					</tr>
					<tr>
						<td></td>
						<td>164.43</td>
						<td>56.56</td>
						<td>307.85</td>
						<td>255.73</td>
						<td>225.43</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>0.13</td>
						<td>15.32</td>
						<td>10.87</td>
						<td>34.75</td>
						<td>22.01</td>
						<td></td>
					</tr>
					<tr class="total-row">
						<td>All</td>
						<td>1628</td>
						<td>560</td>
						<td>3048</td>
						<td>2532</td>
						<td>2232</td>
						<td>10000</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row m-0 content-col">
		<div class="col-lg-6">
			<h4 class="w-100">Chi-Square Test</h4>
			<table class="w-100 table-hover table-bordered">
				<thead>
					<th></th>
					<th>Chi-Square</th>
					<th>DF</th>
					<th>P-Value</th>
				</thead>
				<tbody>
					<tr>
						<td>Pearson</td>
						<td>403.46</td>
						<td>16</td>
						<td>0.0001</td>
					</tr>
					<tr>
						<td>Likelihood Ratio</td>
						<td>386.35</td>
						<td>16</td>
						<td>0.0001</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-lg-6">
			<h4 class="w-100">Chi-Square Test</h4>
			<table class="w-100 table-hover table-bordered">
				<thead>
					<th></th>
					<th>Chi-Square</th>
					<th>DF</th>
					<th>P-Value</th>
				</thead>
				<tbody>
					<tr>
						<td>Pearson</td>
						<td>222.41</td>
						<td>8</td>
						<td>0.0001</td>
					</tr>
					<tr>
						<td>Likelihood Ration</td>
						<td>226.36</td>
						<td>8</td>
						<td>0.0001</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row m-0 content-col">
		<div class="col-lg-6">
			<h2 class="w-100 section-title"><hr>Case / Claim Value<hr></h2>
			<h4 class="w-100">Tabulated Statistics</h4>
			<table class="tab-stats w-100 table-hover table-bordered">
				<thead>
					<th>Has Case</th>
					<th>Battery</th>
					<th>Loss</th>
					<th>Other</th>
					<th>Screen</th>
					<th>Water</th>
					<th>All</th>
				</thead>
				<tbody>
					<tr>
						<td>No</td>
						<td>335</td>
						<td>194</td>
						<td>821</td>
						<td>1267</td>
						<td>706</td>
						<td>3323</td>
					</tr>
					<tr>
						<td></td>
						<td>540.98</td>
						<td>186.09</td>
						<td>1012.85</td>
						<td>841.38</td>
						<td>741.69</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>78.43</td>
						<td>0.34</td>
						<td>36.34</td>
						<td>215.30</td>
						<td>1.72</td>
						<td></td>
					</tr>
					<tr>
						<td>Yes</td>
						<td>1293</td>
						<td>366</td>
						<td>2227</td>
						<td>1265</td>
						<td>1526</td>
						<td>6677</td>
					</tr>
					<tr>
						<td></td>
						<td>1087.02</td>
						<td>373.91</td>
						<td>2035.15</td>
						<td>1690.62</td>
						<td>1490.31</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>39.03</td>
						<td>0.17</td>
						<td>18.09</td>
						<td>107.15</td>
						<td>0.85</td>
						<td></td>
					</tr>
					<tr class="total-row">
						<td>All</td>
						<td>1628</td>
						<td>560</td>
						<td>3048</td>
						<td>2532</td>
						<td>2232</td>
						<td>10000</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-lg-6">
			<h2 class="w-100 section-title"><hr>Type of Phone / Claim Value<hr></h2>
			<h4 class="w-100">Tabulated Statistics</h4>
			<table class="tab-stats w-100 table-hover table-bordered">
				<thead>
					<th></th>
					<th>Battery</th>
					<th>Loss</th>
					<th>Other</th>
					<th>Screen</th>
					<th>Water</th>
					<th>All</th>
				</thead>
				<tbody>
					<tr>
						<td>Oops Midtier</td>
						<td>791</td>
						<td>176</td>
						<td>917</td>
						<td>671</td>
						<td>529</td>
						<td>3084</td>
					</tr>
					<tr>
						<td></td>
						<td>502.08</td>
						<td>172.70</td>
						<td>940.00</td>
						<td>780.87</td>
						<td>688.35</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>166.27</td>
						<td>0.06</td>
						<td>0.56</td>
						<td>15.46</td>
						<td>36.89</td>
						<td></td>
					</tr>
					<tr>
						<td>Oops Premium</td>
						<td>123</td>
						<td>103</td>
						<td>486</td>
						<td>370</td>
						<td>454</td>
						<td>4536</td>
					</tr>
					<tr>
						<td></td>
						<td>250.06</td>
						<td>86.02</td>
						<td>468.17</td>
						<td>388.92</td>
						<td>342.84</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>64.56</td>
						<td>3.35</td>
						<td>0.68</td>
						<td>0.92</td>
						<td>36.05</td>
						<td></td>
					</tr>
					<tr>
						<td>Sturdy Midtier</td>
						<td>348</td>
						<td>149</td>
						<td>990</td>
						<td>809</td>
						<td>667</td>
						<td>2963</td>
					</tr>
					<tr>
						<td></td>
						<td>482.38</td>
						<td>165.93</td>
						<td>903.12</td>
						<td>750.23</td>
						<td>661.34</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>37.43</td>
						<td>1.73</td>
						<td>8.36</td>
						<td>4.60</td>
						<td>0.05</td>
						<td></td>
					</tr>
					<tr>
						<td>Sturdy Premium</td>
						<td>366</td>
						<td>132</td>
						<td>655</td>
						<td>682</td>
						<td>582</td>
						<td>2417</td>
					</tr>
					<tr>
						<td></td>
						<td>393.49</td>
						<td>135.35</td>
						<td>736.70</td>
						<td>611.98</td>
						<td>539.47</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>1.92</td>
						<td>0.08</td>
						<td>9.06</td>
						<td>8.01</td>
						<td>3.35</td>
						<td></td>
					</tr>
					<tr class="total-row">
						<td>All</td>
						<td>1628</td>
						<td>560</td>
						<td>3048</td>
						<td>2532</td>
						<td>2232</td>
						<td>10000</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row m-0 content-col">
		<div class="col-lg-6">
			<h4 class="w-100">Chi-Square Test</h4>
			<table class="w-100 table-hover table-bordered">
				<thead>
					<th></th>
					<th>Chi-Square</th>
					<th>DF</th>
					<th>P-Value</th>
				</thead>
				<tbody>
					<tr>
						<td>Pearson</td>
						<td>497.41</td>
						<td>4</td>
						<td>0.0001</td>
					</tr>
					<tr>
						<td>Likelihood Ratio</td>
						<td>490.72</td>
						<td>4</td>
						<td>0.0001</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-lg-6">
			<h4 class="w-100">Chi-Square Test</h4>
			<table class="w-100 table-hover table-bordered">
				<thead>
					<th></th>
					<th>Chi-Square</th>
					<th>DF</th>
					<th>P-Value</th>
				</thead>
				<tbody>
					<tr>
						<td>Pearson</td>
						<td>399.39</td>
						<td>12</td>
						<td>0.0001</td>
					</tr>
					<tr>
						<td>Likelihood Ratio</td>
						<td>393.63</td>
						<td>12</td>
						<td>0.0001</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row m-0 content-col">
		<div class="col-lg-6">
			<h2 class="w-100 section-title"><hr>Year Produced / Claim Value<hr></h2>
			<h4 class="w-100">Tabulated Statistics</h4>
			<table class="w-100 tab-stats table-bordered table-hover">
				<thead>
					<th></th>
					<th>Battery</th>
					<th>Loss</th>
					<th>Other</th>
					<th>Screen</th>
					<th>Water</th>
					<th>All</th>
				</thead>
				<tbody>
					<tr>
						<td>2013</td>
						<td>100</td>
						<td>26</td>
						<td>117</td>
						<td>118</td>
						<td>71</td>
						<td>442</td>
					</tr>
					<tr>
						<td></td>
						<td>71.96</td>
						<td>24.75</td>
						<td>134.72</td>
						<td>111.91</td>
						<td>98.65</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>20.11</td>
						<td>0.06</td>
						<td>2.33</td>
						<td>0.33</td>
						<td>7.75</td>
						<td></td>
					</tr>
					<tr>
						<td>2014</td>
						<td>120</td>
						<td>27</td>
						<td>211</td>
						<td>275</td>
						<td>361</td>
						<td>994</td>
					</tr>
					<tr>
						<td></td>
						<td>161.82</td>
						<td>55.66</td>
						<td>302.97</td>
						<td>251.68</td>
						<td>221.86</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>10.81</td>
						<td>14.76</td>
						<td>27.92</td>
						<td>2.16</td>
						<td>87.26</td>
						<td></td>
					</tr>
					<tr>
						<td>2015</td>
						<td>505</td>
						<td>141</td>
						<td>190</td>
						<td>486</td>
						<td>119</td>
						<td>1441</td>
					</tr>
					<tr>
						<td></td>
						<td>234.59</td>
						<td>80.70</td>
						<td>439.22</td>
						<td>364.83</td>
						<td>321.63</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>311.68</td>
						<td>45.07</td>
						<td>141.41</td>
						<td>40.22</td>
						<td>127.66</td>
						<td></td>
					</tr>
					<tr>
						<td>2016</td>
						<td>384</td>
						<td>78</td>
						<td>649</td>
						<td>392</td>
						<td>490</td>
						<td>1993</td>
					</tr>
					<tr>
						<td></td>
						<td>324.46</td>
						<td>111.61</td>
						<td>607.47</td>
						<td>504.63</td>
						<td>444.84</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>10.93</td>
						<td>10.12</td>
						<td>2.84</td>
						<td>25.14</td>
						<td>4.59</td>
						<td></td>
					</tr>
					<tr>
						<td>2017</td>
						<td>64</td>
						<td>196</td>
						<td>1048</td>
						<td>729</td>
						<td>528</td>
						<td>2565</td>
					</tr>
					<tr>
						<td></td>
						<td>417.58</td>
						<td>143.64</td>
						<td>781.81</td>
						<td>649.46</td>
						<td>572.51</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>299.39</td>
						<td>19.09</td>
						<td>90.63</td>
						<td>9.74</td>
						<td>3.46</td>
						<td></td>
					</tr>
					<tr>
						<td>2018</td>
						<td>445</td>
						<td>92</td>
						<td>833</td>
						<td>532</td>
						<td>663</td>
						<td>2565</td>
					</tr>
					<tr>
						<td></td>
						<td>417.58</td>
						<td>143.64</td>
						<td>781.81</td>
						<td>649.49</td>
						<td>572.51</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>1.80</td>
						<td>18.57</td>
						<td>3.35</td>
						<td>21.24</td>
						<td>14.30</td>
						<td></td>
					</tr>
					<tr class="total-row">
						<td>All</td>
						<td>1628</td>
						<td>560</td>
						<td>3048</td>
						<td>2532</td>
						<td>2232</td>
						<td>10000</td>
					</tr>
				</tbody>
			</table>
			<h4 class="w-100">Chi-Square Test</h4>
			<table class="w-100 table-bordered table-hover">
				<thead>
					<th></th>
					<th>Chi-Square</th>
					<th>DF</th>
					<th>P-Value</th>
				</thead>
				<tbody>
					<td>Pearson</td>
					<td>1374.72</td>
					<td>20</td>
					<td>0.0001</td>
				</tbody>
			</table>
		</div>
		<div class="col-lg-6">
		</div>
	</div>
	<h1 class="w-100 big-title"><hr>Determining Premiums<hr></h1>
	<div class="row m-0 content-col">
	<table class="w-100 table-hover table-bordered">
		<thead>
			<th>Risk Name</th>
			<th>Phone Type</th>
			<th>Deductable amount</th>
			<th>Premium</th>
			<th>Total Cost of Claim</th>
			<th>Combined Ratio</th>
		</thead>
		<tbody>
			<?php require 'calcPremiums.php'?>
		</tbody>
	</table>
	</div>
</body>




