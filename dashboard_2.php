<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
include("include/dbconnect.php");
include("include/functions.php");

?>

<!DOCTYPE html>

<head>
	<html lang="sk">
	<meta charset="utf-8" />
	<name="viewport" content="width=device-width; height=device-height; initial-scale=1.0">
		<link rel="stylesheet" href="css/style_new.css?<?php echo time() ?>" type="text/css">
		<link rel="stylesheet" href="css/dashboard.css?<?php echo time() ?>" type="text/css">
		<!--<link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">-->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/1.3.2/css/weather-icons.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0/angular.min.js"></script>
		
		<title>axoloth - dashboard</title>
		<link rel="icon" type="image/x-icon" href="eis.png?v=2"  />
</head>

<body ng-app="myApp" ng-controller="MainCtrl">


	<?php // Change or ommit page-extension in URL
	if (!isset($page_ext))
		$page_ext = ".php";
	$page_ext_qry = "$page_ext.?";

	?>
	<!-- header -->

	<?php include("include/header.php") ?>

	<!--</div> -->
	<!-- Body -->

	<div id="layout">
		<!-- <div id="new_session">
			WELCOME TO THE <b>AXOLOTH</b> INFORMATING SYSTEM <?php echo date('j.n.Y')?>&nbsp;<span class="location_city">{{city}}</span> <span class="temp">{{temp}} &#176;</span>&nbsp;<span class="icon"><i ng-class="getIcon()"></i></span>&nbsp;&nbsp;<span id="curr_time"></span>
	    </div> --> <!-- new session information -->

		<div id="panels">
			<div class="dash_panel" onclick="show_module('events')"> 
				<div class="dash_body">
                    <div class="left-panel-side"><span><i class="far fa-calendar-alt"></i></span></div>
                    <div class="right-panel-side"><span><?php echo GetCountAllEvents(); ?></span></div>
                </div>  
                <div class="dash_footer">
                    <span>Newest Events: <?php echo GetCountNewestEvents(); ?></span>
	            </div>	
			</div>
		    
            <div class="dash_panel" onclick="show_module('tasks')">
        	    <div class="dash_body">
                    <div class="left-panel-side"><span><i class="fas fa-tasks"></i></span></div>
                    <div class="right-panel-side"><span><?php echo GetCountofActiveTasks(); ?></span></div>
	    		</div>
                <div class="dash_footer">
                    <span>Newest tasks: <?php echo GetCountNewestTasks(); ?></span>
                    </div>
                </div>  
	
            <div class="dash_panel" onclick="show_module('notes')">
            	<div class="dash_body">
                    <div class="left-panel-side"><span><i class="far fa-edit"></i></span></div>
                    <div class="right-panel-side"><span><?php echo GetCountofAllnotes(); ?></span></div>
    			</div>
                <div class="dash_footer">
                    <span>Newest notes: <?php echo GetCountNewestNotes(); ?></span>
                </div>
            </div>  
			
            <div class="dash_panel" onclick="show_module('passwords')">
                <div class="dash_body">
				   <div class="left-panel-side"><span><i class="fas fa-key"></i></span></div>
				   <div class="right-panel-side"><span><?php echo GetCountofAllPasswords(); ?></span></div>
			    </div>
                <div class="dash_footer">
                    <span>Newest passwords: <?php echo GetCountNewestPasswords(); ?></span>
	            </div>
            </div>  

			<div class="dash_panel" onclick="show_module('customers')">
        	    <div class="dash_body">
				    <div class="left-panel-side"><span><i class="fas fa-users"></i></span></div>
				    <div class="right-panel-side"><span><?php echo GetCountofAllCustomers(); ?></span></div>
			    </div>
                <div class="dash_footer">
                    <span>Newest customers: 0</span>
	            </div>
		    </div>

            <div class="dash_panel" onclick="show_module('trading')">
        	    <div class="dash_body">
				    <div class="left-panel-side"><span><i class="fas fa-coins"></i></span></div>
				    <div class="right-panel-side"><span><?php echo GetCountofAllTradingPlatforms(); ?></span></div>
			    </div>
                <div class="dash_footer">
                    <span>Newest platforms: <?php echo GetCountNewestPlatforms()?></span>
	            </div>
		    </div>

		<div id="applications">
		</div>

	</div><!-- layout -->
	<script>
		  	
	 	  var app = angular.module('myApp', []);

            app.factory('WeatherApi', function($http) {
                var obj = {};

                obj.getLoc = function() {
                    return $http.jsonp("https://ipinfo.io/json?callback=JSON_CALLBACK");
                };
                obj.getWeather = function(latitude, longitude) {
                    var api = "https://api.openweathermap.org/data/2.5/weather?";
                    var units = "&units=metric";
                    var appid = '&appid=270245b93dc95a2783a7a4937a627994';
                    var cb = "&callback=JSON_CALLBACK";

                    return $http.jsonp(api + latitude + longitude + units + appid + cb);
                };

                return obj;
            });

            app.controller('MainCtrl', function($scope, WeatherApi) {
                var icons = {
                    'Clear': {
                        'day': "wi wi-day-sunny",
                        'night': "wi wi-night-clear"
                    },
                    'Clouds': "wi wi-cloudy",
                    'Snow': "wi wi-snow",
                    'Rain': "wi wi-rain",
                    'Drizzle': "wi wi-sprinkle",
					'Thunderstorm': "wi wi-thunderstorm",
					'Fog':'wi wi-fog',
					'Mist':'wi wi-showers',
                };

                $scope.unit = 'C';
                var isChanged = false;
                WeatherApi.getLoc().success(function(data) {

                    var lat = 'lat=' + data.loc.split(',')[0],
                        lon = '&lon=' + data.loc.split(',')[1];


                    $scope.country = data.country;
                    WeatherApi.getWeather(lat, lon).success(function(data) {
                        $scope.city = data.name;
                        var iconKey = data.weather[0].main;

                        $scope.getIcon = function() {
                            if (icons[iconKey]) {
                                if (iconKey === 'Clear') {
                                    return icons[iconKey][dayOrNight()];
                                }
                                return icons[iconKey];
                            }
                        };

                        cityWeather(data)
                    });
                });

                function cityWeather(data) {
                    $scope.temp = $scope.cel = Math.round(data.main.temp);
                    $scope.fah = Math.round(($scope.temp * 9) / 5 + 32);
                }

                $scope.changeUnit = function() {
                    if (isChanged) {
                        $scope.unit = 'C';
                        $scope.temp = $scope.cel;
                        return isChanged = false;
                    }
                    $scope.unit = 'F';
                    $scope.temp = $scope.fah;
                    return isChanged = true;
                };

                function dayOrNight() {
                    var hours = new Date().getHours();
                    if (hours > 6 && hours < 20) {
                        return 'day';
                    }
                    return 'night';
                }

            });

			function show_module(module){
				if(module=='tasks'){
					window.location.href="tasks/index.php"
				}else if(module=='passwords'){
					window.location.href="passmgr/index.php"
				} else if(module=='notes'){
					window.location.href="notepad/index.php"
				} else if(module=='events'){
					window.location.href="events/index.php"
				}else if(module=='customers'){
					window.location.href="customers/index.php"
				}else if(module=='trading'){
                    window.location.href="trading/index.php"
                } 
			}
        </script>
</body>

</html>