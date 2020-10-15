<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;

class LoginController extends Controller {

    public function signin() {
        $flash = '';

        //verifica se tem alguma mensagem em flash
        if(!empty($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('login', ['flash'=>$flash]);
        
    }

    public function signinAction(){
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDADE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        //verifica se o login e senha foram digitados
        if($email && $password){
            
            //Verifica se os dados digitados estão cadastrados e corretos
            //verificação através do handlers
            $token = LoginHandler::verifyLogin($email, $password);

            if($token){
                $_SESSION['token'] = $token;
                $this->redirect('/');
            }
            else{
                //mensagem de erro caso os campos não estiverem corretos
                $_SESSION['flash'] = 'Email e/ou senha não conferem';
                $this->redirect('/login');
            }
        }
        else{
            $_SESSION['flash'] = 'Digite os campos de e-mail e/ou senha,';
            $this->redirect('/login');
        }
    }

    public function signup() {
        echo 'cadastro';
    }
}