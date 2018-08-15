<?php
ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);
require_once ('Secure_Control.php');
class RegAuth extends Secure_Control
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('RegAuth_model');
    }
    public function index()
    {
        $logIn = $this->LI();
        $this->load->view('/regAuth/index', $logIn);
    }
    public function page2()
    {
        $logIn = $this->LI();
        $this->load->view('/regAuth/page2', $logIn);
    }
    public function ra($a = 'e')                                        //процесс авторизации и регистрации
    {
        if ($a == 'auth') {                                             //авторизация
            $type = 'auth';
            if ($this->input->post('login') != NULL && $this->input->post('pass') != NULL) {
                $ra = $this->RegAuth_model->ra($a);
                if($ra == true){
                    header('Location: /regAuth/index', true, 303);
                }
                else if($ra == false){
                    echo "Данные введены не верно";
                }
            }
        }
        else if ($a == 'reg') {                                        //регистрация
            $type = 'reg';
            if ($this->input->post('login') != NULL && $this->input->post('pass') != NULL) {
                $ra = $this->RegAuth_model->ra($a);
                if($ra == true){
                    header('Location: /regAuth/index', true, 303);
                }
                else if($ra == false){
                    echo "Данный логин уже используется";
                }
            }
        }
        else {
                echo "Ошибка передачи данных";
                $this->index();
        }
        if($a == 'auth'|| $a == 'reg') {
            $logIn = [
                'type' => $type,
                'login' => $this->input->post('login')
            ];
            $_POST = [];
            $this->load->view('/regAuth/ra', $logIn);
        }
    }
    public function logOut()
    {
        if($this->RegAuth_model->logOut()){
            header( 'Location: /regAuth/index', true, 303 );
        }
    }
}