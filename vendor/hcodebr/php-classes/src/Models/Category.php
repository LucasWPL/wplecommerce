<?php
    namespace Wpl\Models;
    use \Wpl\DB\Sql;
    use \Wpl\Model;
    use \Wpl\Mailer;

    class Category extends Model{

        public static function listAll()
        {
            $sql = new Sql();

            return $sql-> select("SELECT * FROM tb_categories ORDER BY descategory");
        }

        public function save()
        {
    
            $sql = new Sql();
    
            $results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
                ":idcategory"=>$this->getidcategory(),
                ":descategory"=>$this->getdescategory()
            ));
    
            $this->setData($results[0]);
    
        }

        public function get($idcategory)
        {
            $sql = new Sql();
            
            $res = $sql -> select("SELECT * FROM tb_categories WHERE idcategory = :idcategory",[
                ":idcategory"=>$idcategory
            ]);

            $this-> setData($res[0]);
        }

        public function delete()
        {
            $sql = new Sql();

            $sql-> query("DELETE FROM tb_categories WHERE idcategory = :idcategory",[
                ":idcategory"=>$this-> getidcategory()
            ]);
        }

    }

?>