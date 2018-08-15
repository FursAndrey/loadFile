<?
ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);
class Secure_Control extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }
    public function index(){

	}
	protected function LI(){
        $auth = 0;
        if($this->session->userdata('id') != NULL){
            $auth = 1;
            $this->db->where('id', $this->session->id);
            $query = $this->db->get('users');
        }
        $logIn = [
            'auth' => $auth
        ];
        if($auth){
            $login = $query -> result_array()[0]['login'];
            $logIn['login'] = $login;
        }
        return $logIn;
    }
}