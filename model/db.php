<?php
$lic='*tikarambanjade.com.np'; // licensed domain can contain wildcard eg. *somesite.com (to allow for both http://somesite.com and http://www.somesite.com or many different subdomains eg. *.portal.com)
$lic2='localhost'; // provide possibility for alternate domain - can be used instead of wildcard to provide support for http://www.somesite.com and http://somesite.com with extra security
$GLOBALS['license'] = $lic;
$GLOBALS['license2'] = $lic2;
function stringMatchWithWildcard($source,$pattern) {
    $pattern = preg_quote($pattern,'/');        
    $pattern = str_replace( '\*' , '.*', $pattern);   
    return preg_match( '/^' . $pattern . '$/i' , $source );
}
/*
function stringMatchWithWildcard2($source,$pattern) {
    $pattern = preg_quote($pattern,'/');        
    $pattern = str_replace( '\*' , '.*', $pattern);   
    return preg_match( '/^' . $pattern . '$/i' , $source );
}
$hit1 = stringMatchWithWildcard2(strtolower (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $curdomain = $_SERVER['SERVER_NAME']), strtolower ($GLOBALS['license'])) ? 1 : 0;
if(strlen($GLOBALS['license2'])>0)
	$hit2 = stringMatchWithWildcard2(strtolower (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $curdomain = $_SERVER['SERVER_NAME']), strtolower ($GLOBALS['license2'])) ? 1 : 0; else $hit2=0;
if($hit1==0 && $hit2==0) die('{"res":0,"msg":"unlicensed domain..."}');
*/
// the following is the same above commented out code encrypted with this site: http://phpencoder.atwebpages.com/index.php
 eval(base64_decode("ZnVuY3Rpb24gc3RyaW5nTWF0Y2hXaXRoV2lsZGNhcmQyKCRzb3VyY2UsJHBhdHRlcm4pIHsNCiAgICAkcGF0dGVybiA9IHByZWdfcXVvdGUoJHBhdHRlcm4sJy8nKTsgICAgICAgIA0KICAgICRwYXR0ZXJuID0gc3RyX3JlcGxhY2UoICcqJyAsICcuKicsICRwYXR0ZXJuKTsgICANCiAgICByZXR1cm4gcHJlZ19tYXRjaCggJy9eJyAuICRwYXR0ZXJuIC4gJyQvaScgLCAkc291cmNlICk7DQp9DQokaGl0MSA9IHN0cmluZ01hdGNoV2l0aFdpbGRjYXJkMihzdHJ0b2xvd2VyIChpc3NldCgkX1NFUlZFUlsnSFRUUF9IT1NUJ10pID8gJF9TRVJWRVJbJ0hUVFBfSE9TVCddIDogJGN1cmRvbWFpbiA9ICRfU0VSVkVSWydTRVJWRVJfTkFNRSddKSwgc3RydG9sb3dlciAoJEdMT0JBTFNbJ2xpY2Vuc2UnXSkpID8gMSA6IDA7DQppZihzdHJsZW4oJEdMT0JBTFNbJ2xpY2Vuc2UyJ10pPjApDQoJJGhpdDIgPSBzdHJpbmdNYXRjaFdpdGhXaWxkY2FyZDIoc3RydG9sb3dlciAoaXNzZXQoJF9TRVJWRVJbJ0hUVFBfSE9TVCddKSA/ICRfU0VSVkVSWydIVFRQX0hPU1QnXSA6ICRjdXJkb21haW4gPSAkX1NFUlZFUlsnU0VSVkVSX05BTUUnXSksIHN0cnRvbG93ZXIgKCRHTE9CQUxTWydsaWNlbnNlMiddKSkgPyAxIDogMDsgZWxzZSAkaGl0Mj0wOw0KaWYoJGhpdDE9PTAgJiYgJGhpdDI9PTApIGRpZSgneyJyZXMiOjAsIm1zZyI6InVubGljZW5zZWQgZG9tYWluLi4uIn0nKTs=")); 
 
 Class dbCon{
	private $hostname ='localhost';
	private $database ='tikaram_sms';//'tikaram_sms'
	private $username ='tikaram';//tikaram
	private $password ='iIzCr&W6)w2)';//iIzCr&W6)w2)
	public $conn;
	public function connect(){
       $connect = mysqli_connect($this->hostname, $this->username, $this->password)
              or 
              die('sorry could not connect the database');
	    if ($connect) {
	    	mysqli_select_db($connect, $this->database)or
	    	die('sorry there is no any database');
	    }else{
	    	exit();
	    }
	    	return $this->conn = $connect;
	}
	public function close(){
		mysql_close();
		ob_flush();
		exit();
	}
	public function logout(){
		if(!isset($_SESSION)) {session_start();} 
		session_destroy();
		ob_flush();
	}
}