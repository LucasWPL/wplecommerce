<?php

use \Wpl\PageAdmin;
use \Wpl\Models\User;
use \Wpl\Models\Product;

$app-> get ("/admin/products", function(){
    
    User::verifyLogin();

    $products = new Product();

    $search = (isset($_GET['search'])) ? $_GET['search'] : '';
	$numpage = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
	
	if($search != ''){

		$pagination = Product::getPageSearch($search, $numpage);


	}else{

		$pagination = Product::getPage($numpage);

	}

	$pages = [];

	for($x = 0; $x < $pagination['pages']; $x++){
		array_push($pages,[
			'href'=> '/admin/products?'.http_build_query([
				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1
		]);
	}

	$page = new PageAdmin();

	$page -> setTpl("products",[
		"products"=>$pagination['data'],
		'search'=>$search,
		'pages'=>$pages
	]);
});

$app-> get("/admin/products/create",function(){
    
    User::verifyLogin();

    $product = new Product();

    $page = new PageAdmin();
    $page -> setTpl("products-create");
});

$app-> post("/admin/products/create",function(){
    
    User::verifyLogin();

    $product = new Product();
    $product -> setData($_POST);

    $product -> save();

    header("Location: /admin/products");
    exit;
});

$app-> get("/admin/products/:idproduct",function($idproduct){
    
    User::verifyLogin();

    $product = new Product();

    $product -> get((int)$idproduct);

    $page = new PageAdmin();
    $page -> setTpl("products-update",[
        "product"=>$product->getData()
    ]);
});

$app-> post("/admin/products/:idproduct",function($idproduct){
    
    User::verifyLogin();

    $product = new Product();

    $product -> get((int)$idproduct);

    $product-> setData($_POST);

    $product -> setPhoto($_FILES['file']);

    $product -> save();

    

    header("Location: /admin/products");
    exit;
});


$app-> get("/admin/products/:idproduct/delete",function($idproduct){
    
    User::verifyLogin();

    $product = new Product();

    $product -> get((int)$idproduct);

    $product -> delete();

    header("Location: /admin/products");
    exit;
});

?>