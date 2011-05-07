<?php
class QqUploadedFileXhr {
    function save($path) {   
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        };
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    
    function getName() {
        return $_GET['qqfile'];
    }
    
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }
}

class QqUploadedFileForm {  
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        };
        
        return true;
    }
    
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class Save {
    private $_config = null;
    
	private $allSize;
	private $sizeLimit;
	
    private $allowedExtensions = array();
    private $file;
    
    private $countAllSize = 0;
    
    private $abspDir = null;
    private $abs_thumbDir = null;
	
    function __construct($config) {
        $this->_config = $config;

        $this->sizeLimit = $config["sizeLimit"];
        $this->allSize = $config["allSize"];

		$allowedExtensions = $config['allowedExtensions'];
		
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        

        if (isset($_GET['qqfile'])) {
            $this->file = new QqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new QqUploadedFileForm();
        } else {
            $this->file = false; 
        }

        session_start();
        
        $this->abspDir = $config["root"] . $config["upload"] . "/";
        $this->abs_thumbDir = $config["root"] . $config["upload"] . "/_thumb/";
    }
    
    function handleUpload($uploadDirectory, $_thumbPath, $replaceOldFile = FALSE) {		 
        if (!is_writable($uploadDirectory)){
            return array('error' => "Ошибка сервера. Запись в директорию невозможен!");
        }
        
        if (!$this->file){
            return array('error' => 'Нет файлов для загрузки');
        }
        
        $size = $this->file->getSize();
        
        if (!$this->countAllSize) {
            $this->getDirSize($this->abspDir); 
        };
               
        if ($this->countAllSize >= $this->allSize) {
            exit();
        } else {
            $this->countAllSize += $size;
        }
        
        if ($size == 0) {
            return array('error' => 'Пустой файл или директория');
        }
        
        if ($size > $this->sizeLimit) {
			if (($this->sizeLimit / 1024) > 1) {
				$tsize = round($this->sizeLimit / 1024, 2) . " Кб";
			} else {
				$tsize = round($this->sizeLimit, 2) . " Б";
			};
			
			if (($tsize / 1024) > 1) {
				$tsize = round($tsize / 1024, 2) . " Мб";
			};
			
            return array('error' => 'Файл слишком большой! Для вас установлен лимит на максимальный размер загружаемого файла: ' . $tsize);
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];

        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'Расширение файла запрещено, разрешены: '. $these . '.');
        }
        
        if(!$replaceOldFile){
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                //$filename .= rand(10, 99);
                return array('error' => 'Имя файла совпадает с уже существующим');
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)) {
            if ( (strtolower($ext) == "gif") or (strtolower($ext) == "png") or (strtolower($ext) == "jpg") or (strtolower($ext) == "jpeg") ) {
                $thumb = new Thumb($this->_config);
                $thumb->img_resize($uploadDirectory . $filename . '.' . $ext, $_thumbPath . $filename . '.' . $ext, 150, 120);
            };
            
            return array('success'=>true);
        } else {
            return array('error'=> 'Не получается сохранить файл.' .
                'Загрузка отменена, ошибка сервера');
        }
        
    }
    
    private function getDirSize($dir) {
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (($file!='.') && ($file!='..')) {
                        $f = $dir . $file;
                        if (filetype($f)=='dir') {
                            $this->getDirSize($dir . $file . "/");
                        }
                        if (filetype($f)!='dir') {
                            $this->countAllSize = $this->countAllSize + filesize($f);
                        }
                    }
                }
                
                closedir($dh);
            }
        }
    }

	public function index() {
	    $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
		$sPath = $this->abspDir . $curdir;
        $_thumbPath = $this->abs_thumbDir . $curdir;
		
		$result = $this->handleUpload($sPath, $_thumbPath);

		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
}
?>