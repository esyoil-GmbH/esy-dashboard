<?php
/**
 * Created by PhpStorm.
 * User: bennet
 * Date: 18.04.18
 * Time: 20:32
 */
require("vendor/autoload.php");

use \Angle\Engine\RouterEngine\Collection;
use \Angle\Engine\RouterEngine\Route;
use \Angle\Engine\RouterEngine\Router;


use Tracy\Debugger;

Debugger::enable();

$engine = new \Angle\Engine\Template\Engine();

$router = new Collection();

define("CACHE_FOLDER", false);
define("TEMPLATES_FOLDER", "templates");

$router->attachRoute(new Route('/', array(
    '_controller' => '\Angle\Examples\Controllers\Dashboard::display',
    'parameters' => ["engine" => $engine],
    'methods' => 'GET'
)));

$router = new Router($router);
$route = $router->matchCurrentRequest();