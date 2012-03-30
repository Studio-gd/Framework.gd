<?php
class Ajax_Admin
{
    static function cleanCache()
    {
        $c = new IB_Core_Cache();
        $c->clean();
        
        if(!DEV) // Clean up memcached
        {
            $mc = mc();
            $mc->flush();
        }
        
        echo 'Cache cleaned';
    }
    static function cleanLog()
    {
        if(!isAdmin()) return;
        
        IB_Error::cleanLog();
        
        echo 'Log cleaned';
    }
    static function saveTranslation()
    {
        IB_Lang::getInstance()->saveTranslation();
        
        echo "$.displayMessage('your changes has been saved');".
        "display($('.pager a#next').attr('href').substr(1));";
    }
    
    static function deleteTranslation()
    {
        if(!isAdmin()) return;
        
        IB_Lang::getInstance()->deleteKey($_POST['id']);
    }
    static function editKey()
    {
        if(!isAdmin()) return;
        
        IB_Lang::getInstance()->editKey($_POST['id']);
        echo "IB.box.rm();$.displayMessage('key updated')";
    }
    static function addKey()
    {
        IB_Lang::getInstance()->addKey();
        echo "IB.box.rm();$.displayMessage('key added')";
    }
    static function addLang()
    {
        IB_Lang::getInstance()->addLang();
        echo "IB.box.rm();$.displayMessage('lang added');display('admin/translate/".$_POST['id']."/all')";
    }
    static function addTranslator()
    {
        if(!isAdmin()) return;
        
        IB_Lang::getInstance()->addTranslator();
        echo "IB.box.rm();$.displayMessage('translator added');";
    }
        
    static function pack()
    {
        if(!isAdmin()) return;
        
        include PATH.'js/pack.php';
        include PATH.'css/pack.php';
        
        echo 'packed!';
    }
    static function togglePack()
    {
        $file = PATH.'lib/config.php';
        
        $f = IB_File::getInstance();
        
        $content = $f->readFile($file);
        
        if(!USE_JS_PACK)
        {
            $content = str_replace("define('USE_JS_PACK',0);","define('USE_JS_PACK',1);",$content);
            $content = str_replace("define('USE_JS_PACK', 0);","define('USE_JS_PACK',1);",$content);
            $content = str_replace("define('USE_JS_PACK', true);","define('USE_JS_PACK',1);",$content);
            $content = str_replace("define('USE_JS_PACK', TRUE);","define('USE_JS_PACK',1);",$content);
            $content = str_replace("define('USE_JS_PACK',true);","define('USE_JS_PACK',1);",$content);
            $content = str_replace("define('USE_JS_PACK',TRUE);","define('USE_JS_PACK',1);",$content);
            
            self::pack();
        }
        else
        {
            $content = str_replace("define('USE_JS_PACK',1);","define('USE_JS_PACK',0);",$content);
            $content = str_replace("define('USE_JS_PACK', 1);","define('USE_JS_PACK',0);",$content);
            $content = str_replace("define('USE_JS_PACK', false);","define('USE_JS_PACK',0);",$content);
            $content = str_replace("define('USE_JS_PACK', FALSE);","define('USE_JS_PACK',0);",$content);
            $content = str_replace("define('USE_JS_PACK',false);","define('USE_JS_PACK',0);",$content);
            $content = str_replace("define('USE_JS_PACK',FALSE);","define('USE_JS_PACK',0);",$content);
        }
        
        $f->writeFile($file, $content);
    }
    
    static function createWidget()
    {
        if(!isAdmin()) return;
        
        $widget = $_POST['widget'];
        
        echo IB_Admin::getInstance()->createWidget($widget);
    }
    static function deleteWidget()
    {
        if(!isAdmin()) return;
                
        IB_Admin::getInstance()->deleteWidget($_POST['widget']);
        
        echo 'Widget deleted !';
    }
    
    static function createController()
    {
        if(!isAdmin()) return;
        
        echo IB_Admin::getInstance()->createController($_POST['name']);
    }

    static function scaffold($simple = false)
    {
        if(empty($_POST['name']) || !isAdmin()) return false;
        
        $sc = new IB_Core_Scaffold();
        
        $name = $sc->init($simple);
        
        echo "$.displayMessage('Scaffold done');display('$name')";
    }

    static function simplepage()
    {
        self::scaffold(true);
    }



    
    static function isPacked()
    {
        echo USE_JS_PACK ? 'yes' : 'no';
    }
}
