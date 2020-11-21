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
                $loggedUser->name = $data['name'];
                $loggedUser->avatar = $data['avatar'];

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

    //Verifica se o email existe
    public function emailExists($email){
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }

    //adicionar usuario no banco
    public function addUser($name, $email, $password, $birthdate){
        //encripta a senha
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0,9999).time());

        User::insert([
            'name' => $name,
            'email' => $email,
            'password' => $hash,
            'birthdate' => $birthdate,
            'token' => $token
        ])->execute();

        return $token;
    }
}
