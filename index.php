<?php
require __DIR__ . "/vendor/autoload.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

use CoffeeCode\Router\Router;

$router = new Router(BASE_URL);
$router->namespace("Source\Controller");

$router->post("/correios/track", "CorreiosController:track");
$router->post("/email/send", "EmailController:send");

$router->post("/trackAndEmail", "TrackAndSendEmailController:send");

$router->group("error");
$router->get("/{errcode}", function($data) {
  echo "<h1> Erro {$data["errcode"]}</h1>";
  var_dump($data);
});

$router->dispatch();

if ($router->error()) {
  $router->redirect("/error/{$router->error()}");
}
