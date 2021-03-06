<?php
use \Wpl\PageAdmin;
use \Wpl\Models\User;
use \Wpl\Models\Order;
use \Wpl\Models\OrderStatus;

$app-> get("/admin/orders/:idorder/delete",function($idorder){
    
    User::verifyLogin();

    $order = new Order();
    $order -> get((int)$idorder);
    $order-> delete();
    
    header("Location: /admin/orders");
    exit;    

});

$app-> get("/admin/orders/:idorder/status", function($idorder){
    User::verifyLogin();

    $order = new Order();
    $order -> get((int)$idorder);

    $page = new PageAdmin();

    $page-> setTpl("order-status",[
        'order'=>$order->getData(),
        'status'=>OrderStatus::listAll(),
        'msgError'=>Order::getError(),
        'msgSuccess'=>Order::getSuccess()
    ]);
});

$app-> post("/admin/orders/:idorder/status", function($idorder){
    User::verifyLogin();

    if(!isset($_POST['idstatus']) || !(int)$_POST['idstatus']){
        Order::setError('Informe o status atual.');
        header("Location: /admin/orders/".$idorder."/status");
        exit;
    }
    $order = new Order();
    $order -> get((int)$idorder);

    $order-> setidstatus((int)$_POST['idstatus']);

    $order-> save();
    
    Order::setSuccess('Status alterado com sucesso.');
    header("Location: /admin/orders/".$idorder."/status");
    exit;
});

$app-> get("/admin/orders/:idorder",function($idorder){
    
    User::verifyLogin();

    $order = new Order();
    
    $order -> get((int)$idorder);
   
    $cart = $order->getCart();
    $cart -> getCalculateTotal();
    // var_dump($cart);
    // exit;
    $page = new PageAdmin();

    $page -> setTpl('order',[
        'order'=> $order-> getData(),
        'cart'=> $cart-> getData(),
        'products'=> $cart-> getProducts()
    ]);
});

$app-> get("/admin/orders",function(){
    
    User::verifyLogin();

    $order = new Order();

    $search = (isset($_GET['search'])) ? $_GET['search'] : '';
	$numpage = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
	
	if($search != ''){

		$pagination = Order::getPageSearch($search, $numpage);


	}else{

		$pagination = Order::getPage($numpage);

	}

	$pages = [];

	for($x = 0; $x < $pagination['pages']; $x++){
		array_push($pages,[
			'href'=> '/admin/orders?'.http_build_query([
				'page'=>$x+1,
				'search'=>$search
			]),
			'text'=>$x+1
		]);
	}

	$page = new PageAdmin();

	$page -> setTpl("orders",[
		"orders"=>$pagination['data'],
		'search'=>$search,
		'pages'=>$pages
	]);
});





?>