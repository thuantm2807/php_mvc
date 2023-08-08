<?php

include_once('inc/config.php');
include_once('inc/routes.php');
include_once('inc/controller.php');
include_once('inc/render.php');
include_once('inc/model.php');
include_once('inc/database.php');
include_once('inc/helper.php');

$route = $_GET['main_route'] ?? "";
new Routes($route);