<?php
define('SITE_NAME','ModHub');
define('MAX_MOD_SIZE',100*1024*1024);
define('MAX_THUMB_SIZE',5*1024*1024);
define('MOD_DIR',__DIR__.'/uploads/mods/');
define('THUMB_DIR',__DIR__.'/uploads/thumbs/');
define('DATA_FILE',__DIR__.'/data/mods.json');

define('ADMIN_USERNAME',getenv('MODHUB_ADMIN_USERNAME') ?: 'admin');
define('ADMIN_PASSWORD_HASH',getenv('MODHUB_ADMIN_PASSWORD_HASH') ?: '');

function ensure_storage():void{
 foreach([MOD_DIR,THUMB_DIR,dirname(DATA_FILE)] as $d) if(!is_dir($d)) @mkdir($d,0755,true);
 if(!file_exists(DATA_FILE)) @file_put_contents(DATA_FILE,"[]",LOCK_EX);
}
function load_mods():array{ensure_storage();$x=json_decode(@file_get_contents(DATA_FILE)?:'[]',true);return is_array($x)?$x:[];}
function save_mods(array $mods):bool{
 ensure_storage();$tmp=DATA_FILE.'.tmp';
 if(@file_put_contents($tmp,json_encode(array_values($mods),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT),LOCK_EX)===false)return false;
 return @rename($tmp,DATA_FILE);
}
function next_id(array $mods):int{$n=0;foreach($mods as $m)$n=max($n,(int)($m['id']??0));return $n+1;}
function json_response(array $d,int $s=200):never{http_response_code($s);header('Content-Type: application/json; charset=utf-8');echo json_encode($d,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;}
function e(?string $v):string{return htmlspecialchars($v??'',ENT_QUOTES,'UTF-8');}
function csrf_token():string{if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function check_csrf():void{if(!isset($_POST['csrf'])||!hash_equals($_SESSION['csrf']??'',$_POST['csrf'])){http_response_code(403);exit('Invalid CSRF token.');}}
function require_admin():void{if(empty($_SESSION['admin_ok'])){header('Location: /admin/login.php');exit;}}
function random_filename(string $ext):string{return bin2hex(random_bytes(16)).'.'.strtolower($ext);}
