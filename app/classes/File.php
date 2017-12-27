<?php
class File {
	private $_error = 0;

	public function upload($file, $targetDir, $targetName, $properties = array()) {
		$file_ext = $this->extension($file);
		$file_type = $file['type'];
		$file_tmp = $file['tmp_name'];
		$_error = $file['error'];
		$file_size = $file['size'];
		$properties['size'] = (isset($properties['size']) ? $properties['size'] :  52428800);
		$allowed = (isset($properties['extensions']) ? $properties['extensions'] : array());
		if($this->error() === 0) {
			if(isset($properties['width']) && isset($properties['height'])) {
				if(!$this->resize($file_tmp, $properties['width'], $properties['height'])) return false;
			}
			if($file_size <= $properties['size']) {
				if(in_array($file_type, $allowed) || empty($allowed)){
					$targetName = $targetName.".".$file_ext;
					$target = $targetDir.$targetName;
					if(move_uploaded_file($file_tmp, $target)){
					  return true;  
					}
				}
			}
		}
		return false;
	}
	
	public function error() {
		return $this->_error;
	}
	
	public function extension($file) {
		$file_name = $file['name'];
		$file_ext = explode('.', $file_name);
		return $file_ext = strtolower(end($file_ext));
	}
	
	public function resize($file_tmp, $width, $height) {
		$size = getimagesize($file_tmp);
		$ratio = $size[0]/$size[1];
		if($ratio * $height <= $width) {
			$width = $ratio * $height;
		} else if($width / $ratio <= $height) {
			$height = $width/$ratio;
		}
		$src = imagecreatefromstring(file_get_contents($file_tmp)); 
		$dst = imagecreatetruecolor($width,$height);
		imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
		if(imagejpeg($dst,$file_tmp)) {
			imagedestroy($src);
			imagedestroy($dst);
			return true;
		}
		imagedestroy($src);
		imagedestroy($dst);
		return false;
	}
	
	public function delete($path) {
		if(unlink($path)) return true;
		return false;
	}
}
?>