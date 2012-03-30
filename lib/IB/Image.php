<?php Class IB_Image extends IB_Image_ThumbFactory
{
    static $s=false;
    static function getInstance(){if(!self::$s){self::$s=new IB_Image();}return self::$s;}
    
    
    function convert($source, $target, $deleteSource = false)
    {
        if(IMAGE_LIB === 'gd')
        {
            $source = $this->gdImage($source);
            
            $this->gdSave($source, $target, $deleteSource);
        }
        elseif(IMAGE_LIB === 'imagemagick')
        {
            exec("convert $source $target");
            exec('optipng -o5 '.$target);
            
            if($source != $target && $deleteSource) unlink($source);
        }
    }


    
    function _resize($source, $width, $height)
    {
        if(IMAGE_LIB === 'gd')
        {
            $i = $this->create($source);
            
            $i->resize($width, $height);
            $i->save($source);
            
        }
        elseif(IMAGE_LIB === 'imagemagick')
        {
            exec("convert $source -resize {$width}x{$height} $source");
            exec('optipng -o5 '.$source);
        }
    }

    
    function niceCrop($source, $target, $width, $height)
    {
        if(IMAGE_LIB === 'gd')
        {
            $i = $this->create($source);
            $i->resize($width*2, $height*2);
            $i->cropFromCenter($width, $height);
            $i->save($target);
        
        }
        elseif(IMAGE_LIB === 'imagemagick') // Warning : ONLY SQUARE SUPPORT !! (use width)
        {
            $size = $width;
            
            $doubleWidth  = $width * 2;
            $doubleHeight = $height * 2;
            
            exec("convert $source \
                    -resize {$doubleWidth}x -resize 'x{$doubleHeight}<'   -resize 50% \
                    -gravity center  -crop {$width}x{$height}+0+0 +repage $target");
            
            exec('optipng -o5 '.$target);
        }
    }
    
    
    
    
    
    
    # * * * ONLY GD FUNCTIONS * * * #

    function gdSave($source, $target, $deleteSource = false)
    {
        $extension = strtolower(pathinfo($target, PATHINFO_EXTENSION));

        if(is_file($target)) @unlink($target);
        
        switch($extension)
        {
            case 'png' : imagepng($source, $target); break;
            case 'jpeg': 
            case 'jpg' : imagejpeg($source, $target); break;
            case 'gif' : imagegif($source, $target); break;
            default: break;
        }
        
        if($deleteSource)
        {
            @unlink($source);
        }
    }
    
    function gdImage($source)
    {
        $img = getimagesize($source);
        
        switch($img['mime'])
        {
            case 'image/png' : $image = imagecreatefrompng($source); break;
            case 'image/jpeg': $image = imagecreatefromjpeg($source); break;
            case 'image/gif' :
                
                $old_id = imagecreatefromgif($source); 
                $image  = imagecreatetruecolor($img[0],$img[1]); 
                imagecopy($image,$old_id,0,0,0,0,$img[0],$img[1]); 
                break;
            default: break;
        }
        return $image;
    }
}