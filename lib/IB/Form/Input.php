<?php Class IB_Form_Input extends IB_Form_Tags
{
    static function create($name, $type = 'text')
    {
        $s = new IB_Form_Input();
        
        $s->name = $name;
        $s->type = $type;
        
        return $s;
    }

    function get($input = '')
    {
        if($this->label)
        {
            $input.= '<label for="'.$this->name.'">'.$this->label.'</label>';
        }
        
        $input.= '<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->name.'"';
        
        if($this->title)        $input.= ' title="'.$this->title.'"';
        if($this->placeholder)  $input.= ' placeholder="'.$this->placeholder.'"';
        if($this->maxlength)    $input.= ' maxlength="'.$this->maxlength.'"';
        if($this->autofocus)    $input.= ' autofocus';
        if($this->readonly)     $input.= ' readonly="readonly"';
        if($this->value)        $input.= ' value="'.$this->value.'"';
        
        
        // CSS CLASS
        $this->css = $this->css ? $this->type.' '.$this->css : $this->type;
        
        if($this->colorpicker)
        {
            $this->css.= ' colorpicker';
            if($this->value) $input.= ' style="background-color:#'.$this->value.'"';
        }
        
        if($this->calendar)     $this->css.= ' calendar';
        if($this->dateFormat)   $this->css.= ' dateFormat_'.$this->dateFormat;
        if($this->date)         $this->css.= ' date_'.$this->date;
        if($this->autocomplete) $this->css.= ' autocomplete';
        if($this->counter)      $this->css.= ' counter';
        if($this->readonly)     $this->css.= ' readonly';
        
        if($this->validate)
        {
            $tmp = '';
            
            if($this->required)   $tmp.= ' required ';
            if($this->valideType) $tmp.= ' '.$this->valideType.' ';
            
            $this->css.= $tmp;
        }
        
        $input.= ' class="'.$this->css.'"';
        
        $input.= ' />';
        
        if($this->autocomplete)
        {
            $input.= '<input type="hidden" value="'.$this->autocomplete.'"/>';
        }
        if($this->counter)
        {
            $input.= '<div class="charLeft tt" title="'.__('Characters left').'">'.$this->maxlength.'</div>';
        }
        
        if($this->notice)
        {
            $input.= '<div class="note">'.$this->notice.'</div>';
        }
        
        if($this->type === 'hidden') return $input; // no container for hidden field
        
        $container = $this->container ? ' '.$this->container : '';
        
        return div('field'.$container,$input);
    }
    
}