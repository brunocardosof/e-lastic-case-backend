<?php
require __DIR__ ."/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

define("MAIL",[
  "host" => $_ENV['HOST'],
  "port" => $_ENV['PORT'],
  "user" => $_ENV['USER'],
  "password" => $_ENV['PASSWORD'],  
  "from_name" => $_ENV['FROM_NAME'],
  "from_email" => $_ENV['FROM_EMAIL'],
]);

define("BASE_URL", "http://localhost/".__DIR__."/../");