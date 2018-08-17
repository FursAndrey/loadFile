<?php
ini_set('display_errors', 1);	//1 - показывать ошибки, 0 - скрывать
error_reporting(E_ALL);
require_once ('Secure_Control.php');
class File extends Secure_Control
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('File_model');
        $this->load->library('session');
    }
    public function index(){
        $this->load->view('/file/fileIns');
    }
    public function fileIns(){
        $logIn = $this->LI();
        if($logIn['auth']){
            if($_FILES != []){
                $fileName = md5($_FILES['file']['name'] . time());						//назначение уникального имени
                $endNameFile = explode('.', $_FILES['file']['name']);				//получение расширения
                $structure = 'load/' . $fileName[0] . '/';									//подготовка директории для записи файла
                if(!file_exists($structure)){												//проверить наличие папки, если нету - создать
                    mkdir($structure, 0777, true);							//создание папки
                }
                $endNameFile = end($endNameFile);
//                $uploadfile = $structure . $fileName . '.' . $endNameFile;		//получение полного адреса (с именем и расширением)
                $uploadfile = $structure . $fileName;		//получение полного адреса (с именем и расширением)
                if($_FILES["file"]["error"] == 0){											//если файл получен без ошибок
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {		//записать по указанному адресу
                                                                                            //вставка файла
                        $rez = $this -> File_model -> insertFile($fileName, $_FILES['file']['name'], $_FILES['file']['size'], $this->session->userdata('id'), $endNameFile);
                        if($rez){                                                           //если успешно ...
                            echo '<p>Файл успешно загружен</p><p><a href="/">Ссылка на главную страницу</a></p>';
                        }
                    }
                }
                else {
                    echo '<p>Файл не загружен. Ошибка ' . $_FILES["file"]["error"] . '</p>';//если возникла ошибка, вывести код ошибки
                    echo '<p><a href="/regAuth/page2">Ссылка на 2-ю страницу</a></p>';
                }
            }
        }
        else{
            $data = [
                'auth' => 0
            ];
            $this->load->view('/regAuth/index', $data);
        }
    }
    public function fileList(){
        $logIn = $this->LI();
        if($logIn['auth']){
            $rez = $this -> File_model -> showFile($this->session->userdata('id'));
            $this->load->view('/file/fileList', $rez);
        }
        else{
            $data = [
                'auth' => 0
            ];
            $this->load->view('/regAuth/index', $data);
        }
    }
    public function fileDel($fileID){
        if(!empty($fileID)){
            $logIn = $this->LI();
            if($logIn['auth']){
                $this->File_model->delFile($fileID,$this->session->userdata('id'));
                $this->fileList();
            }
            else{
                $data = [
                    'auth' => 0
                ];
                $this->load->view('/regAuth/index', $data);
            }
        }
    }
    public function load(){
        $filename1 = $this->input->get('filename');
        $end = $this->input->get('end');
        $filename = 'load/' . $filename1[0] . '/' . $filename1;
        $filename2 = $filename . '.' . $end;
//        $filename = 'load/' . $filename1[0] . '/' . $filename1 . '.' . $end;

        // нужен для Internet Explorer, иначе Content-Disposition игнорируется
        if(ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');
        $file_extension = strtolower(substr(strrchr($filename,"."),1));
        if( $filename == "" )
        {
            echo "ОШИБКА: не указано имя файла.";
            exit;
        } elseif ( ! file_exists( $filename ) ) // проверяем существует ли указанный файл
        {
            echo "ОШИБКА: данного файла не существует.";
            exit;
        };
        switch( $file_extension )
        {
            case "pdf": $ctype="application/pdf"; break;
            case "exe": $ctype="application/octet-stream"; break;
            case "zip": $ctype="application/zip"; break;
            case "doc": $ctype="application/msword"; break;
            case "xls": $ctype="application/vnd.ms-excel"; break;
            case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
            case "mp3": $ctype="audio/mp3"; break;
            case "gif": $ctype="image/gif"; break;
            case "png": $ctype="image/png"; break;
            case "jpeg":
            case "jpg": $ctype="image/jpg"; break;
            case "php": $ctype="text/php"; break;
            default: $ctype="application/force-download";
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false); // нужен для некоторых браузеров
        header("Content-Type: $ctype");
        header("Content-Disposition: attachment; filename=\"".basename($filename2)."\";" );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($filename2)); // необходимо доделать подсчет размера файла по абсолютному пути
        readfile("$filename2");
    }
}