<?php Class IB_Form_Button extends IB_Form_Tags
{
    static function create($label, $css = false)
    {
        $s = new IB_Form_Button();
        
        $s->css = $css;
        $s->label = $label;
        
        return $s;
    }

    function get()
    {
        $attr = '';
        if($n = $this->name) $attr = ' id="'.$n.'" name="'.$n.'"';
        
        if(!$this->css) $css = 'btn-primary'; else $css = $this->css;

        $btn = '<button class="btn '.$css.'" type="submit"'.$attr.'>'.$this->label.'</button>';
        
        $container = $this->container ? ' '.$this->container : '';

        return div('field fieldBtn'.$container,$btn);
    }
}