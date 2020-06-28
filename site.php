<?php

use \Wpl\Page;
use \Wpl\Models\Product;

$app->get('/', function() {

	$products = Product::listAll();
	$page = new Page();

	$page -> setTpl("index",[
		'products'=> Product::checkList($products)
	]);
	
});

?>