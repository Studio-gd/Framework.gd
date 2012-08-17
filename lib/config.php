<?php
define('URL',"http://framework/");
define('PATH','/Users/Studiogd/git/frameworkgd/');

define('DBHOST','localhost');
define('DBLOGIN','root');
define('DBPASS','root');
define('DBNAME','framework');

define('USE_JS_PACK',0);
define('MEMCACHE',0);
define('DEV',1);
define('REVISION','');

define('MOBILE',0);
define('FORCE_MOBILE',0);

define('IMAGE_LIB','gd');

define('SIGNUP_OPEN',1);

define('HOMEPAGE','home'); # lowercase
define('TITLE','Default Title');
define('TITLE_SEPARATOR',' | ');
define('STATS',0); # google analytics code, ex : UA-16879056-1
define('KEYWORDS',0);
define('DESCRIPTION',0);

define('EMAIL_FROM','"Studio.gd" <noReply@studio.gd>');

define('MULTI_LANGUE',false);
define('DEFAULT_LANG','fr');
date_default_timezone_set('Europe/Paris');

define('UPLOAD_FOLDER','files/');

define('FILE_CACHE',false);
define('FILE_CACHE_FOLDER',UPLOAD_FOLDER.'cache/');
    
define('AVATAR_FOLDER',UPLOAD_FOLDER.'avatar/');
define('AVATAR_SIZE','50,90'); # avatar size (not retroactive)

define('LOG_ERROR','error.log'); # error file name # false to desactivate
if(LOG_ERROR) define('LOG_ERROR_PATH',PATH.UPLOAD_FOLDER.LOG_ERROR);

define('DEBUG',0);
if(DEBUG) define('TIME',time());

define('SALT','!÷53_)(*&^58%$#)?');

set_include_path(PATH.'lib/');

define('USER_PER_PAGE',10);
define('ITEM_PER_PAGE',10);
define('RSS2','RSS 2.0',true);
define('ATOM','ATOM',true);


# PLUGINS (beta)
define('plugins', serialize(array('wysihtml5', 'bootstrap-wysihtml5'))); # 'colorpicker'

function a($n){include PATH.'lib/'.str_replace('_','/',$n).'.php';}spl_autoload_register('a');

if(MEMCACHE)
{
    $mc = new Memcache;
    $mc->connect('localhost',11211);
    $mc->setCompressThreshold(14000, 0.2);
    IB::getInstance()->set('mc',$mc);
}
IB_DB::connect();
function button($label, $css = false){return IB_Form_Button::create($label, $css)->get();}
function cancel(){return button(__('Cancel'), 'btn-danger');}
function div($css, $c){return '<div class="'.$css.'">'.$c.'</div>';}
function str($s){return preg_replace("[^a-z_]",'',strtolower($s));}
function now(){return date("Y-m-d H:i:s");}
function dateShift($date,$shift){return date("Y-m-d H:i:s",strtotime($shift,strtotime($date)));}
function relativeDate($shift){return dateShift(now(),$shift);}
function __($e,$u='',$l=''){return IB_Lang::getInstance()->__($e,$u,$l);}
function _s($e,$e2,$e3){if(strip_tags($e3)<=1) return sprintf(__($e),$e3);return sprintf(__($e2),$e3);}
function _d($d){return date(__('Y-m-d'),strtotime($d));}
function isAdmin(){return IB_User::getInstance()->isAdmin();}
function reader(){return IB_User::getInstance()->reader();}
function isReader($id){return reader()===intval($id);}
function isLoggedIn(){return IB_User::getInstance()->isLoggedIn();}
function getExtension($f){return strtolower(preg_replace('/^.*\./', '', $f));}
function view($n, $r = true){return IB::view($n, $r);}
function isMobile(){if(FORCE_MOBILE) return true;$D = new IB_Core_MobileDetect();return $D->isMobile();}