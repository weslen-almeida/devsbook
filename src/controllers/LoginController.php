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
        $this->render('signin', ['flash'=>$flash]);
        
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
        $flash = '';
        //verifica se tem alguma mensagem em flash
        if(!empty($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        $this->render('signup', ['flash'=>$flash]);
        
    }

    public function signupAction(){
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDADE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthdate = filter_input(INPUT_POST, 'birthdate');

        //Verifica se os dados passados foram preencidos
        if($name && $email && $password && $birthdate){
            
            //tira '/' da data de nascimento
            $birthdate = explode('/', $birthdate);
            
            //caso não tenha passado o minimo para a data de nascimento, ele redireciona para o cadastro
            if(count($birthdate) !=3){
                $_SESSION['flash'] = 'Data de Nascimento Invalida';
                $this->redirect('/cadastro');
            }

            //converto a data para o padrão americano
            $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

            //verifica se a data é valida
            if(strtotime($birthdate) === false){
                $_SESSION['flash'] = 'Data de nascimento invaldia';
                $this->redirect('/cadastro');
            }

            // caso o email não existe, faz o cadastro
            if(LoginHandler::emailExists($email) === false) {
                $token = LoginHandler::addUser($name, $email, $password, $birthdate);
                $_SESSION['token'] = $token;
                $this->redirect('/');
            }
            else{
                $_SESSION['flash'] = 'Email já Cadastrado!';
                $this->redirect('/cadastro');
            }
        }
        else{
            $this->redirect('/cadastro');
        }
    }
}