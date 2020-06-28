<?php

use \Wpl\Page;
use \Wpl\Models\Product;
use \Wpl\Models\Category;

$app->get('/', function() {

	$products = Product::listAll();
	$page = new Page();

	$page -> setTpl("index",[
		'products'=> Product::checkList($products)
	]);
	
});

$app-> get ("/categories/:idcategory", function($idcategory){

	$category = new Category();

	$products = new Product();

	$category -> get((int)$idcategory);

	$page = new Page();

	$page -> setTpl("category", [
		'category'=> $category->getData(),
		'products'=> Product::checkList($category-> getProducts())
	]);
});


?>