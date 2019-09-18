<?php
namespace App\Common;
use \Slim\Http\UploadedFile;

class Files {

    public function moveFile(UploadedFile $file, $dir) {
        if ($file->getError() === UPLOAD_ERR_OK) {
            $fileName = $file->getClientFilename();
            $file->moveTo($dir . DIRECTORY_SEPARATOR . $fileName);
            return $fileName;
        }
        return false;
    }

    public function moveFileRandomName(UploadedFile $file, $dir) {
        if ($file->getError() === UPLOAD_ERR_OK) {
          $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
          $fileName = date('YmdHis') . '-' . rand(100, 999) . '.' . $extension;
          $file->moveTo($dir . DIRECTORY_SEPARATOR . $fileName);
          return $fileName;
        }
        return false;
    }

    public static function removeDir($dir) {
        $files = glob($dir . '/*');  
        foreach ($files as $file) { 
            if (is_file($file)) unlink($file); 
        }     
        return rmdir($dir);
    }

    public static function files($path) {
		$arr = [];
        $files = scandir($path);   
        foreach($files as $file) {  
    	    if (($file !== '.') && ($file !== '..')) {
				$arr[] = $file;
			}
        }
        return $arr;
    }
    
    public static function getPath($arr) {
        return join(DIRECTORY_SEPARATOR, $arr);
    }
    

}