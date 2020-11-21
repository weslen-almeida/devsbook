<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;

class HomeController extends Controller {

    //Guarda o usuario logado
    private $loggedUser;

    // primeiro metodo que vai ser executado
    public function __construct(){
       
        //pega os dados do usuario
        $this->loggedUser = LoginHandler::checkLogin();
        
        //verifica se o usuario esta logado, caso não esteja vai para tela de login
       if($this->loggedUser === false){
           $this->redirect('/login');
       }
    }

    public function index() {
        $this->render('home', [
            'loggedUser' => $this->loggedUser
        ]);
    }
}