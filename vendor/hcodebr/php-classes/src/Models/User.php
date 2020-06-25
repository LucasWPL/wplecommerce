<?php
    namespace Wpl\Models;
    use \Wpl\DB\Sql;
    use \Wpl\Model;

    class User extends Model{

        const SESSION = "User";

        public static function login($login, $password)
        {
            $sql = new Sql();

            $res = $sql-> select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
                ":LOGIN" => $login
            ));

            if (count($res) === 0) throw new \Exception("Usuário inexistente ou senha inválida.", 44);
            
            $data = $res[0];

            if (password_verify($password, $data["despassword"]) === true){
                
                $user = new User();
                $user-> setData($data);

                $_SESSION[User::SESSION] = $user-> getData();

                return $user;


            }else {
                throw new \Exception("Usuário inexistente ou senha inválida.", 44);
            }

        }

        public static function verifyLogin($inadmin = true)
        {
            if (
                !isset($_SESSION[User::SESSION])
                ||
                !$_SESSION[User::SESSION]
                ||
                !(int)$_SESSION[User::SESSION] ["iduser"] > 0
                ||
                (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin

            ) {
                header("Location: /admin/login");
                exit;
            }
        }

        public static function logout()
        {
            $_SESSION[User::SESSION] = NULL;
        }
    }

?>