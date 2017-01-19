<?php
/*
Plugin Name: RC Weather Widget
Plugin URI: https://github.com/rcadhikari
Description: Fetch the given city weather instantly
Version: 1.0
Author: RC Adhikari
Author URI: https://github.com/rcadhikari
License: MIT
License URI: http://opensource.org/licenses/MIT
*/

class Wp_Free_Weather extends WP_Widget
{
    function __construct()
    {
        parent::__construct(false, $name = __('Free Weather Widget'));
    }

    function form()
    {
        // code here
    }

    function update()
    {
        // code here
    }

    function widget($args, $instance)
    {
        ?>
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin">
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
        <style type="text/css">
                .sam {
                    height: 500px;
                    width: 320px;
                    background-color: rgba(237, 237, 237, 0.70);
                }

                .box {
                    height: 49px;
                    width: 152px;
                    background-color: rgba(222, 221, 221, 0.7);
                    float: right;
                }

                .heading {
                    padding-top: 10px;
                    padding-left: 30px;
                    font-family: "Arial Bold";
                    font-size: 14pt;
                    color: #464545;
                    font-weight: bold;

                }

                .head {
                    padding-left: 30px;
                    font-family: Ubuntu, "times new roman", times, roman, serif;
                    font-size: 17pt;
                    color: #464545;
                }

                .deg {
                    font-size: 12px;
                    padding-left: 30px;
                    font-family: Ubuntu, "times new roman", times, roman, serif;
                    font-size: 12pt;
                    color: #898989;
                }
            </style>

        <div ng-app="WpFreeWeatherApp" ng-controller="WpFreeWeatherController" class="sam">
            <div class="box"></div>
            <div class="heading">WEATHER</div>
            <br><br>

            <div class="head">New York</div>
            <div class="deg"> {{myData.list[0].main.temp}} Degrees</div>
            <br><br>

            <div class="head">Paris</div>
            <div class="deg">{{myData.list[1].main.temp}} Degrees</div>
            <br><br>

            <div class="head">Singapore</div>
            <div class="deg">{{myData.list[2].main.temp}} Degrees</div>
            <br><br>

            <div class="head">Tokyo</div>
            <div class="deg">{{myData.list[3].main.temp}} Degrees</div>
        </div>
        <script>
            var app = angular.module('WpFreeWeatherApp', []);
            app.controller('WpFreeWeatherController', function ($scope, $http) {
                $http.get("http://api.openweathermap.org/data/2.5/group?id=5128638,6455259,1880251,1850147&units=metric&appid=6cefbacb8b2e0ed4a6fdd645c8acb769")
                    .then(function (response)
                    {
                        $scope.myData = response.data;
                        console.log($scope.myData);
                    });
            });
        </script>
        <?php
    }
}

function wp_free_weather()
{
    register_widget('Wp_Free_Weather');
}

add_action('widgets_init', 'wp_free_weather');
?>