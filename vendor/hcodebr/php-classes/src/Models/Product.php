<?php
namespace Wpl\Models;
use \Wpl\DB\Sql;
use \Wpl\Model;
use \Wpl\Mailer;

class Product extends Model{

    public static function listAll()
    {
        $sql = new Sql();

        return $sql-> select("SELECT * FROM tb_products ORDER BY desproduct");

    }

    public static function checkList($list)
    {

        foreach ($list as &$row) {
            
            $p = new Product();
            $p->setData($row);
            $row = $p->getData();

        }

        return $list;

    }

    public function save()
    {

        $sql = new Sql();

        $results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, 
        :desurl)", array(
            ":idproduct"=>$this->getidproduct(),
            ":desproduct"=>$this->getdesproduct(),
            ":vlprice"=>$this->getvlprice(),
            ":vlwidth"=>$this->getvlwidth(),
            ":vlheight"=>$this->getvlheight(),
            "vllength"=>$this->getvllength(),
            ":vlweight"=>$this->getvlweight(),
            ":desurl"=>$this->getdesurl()
        ));

        $this->setData($results[0]);

    }

    public function get($idproduct)
    {
        $sql = new Sql();
        
        $res = $sql -> select("SELECT * FROM tb_products WHERE idproduct = :idproduct",[
            ":idproduct"=>$idproduct
        ]);

        $this-> setData($res[0]);
    }

    public function delete()
    {
        $sql = new Sql();

        $sql-> query(
            "SET foreign_key_checks = 0;
            DELETE FROM tb_products 
            WHERE idproduct = :idproduct;
            SET foreign_key_checks = 1;",[
            ":idproduct"=>$this-> getidproduct()
        ]);
    }


    public function checkPhoto()
    {
        if(file_exists($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.
        'res'.DIRECTORY_SEPARATOR.
        'site'.DIRECTORY_SEPARATOR.
        'img'.DIRECTORY_SEPARATOR.
        'products'.DIRECTORY_SEPARATOR.
        $this-> getidproduct().
        '.jpg'
        )){
            $url = '/res/site/img/products/'. $this-> getidproduct() .'.jpg'; 
        }else{
            $url = '/res/site/img/product.jpg';
        }

        return $this-> setdesphoto($url);
    }
    
    public function getData()
    {

        $this-> checkPhoto();
        
        $values = parent::getData();

        return $values;
    }

    public function setPhoto($file)
    {
        
        $extension = explode('.', $file['name']);
        $extension = end($extension);

        switch ($extension) {

            case "jpg":
            case "jpeg":
            $image = imagecreatefromjpeg($file["tmp_name"]);
            break;

            case "gif":
            $image = imagecreatefromgif($file["tmp_name"]);
            break;

            case "png":
            $image = imagecreatefrompng($file["tmp_name"]);
            break;

        }

        $dest = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
        "res" . DIRECTORY_SEPARATOR . 
        "site" . DIRECTORY_SEPARATOR . 
        "img" . DIRECTORY_SEPARATOR . 
        "products" . DIRECTORY_SEPARATOR . 
        $this->getidproduct() . ".jpg";

        imagejpeg($image, $dest);

        imagedestroy($image);

        $this-> checkPhoto();
    }
    
    public function getFromURL($url)
    {
     $sql = new Sql();
     
     $res = $sql-> select("SELECT * FROM tb_products WHERE desurl = :desurl",[
         'desurl'=>$url
     ]);

     $this-> setData($res[0]);
    }

    public function getCategories()
    {
        $sql = new Sql();

        return $sql -> select("
        SELECT * FROM tb_categories a INNER JOIN tb_productscategories b ON a.idcategory = b.idcategory WHERE b.idproduct = 
        :idproduct",[
            ':idproduct'=> $this-> getidproduct()
        ]);
    }

    public static function getPage($page = 1, $itemsPerPage = 10)
    {
        $start = ($page - 1) * $itemsPerPage;
    
        $sql = new Sql();

        $res = $sql -> select(
        "SELECT SQL_CALC_FOUND_ROWS *
        FROM tb_products ORDER BY desproduct
        LIMIT $start, $itemsPerPage;
        ");

        $resTotal = $sql-> select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            'data'=> $res,
            'total'=> (int)$resTotal[0]['nrtotal'],
            'pages'=> ceil($resTotal[0]['nrtotal'] / $itemsPerPage)
        ];
    }

    public static function getPageSearch($search, $page = 1, $itemsPerPage = 10)
    {
        $start = ($page - 1) * $itemsPerPage;
    
        $sql = new Sql();

        $res = $sql -> select(
        "SELECT SQL_CALC_FOUND_ROWS *
        FROM tb_products 
        WHERE desproduct LIKE :search
        ORDER BY desproduct
        LIMIT $start, $itemsPerPage;
        ",[
            ':search'=> '%'.$search.'%'
        ]); 

        $resTotal = $sql-> select("SELECT FOUND_ROWS() AS nrtotal;");

        return [
            'data'=> $res,
            'total'=> (int)$resTotal[0]['nrtotal'],
            'pages'=> ceil($resTotal[0]['nrtotal'] / $itemsPerPage)
        ];
    }

}

    
?>