<?php Class IB_Form_Select extends IB_Form_Tags
{
    static function create($name, $texts, $values)
    {
        $s = new IB_Form_Select();
        
        $s->name  = $name;
        $s->texts = $texts;
        $s->values = $values;
        
        return $s;
    }

    function get($d = '')
    {
        if($this->label)
        {
            $d.= '<label for="'.$this->name.'">'.$this->label.'</label>';
        }

        $d.= '<select name="'.$this->name.'" id="'.$this->name.'">';

        $values = $this->values;

        foreach($this->texts as $text)
        {
            $value = array_shift($values); 

            $d.= '<option value="'.$value.'" ';

            if($value === $this->value) $d.=' selected="selected"';

            $d.='>'.$text.'</option>';
        }
        $d.= '</select>';

        $container = $this->container ? ' '.$this->container : '';
        
        return div('field fieldSelect'.$container,$d);
    }
}