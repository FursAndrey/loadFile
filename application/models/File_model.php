<?php
ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);
class File_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }
    public function insertFile($adres,$name,$size){
        $data = array(
            'adres' => $adres,
            'name' => $name,
            'size' => $size,
            'userID' => $this->session->userdata('id')
        );
        return $this->db->insert('file', $data);
    }
    public function showFile(){
        $this->db->where('id', $this->session->userdata('id'));                //получение данных пользователя
        $this->db->select('login');
        $query = $this->db->get('users');
        $rez['login'] = $query->result_array()[0]['login'];
        $this->db->select('file.*,users.login');
        $this->db->from('file');
        $this->db->join('users', 'users.id = file.userID');
        $query = $this->db->get();
        $mas = [];
        foreach ($query->result() as $row)                      //подготовка списка файлов к выводу
        {
            $mas[] = $row;
        }
        $rez['file'] = $mas;
        return $rez;
    }
    public function delFile($fileID)
    {
        $this->db->where('id', $fileID);                    //получение файлов пользователя
        $query = $this->db->get('file');
        $mas = $query->result_array();
        if (empty($mas[0])) {                              //проверка: получен ли файл?
			return false;
		}
        if ($mas[0]['userID'] != $this->session->userdata('id')) {             //проверка: получен файл данного пользователя?
			return false;
		}
        $this->db->where('id', $fileID);
        $query = $this->db->delete('file');         //запрос на удаление
        if ($query != 1) {                          //проверка: выполнено ли удаление?
            return false;
		}
		else{
            $adr = $mas[0]['adres'];
            unlink($adr);
            return true;
        }
    }
}