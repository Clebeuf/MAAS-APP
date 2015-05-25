<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>MAAS Explorer</title>

		<!-- jQuery min (JUST IN CASE) -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

		<!-- BOOTSTRAP COMPONENTS-->
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>


		<!-- CUSTOM STYLESHEET -->
		<link rel="stylesheet" type="text/css" href="CSS/custom.css" />
		<link rel="stylesheet" type="text/css" href="CSS/weather-icons.min.css" />

		<link rel="icon" type="image/png" href="favicon.png">


		<link rel="stylesheet/less" type="text/css" href="weather-icons/weather-icons.less" />

	</head>

	<body>

		<?php
			date_default_timezone_set('UTC'); //set timezone

			// GET LATEST DATA

			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => "http://marsweather.ingenology.com/v1/latest/"
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			// Close request to clear up some resources
			curl_close($curl);

			$latestData = json_decode($resp, true);

			// Store Lastest Weather Variables
			$latestDayMars = $latestData[report][sol];
			$latestDateEarth = new DateTime($latestData[report][terrestrial_date]);
			$latestDateEarth = $latestDateEarth->format('l M jS, Y');
			$latestMinTemp = $latestData[report][min_temp];
			$latestMaxTemp = $latestData[report][max_temp];
			$latestPressure = $latestData[report][pressure];
			$latestPressureString = $latestData[report][pressure_string];
			$latestHumidity = $latestData[report][abs_humidity];
			$latestWindSpeed = $latestData[report][wind_speed];
			$latestWindDirection = $latestData[report][wind_direction];
			$latestWeather = $latestData[report][atmo_opacity];
			$latestSeason = $latestData[report][season];
			$latestSunrise = new DateTime($latestData[report][sunrise]);
			$latestSunrise = $latestSunrise->format(' g:i A');
			$latestSunset = new DateTime($latestData[report][sunset]);
			$latestSunset = $latestSunset->format(' g:i A');

			// Calculate Average Temp
			$latestAverageTemp = ($latestMinTemp + $latestMaxTemp)/2;
		?>

		<div class="page-header">
		  <h1>Day <?=$latestDayMars?> on Mars <br><small><?=$latestDateEarth?> (Earth Time)</small></h1>
		</div>

		<div class="container" id="main-container">


		<!-- current condition boxs -->
		<div class='row'>

			<!-- Weather -->
			<div class="col-sm-4">
				<div class="box">
					<div class="container-fluid box-header yellow">
						<div class="row">
							<div class="col-xs-12">
							    <i class="wi wi-day-sunny"></i>
					      	</div>
				      	</div>
				    </div>
				    <div class="row box-content">
				    	<div class="col-xs-12">
					    	<h3><?=$latestWeather?></h3>
					    </div>
				    </div>
			        <div class="row box-content">
							<div class="col-xs-6">
								<?=$latestAverageTemp?> °C
							</div>
							<div class="col-xs-6">
								<span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span>  <?=$latestMaxTemp?> °C
								<br>
								<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>  <?=$latestMinTemp?> °C
							</div>
					</div>
			    </div>
			</div>

			<!-- Season -->
			<div class="col-sm-4">
				<div class="box">
					<div class="container-fluid box-header pink">
						<div class="row">
							<div class="col-xs-12">
							    <i class="wi wi-alien"></i>
					      	</div>
				      	</div>
				    </div>
				    <div class="row box-content">
				    	<div class="col-xs-12">
					    	<h3><?=$latestSeason?></h3>
					    </div>
				    </div>
			        <div class="row box-content">
							<div class="col-xs-6">
									<i class="wi wi-sunrise"></i><?=$latestSunrise?>
								</div>
								<div class="col-xs-6">
									<i class="wi wi-sunset"></i><?=$latestSunset?>
							</div>
					</div>
			    </div>
			</div>

			<!-- Wind/Pressure -->
			<div class="col-sm-4">
				<div class="box">
					<div class="container-fluid box-header teal">
						<div class="row">
							<div class="col-xs-12">
							    <i class="wi wi-strong-wind"></i>
					      	</div>
				      	</div>
				    </div>
				    <div class="row box-content">
				    	<div class="col-xs-12">
					    	<h3>Wind: <?=$latestWindSpeed?> (<?= $latestWindDirection?>)</h3>
					    </div>
				    </div>
			        <div class="row box-content">
							<div class="col-xs-6">
								Pressure: <?=$latestPressure?>
								<br>(<?=$latestPressureString?>)
							</div>
							<div class="col-xs-6">
								Humidity: <?=$latestHumidity?>
							</div>
					</div>
			    </div>
			</div>

		</div>
		<!-- end of current condition boxs -->

		<!-- set placeholders for date range -->
		<?php
					// GET DATE RANGE
					if(isset($_POST["start"])){
						$start = $_POST["start"];
					}else{
						$start = "2012-08-27";
					}

					if(isset($_POST["end"])){
						$end = $_POST["end"];
					}else{
						$today = getdate();
						$d = $today[mday] - 3;
						if(strlen($d) == 1){
							$d = '0'.$d;
						}
						$m = $today[mon];
						if(strlen($m) == 1){
							$m = '0'.$m;
						}
						$y = $today[year];
						$end = $y."-".$m."-".$d;
					}
		?>


		<!-- container that holds the graph -->
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="container-fluid graph-header">
						<div class="row">
							<div class="col-xs-12">
							    <h3>Long Range Temperature</h3>
					      	</div>
				      	</div>
				    </div>
					<div class="row">
						<div class="col-xs-12">
							<div id="graph" class="aGraph"></div>
						</div>
					</div>
					<div class="row box-content form-box">
						<?php
							echo "
								<form action='index.php' method='post' class='form-inline'>
								<div class='col-sm-12'><lable>Start Date: </lable><input type='date' class='form-control' name='start' id='start' min='2012-08-27' max='2015-05-21' value='".$start."'>
								<lable>End Date: </lable><input type='date' class='form-control' name='end' id='end' min='2012-08-27' max='2015-05-21' value='".$end."'>
								<input type='submit' class='btn btn-warning'></div>
							</form>";
						?>
					</div>
			    </div>
			</div>
		</div>

		<!-- get date range using curl -->
		<?php

			$temp_data = array('date' => '', 'max_temp' => '', 'min_temp' => '');
			$max_temp = -100;
			$min_temp = 100;
			
			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => "http://marsweather.ingenology.com/v1/archive/?terrestrial_date_start=".$start."&terrestrial_date_end=".$end
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			// Close request to clear up some resources
			curl_close($curl);

			$data = json_decode($resp, true);

			$count = 1;

			while($data["next"]){
				// Get cURL resource
				$curl = curl_init();
				// Set some options - we are passing in a useragent too here
				curl_setopt_array($curl, array(
				    CURLOPT_RETURNTRANSFER => 1,
				    CURLOPT_URL => $data['next']
				));

				// Send the request & save response to $resp
				$resp = curl_exec($curl);
				// Close request to clear up some resources
				curl_close($curl);

				$data = json_decode($resp, true);

				$i = 0;
				while($data[results][$i][terrestrial_date]){
					if($data[results][$i][terrestrial_date] && $data[results][$i][max_temp] && $data[results][$i][min_temp]){
						$new_data = array('date' => $data[results][$i][terrestrial_date], 'max_temp' => $data[results][$i][max_temp], 'min_temp' => $data[results][$i][min_temp]);
						array_push($temp_data, $new_data);
						if($min_temp > $data[results][$i][min_temp]){
							$min_temp = $data[results][$i][min_temp];
						}
						if($max_temp < $data[results][$i][max_temp]){
							$max_temp = $data[results][$i][max_temp];
						}
						$count++;
					}
					$i++;
				}
			}
		
		$temp_data = array_slice($temp_data, 3);
	?>


	<div class="footer">
		© 2015 All rights reserved
	<div>

	<!-- set javascript variables -->
	<script type="text/javascript">
		var startTime = new Date(<?php echo "\"".$start."\"" ?>);
		var endTime = new Date(<?php echo "\"".$end."\"" ?>);
		var data = <?php 
				echo "[";
				foreach($temp_data as $key => $value){
					echo "[\"".$value[date]."\",".$value[max_temp].",".$value[min_temp]."],";
				}
				echo "]";
		?>;
		var max_temp = <?php echo $max_temp ?>;
		var min_temp = <?php echo $min_temp ?>;
	</script>

	<!-- D3.js script -->
	<script src="http://d3js.org/d3.v3.js"></script>

	<!-- include custom js script -->
	<script src="JS/custom.js"></script>

	</body>
</html>