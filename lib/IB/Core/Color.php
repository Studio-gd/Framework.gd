<?php
class IB_Core_Color
{
    static function shortToLong($hex)
    {
        if(!isset($hex{4})) $hex = preg_replace('/(.)/', '\1\1', $hex);
        return $hex;
    }
    static function lighter($hex, $factor)
    {
        $newHex = '';
        
        $hex = self::shortToLong($hex);
        
        $base['R'] = hexdec($hex{0}.$hex{1});
        $base['G'] = hexdec($hex{2}.$hex{3});
        $base['B'] = hexdec($hex{4}.$hex{5});
        foreach ($base as $k => $v)
        {
            $amount = 255 - $v;
            $amount = $amount / 100;
            $amount = round($amount * $factor);
            $new_decimal = $v+$amount;
            $newHexComponent = dechex($new_decimal);
            if(strlen($newHexComponent) < 2)
            {
                $newHexComponent = '0'.$newHexComponent;
            }
            $newHex.= $newHexComponent;
        }
        return $newHex;
    }
    static function darker($hex, $factor)
    {
        $newHex = '';
        
        $hex = self::shortToLong($hex);
        
        $base['R'] = hexdec($hex{0}.$hex{1});
        $base['G'] = hexdec($hex{2}.$hex{3});
        if(isset($hex{5})) $base['B'] = hexdec($hex{4}.$hex{5});
        foreach ($base as $k => $v)
        {
            $amount = $v;
            $amount = $amount / 100;
            $amount = round($amount * $factor);
            $new_decimal = $v - $amount;
            $newHexComponent = dechex($new_decimal);
            if(strlen($newHexComponent) < 2)
            {
                $newHexComponent = '0'.$newHexComponent;
            }
            $newHex.= $newHexComponent;
        }
        return $newHex;
    }
    static function isDark($hex)
    {
        $hex = self::shortToLong($hex);
        
        $c_r = hexdec(substr($hex, 0, 2));
        $c_g = hexdec(substr($hex, 2, 2));
        $c_b = hexdec(substr($hex, 4, 2));
        return ((($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1650) < 50;
    }
    static function contrast($hex, $factor)
    {
        if(self::isDark($hex))
        {
            return self::lighter($hex, $factor);
        }
        return self::darker($hex, $factor);
    }
    
    static function hex2rgb($hexstr)
    {
        $n = hexdec($hexstr);

        return array(0 => 0xFF & ($n >> 0x10), 1 => 0xFF & ($n >> 0x8), 2 => 0xFF & $n);
    }
}