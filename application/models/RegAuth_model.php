<?php
ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);
class RegAuth_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    public function ra($a = 'e', $login, $pass)                                        //процесс авторизации и регистрации
    {
        if ($login != NULL && $pass != NULL) {        //логин и пароль введены
            $this->db->where('login', $login);
            $query = $this->db->get('users');
            if ($a == 'auth') {                                             //авторизация
                if (!empty($query->result_array()[0])) {                //если логин существует в базе
                    $pass = md5($pass);                        //хеш пароля
                                                                //если пароль пользователя совпадает с паролем из базы - авторизация
                    if ($query->result_array()[0]['login'] == $login && $query->result_array()[0]['password'] == $pass) {
                        $data = [
                            'id' => $query->result_array()[0]['id']
                        ];
                        $this->session->set_userdata($data);
                        return true;
                    }                                                   //если нет - ошибка
                    if ($query->result_array()[0]['login'] != $login || $query->result_array()[0]['password'] != $pass) {
                        return false;
                    }
                } else {                                                //если логин в базе не найден - ошибка
                    return false;
                }
            } else if ($a == 'reg') {                                        //регистрация
                if (!empty($query->result_array()[0])) {                    //если в базе есть такой логин - ошибка
                    return false;
                } else {                                                    //если логина в базе нет - продолжить регистрацию
                    $pass = md5($pass);                //хеш пароля
                    $mas = [
                        'login' => $login,
                        'password' => $pass
                    ];
                    $query = $this->db->insert('users', $mas);              //запрос на добавление данных в базу
                    if (!empty($query)) {
                        if ($query == 1) {                                   //если данные добавлены
                            if (!empty($this->db->insert_id())) {           //сохранить ИД пользователя и авториовать
                                $data = [
                                    'id' => $this->db->insert_id()
                                ];
                                $this->session->set_userdata($data);
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }
}