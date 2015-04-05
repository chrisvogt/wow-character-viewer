<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim;

$app->view(new \Slim\Views\Twig());

$app->get('/', function() use ($app) {
    $app->render('viewer.html', [
      'character' => $app->request()->get('character'),
      'server'    => $app->request()->get('server'),
      'og_meta'      => assembleOgMeta()
    ]);
});

$app->run();

// ---- WoW Character Viewer Extras

function assembleOgMeta()
{
  $html = "";
  $response = [];

  if (isset($_GET['character']) && isset($_GET['server'])) {
    $char = $_GET['character'];
    $serv = $_GET['server'];
    $response = requestCharacter($char, $serv);
  }

    $html .= assembleOgDesc($response) . "\n";
    $html .= assembleOgTitle($response) . "\n";
    $html .= assembleOgImage($response);

  return $html;
}

function assembleOgTitle($data)
{
  $content = "WoW Character Viewer";
  if (isset($data['name'])) {
    $content = "{$data['name']} on Wow Character Viewer";
  }
  return "<meta property='og:title' content='$content' />";
}

function assembleOgDesc($character)
{
  $content = "Use WoW Profile Viewer to find and share your World of Warcraft character information and avatar.";

  if (isset($character['name']) && isset($character['thumbnail']) && isset($character['level']) && isset($character['achievementPoints'])) {
    $content = "Overview of my WoW character. {$character['name']} is level {$character['level']} and has {$character['achievementPoints']} achievement points.";
  }

  return "<meta property='og:description' content='$content' />";
}

function assembleOgImage($data)
{
  $content = "http://res.cloudinary.com/chrisvogt/image/upload/v1420461939/wow-profile-card_p6i5ys.jpg";

  if (isset($data['thumbnail'])) {
    $content = "http://us.battle.net/static-render/us/" . $data['thumbnail'];
  }
  return "<meta property='og:image' content='$content' />";
}

function requestCharacter($character, $server)
{
  $response = json_decode(file_get_contents("http://us.battle.net/api/wow/character/$server/$character?fields=appearance&jsonp="), true);
  return $response;
}
