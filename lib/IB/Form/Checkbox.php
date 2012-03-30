<?php Class IB_Form_Checkbox extends IB_Form_Tags
{
    static function create($name, $value = 0)
    {
        $s = new IB_Form_Checkbox();
        
        $s->name  = $name;
        $s->value = $value;
        
        return $s;
    }

    function get()
    {
        $d = '<div class="customCheckbox'.($this->value ? ' selected' : '').'"><input type="hidden" name="'.$this->name.'" value="'.$this->value.'" /><b class="'.($this->value ? 'icon-ok' : '').'"></b><span>'.$this->label.'</span></div>';

        $container = $this->container ? ' '.$this->container : '';
        
        return div('field fieldCheckbox'.$container,$d);
    }
}