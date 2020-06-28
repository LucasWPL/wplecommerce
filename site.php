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

	$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

	$category = new Category();

	$category -> get((int)$idcategory);
	
	$pagination = $category-> getProductsPage($page);

	$pages = [];

	for ($i=1; $i <= $pagination['pages']; $i++) { 
		array_push($pages, [
			'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
			'page'=>$i
		]);
	}

	$page = new Page();

	$page -> setTpl("category", [
		'category'=> $category->getData(),
		'products'=> $pagination['data'],
		'pages'=>$pages
	]);
});

$app-> get ("/product/:desurl", function($desurl){
	
	$product = new Product();

	$product-> getFromURL($desurl);

	$page = new Page();
	
	$page -> setTpl("product-detail", [
		'product'=> $product->getData(),
		'categories'=>$product->getCategories()	
	]);

	
});


?>