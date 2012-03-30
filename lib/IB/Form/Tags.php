<?php Class IB_Form_Tags
{
    protected $type        = false;
    protected $name        = false;
    protected $css         = false;
    protected $placeholder = false;
    protected $title       = false;
    protected $maxlength   = false;
    protected $minlength   = false;
    protected $label       = false;
    protected $autofocus   = false;
    protected $validate    = false;
    protected $valideType  = false;
    protected $required    = false;
    protected $readonly    = false;
    protected $value       = false;
    protected $notice      = false;
    protected $calendar    = false;
    protected $dateFormat  = false;
    protected $date        = false;
    protected $colorpicker = false;
    protected $autocomplete= false;
    protected $counter     = false;
    protected $container   = false;
    protected $editor      = false;
    
    function label($label, $subLabel = false)
    {
        if($subLabel)
        {
            $label.= " <span>$subLabel</span>";
        }
        
        $this->label = $label; return $this;
    }
    function css($css)
    {
        $this->css = $css; return $this;
    }
    function placeholder($placeholder)
    {
        $this->placeholder = $placeholder; return $this;
    }
    function title($title)
    {
        $this->title = $title; return $this;
    }
    function maxlength($maxlength)
    {
        $this->validate  = true;
        $this->maxlength = $maxlength; return $this;
    }
    function minlength($minlength)
    {
        $this->validate  = true;
        $this->minlength = $minlength; return $this;
    }
    function counter($maxlength = false)
    {
        $this->counter  = true;
        $this->validate = true;
        
        if($maxlength) $this->maxlength = $maxlength;
        
        return $this;
    }
    function autofocus()
    {
        $this->autofocus = true; return $this;
    }
    function required()
    {
        if($this->label) $this->label.= ' <span>('.__('Required').')</span>';
        
        $this->validate = true;
        $this->required = true; return $this;
    }
    function validate($type)
    {
        $this->validate   = true;
        $this->valideType = $type; return $this;
    }
    function readonly()
    {
        $this->readonly = true; return $this;
    }
    function value($value)
    {
        $this->value = $value; return $this;
    }
    function notice($notice)
    {
        $this->notice = $notice; return $this;
    }
    function autocomplete($source)
    {
        $this->autocomplete = $source; return $this;
    }
    function calendar($dateFormat = false, $date = false)
    {
        $this->calendar = true;
        
        $this->validate = true;
        $this->valideType = 'date';
        
        if($dateFormat) $this->dateFormat = $dateFormat; else $this->dateFormat = __('Y-m-d');
        
        $this->date = $date;
        
        return $this;
    }
    function colorpicker()
    {
        $this->colorpicker = true; return $this;
    }
    function editor()
    {
        $this->css('editor');
        $this->editor = true; return $this;
    }
    function container($container)
    {
        $this->container = $container; return $this;
    }
    
}