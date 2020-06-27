<?php
    namespace Wpl\Models;
    use \Wpl\DB\Sql;
    use \Wpl\Model;
    use \Wpl\Mailer;

    class User extends Model{

        const SESSION = "User";
        const SECRET = "PEDRO_LUCAS_0323";
        const SECRET_IV = "PEDRO_LUCAS_0323_IV";

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

        public static function listAll()
        {
            $sql = new Sql();

            return $sql-> select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
        }

        public function save()
        {
    
            $sql = new Sql();
    
            $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
                ":desperson"=>utf8_decode($this->getdesperson()),
                ":deslogin"=>$this->getdeslogin(),
                ":despassword"=>$this->getdespassword(),
                ":desemail"=>$this->getdesemail(),
                ":nrphone"=>$this->getnrphone(),
                ":inadmin"=>$this->getinadmin()
            ));
    
            $this->setData($results[0]);
    
        }


        public function get($iduser)
        {
    
            $sql = new Sql();
    
            $results = $sql-> select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
                ":iduser"=>$iduser
            ));

            if(count($results)===0) throw new \Exception("Erro no envio dos dados.");
            
            $data = $results[0];
    
            $data["desperson"] = utf8_encode($data["desperson"]);
    
    
            $this->setData($data);
    
        }

        public function update()
        {
            $sql = new Sql();

            $res = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)",array(
                ":iduser"=>$this->getiduser(),
                ":desperson"=>$this->getdesperson(),
                ":deslogin"=>$this->getdeslogin(),
                ":despassword"=>$this->getdespassword(),
                ":desemail"=>$this->getdesemail(),
                ":nrphone"=>$this->getnrphone(),
                ":inadmin"=>$this->getinadmin()
            ));

            $this-> setData($res[0]);
        }

        public function delete()
        {
            $sql = new Sql();

            $sql -> query("CALL sp_users_delete(:iduser)",array(
                ":iduser"=>$this->getiduser()
            ));
        }

        public static function getForgot($email)
        {
            $sql = new Sql();

            $res = $sql->select(
            "SELECT *
			FROM tb_persons a
			INNER JOIN tb_users b USING(idperson)
			WHERE a.desemail = :email;
		    ", array(
			":email"=>$email
		    ));
            
            if (count($res) === 0)
            {
    
                throw new \Exception("Houve um problema ao recuperar o sua senha.");
                
            }else{

                $data = $res[0];
                $res2 = $sql -> select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)",array(
                    ":iduser"=>$data["iduser"],
                    ":desip"=> $_SERVER["REMOTE_ADDR"]

                ));
    
                if (count($res2) === 0) {

                    throw new Exception("Houve um problema ao recuperar o sua senha.");

                }else{

                    $dataRecovery = $res2[0];

                    $code = openssl_encrypt($dataRecovery['idrecovery'], 'AES-128-CBC', 
                    pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

                    $code = base64_encode($code);

                    $link = "http:www.wplstore.com.br/admin/forgot/reset?code=$code";
                    
                    $mailer = new Mailer($data["desemail"], $data["desperson"], "Recuperacao de senha WPL Store", "forgot", array(
                        "name"=>$data["desperson"],
                        "link"=>$link
                    ));

                    $mailer->send();

                    return $link;
                }
            }
            
        }

        public static function validForgot($code)
        {
            
            $code = base64_decode($code);

            $idrecovery = openssl_decrypt($code, 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

            $sql = new Sql();
            $res = $sql->select(
            "SELECT *
			FROM tb_userspasswordsrecoveries a
			INNER JOIN tb_users b USING(iduser)
			INNER JOIN tb_persons c USING(idperson)
			WHERE
				a.idrecovery = :idrecovery
				AND
				a.dtrecovery IS NULL
				AND
				DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();
            ", array(
                ":idrecovery"=>$idrecovery
            ));

            if (count($res) === 0)
            {
                throw new \Exception("Não foi possível recuperar a senha.");
            }
            else
            {

                return $res[0];

            }
        }

        public static function setForgotUsed($idrecovery)
        {
            
            $sql = new Sql();

            $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
                ":idrecovery"=>$idrecovery
            ));

        }

        public function setPassword($password)
        {
    
            $sql = new Sql();
    
            $sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
                ":password"=>$password,
                ":iduser"=>$this->getiduser()
            ));
    
        }

        public static function getPasswordHash($password)
        {
            password_hash($password, PASSWORD_DEFAULT, [
                "cost"=>12
            ]);
        }

    }

?>