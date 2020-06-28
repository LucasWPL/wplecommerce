<?php 

session_start();
require_once("vendor/autoload.php");

use Slim\Slim;
use Wpl\Page;
use Wpl\PageAdmin;
use Wpl\Models\User;
use Wpl\Models\Category;

$app = new Slim();
$app->config('debug', true);

require_once("site.php");
require_once("functions.php");
require_once("admin.php");
require_once("admin-user.php");
require_once("admin-category.php");
require_once("admin-product.php");

$app->run();

 ?>