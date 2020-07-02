<?php
namespace Wpl\Models;
use \Wpl\DB\Sql;
use \Wpl\Model;
use \Wpl\Models\Cart;

class Order extends Model
{
    
    const SUCCESS = 'SuccessOrder';
    const ERROR = 'ErrorOrder';
    
    public function save()
    {
        $sql = new Sql();

        $res = $sql-> select("CALL sp_orders_save(:idorder, :idcart, :iduser, :idstatus, :idaddress, :vltotal)", [
            ':idorder'=> $this->getidorder(),   
            ':idcart'=> $this->getidcart(),   
            ':iduser'=> $this->getiduser(),   
            ':idstatus'=> $this->getidstatus(),   
            ':idaddress'=> $this->getidaddress(),   
            ':vltotal'=> $this->getvltotal()
        ]);
            
        if(count($res) > 0){
            $this->setData($res[0]);
        }

    }

    public function get($idorder)
    {
        $sql = new Sql();

        $res = $sql-> select(
        "SELECT * FROM tb_orders a 
        INNER JOIN tb_ordersstatus b USING(idstatus) 
        INNER JOIN tb_carts c USING(idcart)
        INNER JOIN tb_users d ON d.iduser = a.iduser
        INNER JOIN tb_addresses USING(idaddress)
        INNER JOIN tb_persons f ON f.idperson = d.idperson
        WHERE a.idorder = :idorder",[
            ':idorder'=> $idorder
        ]);
        
        if(count($res) > 0){
            $this-> setData($res[0]);
        }
    }

    public static function listAll()
    {
        $sql = new Sql();

        $res = $sql-> select(
            "SELECT * FROM tb_orders a 
            INNER JOIN tb_ordersstatus b USING(idstatus) 
            INNER JOIN tb_carts c USING(idcart)
            INNER JOIN tb_users d ON d.iduser = a.iduser
            INNER JOIN tb_addresses USING(idaddress)
            INNER JOIN tb_persons f ON f.idperson = d.idperson
            ORDER BY a.dtregister DESC
        ");
        return $res;                
    }

    public function delete()
    {
        $sql = new Sql();

        $res = $sql-> query(
            "DELETE FROM tb_orders WHERE idorder = :idorder", [
            ':idorder'=>$this-> getidorder()
        ]);
    }

    public function getCart():Cart
    {
        $cart = new Cart();
        $cart->get((int)$this->getidcart());

        return $cart;
    }

    public static function setError($msg)
	{

		$_SESSION[Order::ERROR] = $msg;

	}

	public static function getError()
	{

		$msg = (isset($_SESSION[Order::ERROR]) && $_SESSION[Order::ERROR]) ? $_SESSION[Order::ERROR] : '';

		Order::clearError();

		return $msg;

	}

	public static function clearError()
	{

		$_SESSION[Order::ERROR] = NULL;

	}

	public static function setSuccess($msg)
	{

		$_SESSION[Order::SUCCESS] = $msg;

	}

	public static function getSuccess()
	{

		$msg = (isset($_SESSION[Order::SUCCESS]) && $_SESSION[Order::SUCCESS]) ? $_SESSION[Order::SUCCESS] : '';

		Order::clearSuccess();

		return $msg;

	}

	public static function clearSuccess()
	{

		$_SESSION[Order::SUCCESS] = NULL;

    }
    
}

   
?>