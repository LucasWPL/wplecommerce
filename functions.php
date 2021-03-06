<?php
use \Wpl\Models\User;
use \Wpl\Models\Cart;

    function checkLogin ($inadmin = true){
    
        return User::checkLogin($inadmin);
    
    }

    function getUserName()
    {

        $user = User::getFromSession();

        return $user->getdesperson();

    }

    function formatPrice( $vlprice)
    {
        
        if (!$vlprice > 0) $vlprice = 0;
        
        return number_format($vlprice, 2, ",", ".");
    }

    function getCartNrQtd(){

        $cart =  Cart::getFromSession();
        $totals = $cart-> getProductsTotals();

        return $totals['nrqtd'];
    }
    
    function getCartVlSubtotal(){
    
        $cart =  Cart::getFromSession();
        $totals = $cart-> getProductsTotals();

        return formatPrice($totals['vlprice']);
    }

    function formatDate($date)
    {
        return date("d/m/Y", strtotime($date));
    }
?>