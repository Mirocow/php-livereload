/*
PHP LiveReload 
Copyright (C) 2012 Carlos Uld�rico Cirello Filho

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
function php_livereload_poll(){
	var dirs = php_livereload_dirs.join('&d[]=');
	console.log(dirs);
	$.ajax(
	{
		'url': "/php-livereload/watch.php?d[]="+dirs, success: function(data){
			if(true == data['reload']){
				location.reload();
			}
		},
		dataType: "json",
		complete: php_livereload_poll,
		timeout: 30000
	}
	);
}
php_livereload_poll();