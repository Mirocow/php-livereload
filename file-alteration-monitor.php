<?php
/*
PHP LiveReload 
Copyright (C) 2012 Carlos Uldérico Cirello Filho

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace PHPLiveReload;
/* 
From 
Yassin Ezbakhe <yassin88 at gmail dot com> 31-Aug-2005 04:16
http://php.net/manual/en/ref.fam.php
Thanks, Yassin.
*/
class File_Alteration_Monitor{
	private $scan_directories, $initial_found_files;
	public function __construct($scan_directories){
		$this->scan_directories = $scan_directories;
		$this->update_monitor();
	}
	private function array_values_recursive($array){
		$array_values = array();
		foreach ($array as $value){
			if (is_scalar($value) || is_resource($value)){
				$array_values[] = $value;
			}elseif (is_array($value)){
				$array_values = array_merge($array_values, $this->array_values_recursive($value));
			}
		}
		return $array_values;
	}
	private function scandir_recursive($directories){
		$directory_contents = array();
		if(!is_array($directories)){
			$directories = array($directories);
		}
		foreach($directories as $directory){
			$directory = realpath($directory).DIRECTORY_SEPARATOR;
			foreach (scandir($directory) as $directory_item){
				if ($directory_item != "." && $directory_item != ".."){
					if (is_dir($directory.$directory_item.DIRECTORY_SEPARATOR)){
						$directory_contents[$directory_item] = $this->scandir_recursive($directory.$directory_item.DIRECTORY_SEPARATOR);
					}else{
						clearstatcache($directory.$directory_item);
						$directory_contents[] = serialize(array('di' => $directory_item, 'mtime' => filemtime($directory.$directory_item)));
					}
				}
			}
		}
		return $directory_contents;
	}
	public function get_new_files(){
		$final_found_files = $this->array_values_recursive($this->scandir_recursive($this->scan_directories));
		if ($this->initial_found_files != $final_found_files){
			$new_files = array_diff($final_found_files, $this->initial_found_files);
			return empty($new_files) ? FALSE : $new_files;
		}
	}
	public function get_removed_files(){
		$final_found_files = $this->array_values_recursive($this->scandir_recursive($this->scan_directories));
		if ($this->initial_found_files != $final_found_files){
			$removed_files = array_diff( $this->initial_found_files, $final_found_files);
			return empty($removed_files) ? FALSE : $removed_files;
		}
	}
	public function update_monitor(){
		$this->initial_found_files = $this->array_values_recursive($this->scandir_recursive($this->scan_directories));
	}
}
?>