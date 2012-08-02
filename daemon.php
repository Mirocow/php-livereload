<?php
/*
PHP LiveReload 
Copyright (C) 2012 Carlos Uld�rico Cirello Filho

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace PHPLiveReload;
require_once('file-alteration-monitor.php');
class Daemon {
	public function __construct($directories, $cache_engine){
		$f = new File_Alteration_Monitor($directories, $cache_engine);
		while(1){
			$new_files = $f->get_new_files();
			$removed_files = $f->get_removed_files();
			$f->update_monitor();
			if(sizeof($new_files) > 0 || sizeof($removed_files) > 0){
				echo json_encode(array('reload' => true));
				die();
			}
			sleep(1);
		}
	}
	
}