<?php
namespace src\handlers;

use \src\models\User;

//classe para verificação de login
class LoginHandler {

    public static function checkLogin(){
        //verifica se tem a sessão token
        if(!empty($_SESSION['token'])){
            $token = $_SESSION['token'];

            $data = User::select()->where('token', $token)->one();
            
            //verifica se teve algum retorno de usuario
            if(count($data) > 0){

                $loggedUser = new User();

                $loggedUser->id = $data['id'];
                $loggedUser->email = $data['email'];
                $loggedUser->name = $data['name'];

                return $loggedUser;
            }
        }
        return false;
    }

    public static function verifyLogin($email, $password) {
        
        //verifica o email
        $user = User::select()->where('email', $email)->one();

        if($user){
            //verificar a senha
            if(password_verify($password, $user('password'))){
                //gerar um token para armazenar a sessão
                $token = md5(time().rand(0,9999).time());

                User::update()->set('token', $token)->where('email', $email)->execute();
                return $token;
            }
        }



    }
}
