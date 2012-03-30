<?php Class IB_Form_Textarea extends IB_Form_Tags
{
    static function create($name)
    {
        $s = new IB_Form_Textarea();
        
        $s->name = $name;
        return $s;
    }

    function get($textarea = '')
    {
        if($this->label)
        {
            $textarea.= '<label for="'.$this->name.'">'.$this->label.'</label>';
        }
        
        $textarea.= '<textarea name="'.$this->name.'" id="'.$this->name.'"';
        
        if($this->title)       $textarea.= ' title="'.$this->title.'"';
        if($this->placeholder) $textarea.= ' placeholder="'.$this->placeholder.'"';
        if($this->maxlength)   $textarea.= ' maxlength="'.$this->maxlength.'"';
        if($this->autofocus)   $textarea.= ' autofocus';
        if($this->readonly)    $textarea.= ' readonly="readonly"';
        
        // CSS CLASS
                
        if($this->autocomplete) $this->css.= ' autocomplete';
        if($this->counter)      $this->css.= ' counter';
        
        if($this->validate)
        {
            $this->css.= ' validate(';
            
            $tmp = '';
            
            if($this->required)   $tmp.= 'required,';
            if($this->valideType) $tmp.= $this->valideType.',';
            
            if($this->minlength && $this->maxlength)
            {
                 $tmp.= 'rangelength('.$this->minlength.','.$this->maxlength.'),';
            }
            elseif($this->minlength)
            {
                $tmp.= 'minlength('.$this->minlength.'),';
            }
            elseif($this->maxlength)
            {
                $tmp.= 'maxlength('.$this->maxlength.'),';
            }
            
            $this->css.= trim($tmp,',').')';
        }
        
        $textarea.= ' class="'.$this->css.'"';
        
        $textarea.= '>';
        
        if($this->value) $textarea.= $this->value;
        
        $textarea.= '</textarea>';
        
        if($this->counter)
        {
            $textarea.= '<div class="charLeft tt" title="'.__('Characters left').'">'.$this->maxlength.'</div>';
        }
        
        if($this->notice)
        {
            $textarea.= '<div class="note">'.$this->notice.'</div>';
        }
        
        $container = $this->container ? ' '.$this->container : '';
        
        return div('field'.$container,$textarea);
    }
    
}