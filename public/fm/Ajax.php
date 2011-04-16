<?php
class Ajax extends Main {    
    private $_config = null;
    
	private $allSize;
	private $sizeLimit;
    
    private $twig;

    private $subdirs = array();
    
    private $relpDir = null;
    private $abspDir = null;
    private $rel_thumbDir = null;
    private $abs_thumbDir = null;
    
    private $count = 0;

	public function __construct($config) {
        $this->_config = $config;
        
        $this->sizeLimit = $config["sizeLimit"];
        $this->allSize = $config["allSize"];
        
        $loader = new Twig_Loader_Filesystem($config['path']['templates']);
        $templates = new Twig_Environment($loader, array(
        	//'cache' => $config['path']["cache"],
            'cache' => FALSE
        ));
        
        $this->twig = $templates;
        
        session_start();
        
        $this->relpDir = $config["upload"];
        $this->abspDir = $config["root"] . $config["upload"] . "/";
        $this->rel_thumbDir = $config["upload"] . "/_thumb/";
        $this->abs_thumbDir = $config["root"] . $config["upload"] . "/_thumb/";
	}
    
	public function render($name, $params) {
		$template = $this->twig->loadTemplate($this->getTemplate($name));

		if (isset($params)) {
			$content = $template->render($params);
		} else {
			$content = $template->render(array());
		};

		return $content;
	}
    
    private function getSubDirs() {
        $this->subdirs[0]["name"] = "/";
        $this->subdirs[0]["path"] = $this->abspDir;
        
        $this->get_directory_list($this->abspDir, $this->abspDir);
    }
    
	public function files() {
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        $dirs = array();
        
        if ($curdir == null) {
            $shPath = "/";
            $dir = $this->relpDir;
            $path = $this->abspDir;
            $_thumb = $this->rel_thumbDir;
            
            $k = 0;
        } else {
            if (!is_dir($this->abspDir . $curdir)) {
                $shPath = "/";
                
                $dir = $this->relpDir;
                $path = $this->abspDir;
                $_thumb = $this->rel_thumbDir;
                
                $curdir = null;

                $fm["dir"] = null;
                
                $k = 0;
            } else {            
                $shPath = "/" . $curdir;
                $dir = $this->relpDir . $curdir;
                $path = $this->abspDir . $curdir;
                $_thumb = $this->rel_thumbDir . $curdir;
    
                $dirs[0]["name"] = "..";
                $k = 1;
            }
        };

        $this->getDirSize($this->abspDir);

        $this->getSubDirs();

		$files = ""; $i = 0; $total = 0;
        
		if ($dh  = opendir($path)) {
    		while (false !== ($filename = readdir($dh))) {
    			if ( ($filename != ".") and ($filename != "..") and ($filename != "_thumb") ) {
                    if (is_file($path . $filename)) {
                        $files[$i]["name"] = $filename;
                        
                        $files[$i]["ext"] = strtolower(substr($filename, strrpos($filename, ".")+1, strlen($filename)-strrpos($filename, ".")-1));
    
        				$size = filesize($path . $filename);
        	
        				$files[$i]["simplesize"] = $size;
                        $files[$i]["date"] = date("d F H:i", fileatime($path . $filename));
        				
        				$total += $size;
        				if (($size / 1024) > 1) { $size = round($size / 1024, 2) . "&nbsp;Кб"; } else { $size = round($size, 2) . "&nbsp;Б"; };
        				if (($size / 1024) > 1) { $size = round($size / 1024, 2) . "&nbsp;Мб"; };
        				$files[$i]["size"] = $size;
        				
        				$i++;
                    } else if (is_dir($path . $filename)) {
                        $dirs[$k]["name"] = $filename;
                        $dirs[$k]["date"] = date("d F H:i", fileatime($path . $filename));
        				
        				$k++;
                    }
    			}
            }
        }
       
		for ($i=0; $i<count($files); $i++) {
			for ($k=count($files)-1; $k > $i; $k--) {

				if (strtolower($files[$k-1]["name"]) > strtolower($files[$k]["name"])) {
					$min = $files[$k-1];
					$files[$k-1] = $files[$k];
					$files[$k] = $min;
				};
			}
		};
        
		for ($i=0; $i<count($dirs); $i++) {
			for ($k=count($dirs)-1; $k > $i; $k--) {
        		if (strtolower($dirs[$k-1]["name"]) > strtolower($dirs[$k]["name"])) {
					$min = $dirs[$k-1];
					$dirs[$k-1] = $dirs[$k];
					$dirs[$k] = $min;
				}
			}
		};
        
        $drop = '';
        foreach($this->_config["drop"] as $part) {
            $drop .= $this->render("dropjHtmlArea", array("obj" => $part, "dir" => $_thumb, "path" => $dir));
        };
        
        if (($total / 1024) > 1) { $total = round($total / 1024, 2) . "&nbsp;Кб"; } else { $total = round($total, 2) . "&nbsp;Б"; };
    	if (($total / 1024) > 1) { $total = round($total / 1024, 2) . "&nbsp;Мб"; };
        
        echo $this->render("content", array("path" => $path, "shPath" => $shPath, "dirs" => $dirs, "_thumb" => $_thumb, "files" => $files, "totalsize" => $total, "javascript" => $drop, "url" => $this->_config["url"], "subdirs" => $this->subdirs, "allSize" => round($this->allSize/1024/1024,2)));
	}

	public function delfile($params) {
	    $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
		$fname = $params["fname"];
			
		$path = $this->abspDir . $curdir . $fname;

		unlink($path);
        if (file_exists($this->abs_thumbDir . $curdir . $fname)) {
            $path = $this->abs_thumbDir . $curdir . $fname;
    		unlink($path);
        }		
	}
   
	public function getTotalSize() {
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
		$totalSize = 0;
		$sPath = $this->abspDir . $curdir;
		$dDir = opendir($sPath);
		
		while ($sFileName=readdir($dDir)) {
			if ( ($sFileName != '.') and ($sFileName != '..') and ($sFileName != "_thumb") ) {
			    if (is_file($sPath . $sFileName)) {
				   $totalSize += @filesize($sPath . $sFileName);
                }
			}
		}
		closedir ($dDir);
		
        if (($totalSize / 1024) > 1) { $totalSize = round($totalSize / 1024, 2) . "&nbsp;Кб"; } else { $totalSize = round($totalSize, 2) . "&nbsp;Б"; };
		if (($totalSize / 1024) > 1) { $totalSize = round($totalSize / 1024, 2) . "&nbsp;Мб"; };
		
		echo $totalSize;
	}
    
    public function chdir($params) {
        $dir = $params["dir"];
        
        $fm = & $_SESSION["fm"];
                
        if ($dir == "..") {
            $dir = $fm["dir"];
            $dir = substr($dir, 0, strlen($dir)-1);
            $dir = substr($dir, 0, strrpos($dir, "/"));
            if ($dir == null) {
                $fm["dir"] = $dir;
            } else {
                $fm["dir"] = $dir . "/";
            }
        } else {
            if (isset($fm["dir"])) {
                if ($fm["dir"] != null) {
                    $fm["dir"] .= $dir . "/";
                } else {
                    $fm["dir"] = $dir . "/";
                }                
            } else {
                $fm["dir"] = $dir . "/";
            }
        };
        
        $this->files();
    }
    
    public function createDir($params) {
        $dirName = $params["dirName"];
        
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
 
        @mkdir($this->abspDir . $curdir . $dirName);
        @mkdir($this->abspDir . "/_thumb/" . $curdir . $dirName);

        $this->files();  
    }
    
    public function rmDir($params) {
        $dirName = $params["dirName"];
        
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
        if (strpos($dirName, "/") == false) {
            $this->removeDirRec($this->abspDir . $curdir . $dirName);
 
            if (is_dir($this->abs_thumbDir . $curdir . $dirName)) {
                $this->removeDirRec($this->abs_thumbDir . $curdir . $dirName);
            }
        }

        $this->files();
    }
    
    private function removeDirRec($dir) {
        if ($objs = glob($dir."/*")) {
            foreach($objs as $obj) {
                if (!is_dir($obj)) $this->count += filesize($obj);
        
                is_dir($obj) ? $this->removeDirRec($obj) : unlink($obj);                
            }
        };

        rmdir($dir);
    }
    
    private function get_directory_list($path, $pDir) {
        if(is_dir($path)) {
            $dh = opendir($path);
            while (false !== ($dir = readdir($dh))) {
                if (is_dir($path . $dir) && $dir !== '.' && $dir !== '..' && $dir !== '_thumb') {
                    $subdir = $path . $dir . '/';
                    
                    $sizeArr = count($this->subdirs);
                    $this->subdirs[$sizeArr]["name"] = substr($subdir, strlen($pDir)-1, strlen($subdir)-strlen($pDir)+1);
                    $this->subdirs[$sizeArr]["path"] = $subdir;
                    
                    $this->get_directory_list($subdir, $pDir);
                }
            }
            closedir($dh);
        }
    }
    
    public function moveFiles($params) {
        $this->getSubDirs();
        
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
        $dirName = $params["dirName"];
        foreach($this->subdirs as $part) {
            if ($part["name"] == $dirName) {
                $path = $part["path"];
            }
        };
        
        foreach($params["file"] as $part) {
            rename($this->abspDir . $curdir . $part, $path . $part);
            $pDir = substr($path, strlen($this->abspDir), strlen($path)-strlen($this->abspDir));
            rename($this->abs_thumbDir . $curdir . $part, $this->abs_thumbDir . $pDir . $part);
        };
        
        $this->files();
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
                    }
                }
                
                closedir($dh);  
            }
        }
    }
}
?>