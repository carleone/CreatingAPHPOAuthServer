<?php
require_once '../vendor/autoload.php';

session_start();

// Add a header indicating this is an OAuth server
header('X-XRDS-Location: http://' . $_SERVER['SERVER_NAME'] .
     '/services.xrds.php');

// Connect to database
$db = new PDO('mysql:host=localhost;dbname=oauth', 'dbuser', 'dbpassword');

// Create a new instance of OAuthStore and OAuthServer
$store = OAuthStore::instance('PDO', array('conn' => $db));
$server = new OAuthServer();
