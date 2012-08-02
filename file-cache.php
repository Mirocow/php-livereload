<?php
/*
PHP LiveReload 
Copyright (C) 2012 Carlos Uldérico Cirello Filho

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace PHPLiveReload;
require_once('interface-cache.php');
class FileCache implements ICache {
	private $expire_time = 60;
	private $cache_filename = '/tmp/php-livereload';
	public function __construct(){
		if(isset($_SERVER, $_GET, $_POST)){
			$this->cache_filename = $this->cache_filename.'-'.sha1(serialize(array($_SERVER['SCRIPT_FILENAME'], $_GET, $_POST)));
		}
		touch($this->cache_filename);
	}
	function read($key){
		if((time() - filemtime($this->cache_filename)) > $this->expire_time){
			unlink($this->cache_filename);
			return false;
		}
		$obj = unserialize(file_get_contents($this->cache_filename));
		if(isset($obj[$key])){
			return $obj[$key];
		}else{
			return null;
		}
	}
	function write($key, $value){
		$obj = unserialize(file_get_contents($this->cache_filename));
		$obj[$key] = $value;
		file_put_contents($this->cache_filename, serialize($obj));
		return $value;
	}
}
?>