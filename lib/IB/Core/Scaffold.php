<?php
class IB_Core_Scaffold extends IB_DB
{
    protected $namespace = false;
    protected $name = false;
    protected $table = false;
    protected $viewPath = false;
    protected $formPath = false;

    protected $labels = false;
    protected $fields = false;
    protected $types = false;
    protected $uploads = false;
    protected $calendars = false;
    protected $requires = false;
    protected $editors = false;
    protected $filters = false;
    protected $textareas = false;
    protected $checkboxes = false;
    protected $hidden = false;
    protected $colorpickers = false;
    protected $selects = false;
    protected $selectRessources = false;
    protected $F = array();

    protected $dates = false;
    protected $css = false;
    protected $js = false;
    protected $fluid = false;
    protected $mobile = false;
    protected $search = false;
    protected $sortable = false;
    protected $adminOnly = false;
    protected $rss = false;
    protected $xml = false;
    protected $json = false;

    protected $ressource = false; # simple page only

    function init($simplepage = false)
    {
        if(!empty($_POST['namespace']))
        {
            $this->namespace = trim(strtolower($_POST['namespace']));

            if(empty($this->namespace)) $this->namespace = false;
        }
        

        $this->name       = trim(strtolower($_POST['name']));
        $this->table      = $this->namespace ? $this->namespace.'_'.$this->name : $this->name;
        $this->viewPath   = $this->namespace ? $this->namespace.'/'.$this->name.'/' : $this->name.'/';
        $this->classname  = $this->namespace ? ucfirst($this->namespace).'_'.ucfirst($this->name) : ucfirst($this->name);

        
        $this->mobile     = (int) $_POST['mobile']; # TODO
        $this->css        = (int) $_POST['css'];
        $this->js         = (int) $_POST['js'];


        if($simplepage)
        {
            $this->ressource = trim($_POST['ressource']);

            $this->createSimplePage();
            return;
        }


        $this->fluid           = (int) $_POST['fluid'];
        $this->dates           = (int) $_POST['dates'];
        $this->search          = (int) $_POST['search'];
        $this->sortable        = (int) $_POST['sortable'];
        $this->adminOnly       = (int) $_POST['adminOnly'];
        $this->rss             = (int) $_POST['rss'];
        $this->xml             = (int) $_POST['xml'];
        $this->json            = (int) $_POST['json'];
     
     
        $this->labels          = $_POST['labels'];
        $this->fields          = $_POST['fields'];
        $this->types           = $_POST['types'];
        $this->uploads         = $_POST['uploads'];
        $this->calendars       = $_POST['calendars'];
        $this->requires        = $_POST['requires'];
        $this->editors         = $_POST['editors']; # TODO
        $this->filters         = $_POST['filters'];
        $this->textareas       = $_POST['textareas'];
        $this->checkboxes      = $_POST['checkboxes'];
        $this->hidden          = $_POST['hidden'];
        $this->colorpickers    = $_POST['colorpickers'];
        $this->selects         = $_POST['selects'];
        $this->selectRessources= $_POST['selectRessources'];


        $this->cleanArrays();

        # DB TABLE
        $this->createTable();

        # FORMS
        $this->setFormPath();
        $this->createForm();
        $this->editForm();
        $this->fieldForm();

        # CLASS
        $this->createClass();
        
        # CSS / JS
        if($this->css) $this->createCss();
        if($this->js)  $this->createJs();

        # AJAX
        $this->createAjax();

        # JSON
        if($this->json) $this->createJson();

        # RSS
        if($this->rss) $this->createRss();
        
        # XML
        if($this->xml) $this->createXml();

        # MOBILE
        $this->createMobile();

        # VIEWS
        $view = $this->namespace ? $this->namespace.'/'.$this->name.'/list' : $this->name.'/list';
        $this->createView($view,'list');

        $view = $this->namespace ? $this->namespace.'/'.$this->name.'/item' : $this->name.'/item';
        $this->createView($view,'item');

        # CONTROLLER
        $this->createController();
    }


    function cleanArrays()
    {
        $r = array();

        $i=0;
        while($i<999)
        {
            if(empty($this->fields[$i])) break;

            $r[$i] = array();

            $r[$i]['label']           = trim( $this->labels[$i]);
            $r[$i]['field']           = trim( $this->fields[$i]);
            $r[$i]['type']            = trim( $this->types[$i]);
            $r[$i]['upload']          = (int) $this->uploads[$i];
            $r[$i]['calendar']        = (int) $this->calendars[$i];
            $r[$i]['require']         = (int) $this->requires[$i];
            $r[$i]['editor']          = (int) $this->editors[$i];
            $r[$i]['filter']          = (int) $this->filters[$i];
            $r[$i]['textarea']        = (int) $this->textareas[$i];
            $r[$i]['checkbox']        = (int) $this->checkboxes[$i];
            $r[$i]['hidden']          = (int) $this->hidden[$i];
            $r[$i]['colorpicker']     = (int) $this->colorpickers[$i];
            $r[$i]['select']          = (int) $this->selects[$i];
            $r[$i]['selectRessource'] = trim( $this->selectRessources[$i]);

            $i++;
        }

        $this->F = $r;
    }

    function createFolder($path)
    {
        if(!file_exists($path))
        {
            mkdir($path, 0755, true);
        }
    }


    function createTable()
    {
        $this->query("DROP TABLE IF EXISTS `{$this->table}`");

        $r = "CREATE TABLE `{$this->table}` 
(
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,";

        foreach($this->F as $v)
        {
            $r.= "
  `{$v['field']}` {$v['type']} NOT NULL,";
        }

        if($this->sortable)
        {
            $r.= "
  `weight` int(2) unsigned NOT NULL,";
        }

        if($this->dates)
        {
            $r.= "
  `created_at` datetime NOT NULL,";
            $r.= "
  `updated_at` datetime NOT NULL,";
        }

        $r.="
PRIMARY KEY (`id`) );";

        IB_File::getInstance()->writeFile(PATH.'doc/sql/'.$this->table.'.sql', "\n$r\n");

        $this->query($r);
    }


    function setFormPath()
    {
        $this->formPath = PATH.'lib/Html/'.$this->viewPath.'form/';

        $this->createFolder($this->formPath);
    }

    function createForm()
    {
        $file = $this->formPath.'create.php';

        if(file_exists($file)) return;

        $d = "
<form action=\"a={$this->classname}_create\">

<?php echo view('{$this->viewPath}form/fields'); ?>

</form>";
        
        IB_File::getInstance()->writeFile($file, $d);
    }

    function editForm()
    {
        $file = $this->formPath.'edit.php';

        if(file_exists($file)) return;

        $d = "<?php 

\$id = (int) \$P->get('id');

\$v = IB_{$this->classname}::getInstance()->get(array('id'=>\$id),1);

\$P->set('value',\$v);

?>
<form action=\"a={$this->classname}_edit&id=<?php echo \$id; ?>\">

<?php echo view('{$this->viewPath}form/fields'); ?>

</form>";
        
        IB_File::getInstance()->writeFile($file, $d);
    }



    function fieldForm()
    {
        $file = $this->formPath.'fields.php';

        if(file_exists($file)) return;

        $d = "<?php

\$fields = '';
";
foreach($this->F as $v)
{
    $field = $v['field'];
    $type  = $v['type'];
    $label = $v['label'];

    if($field === 'user_id') continue;

    if($v['select'])
    {
        $ressource = $v['selectRessource'];

        $d.= "

\$dataSelect = {$ressource}::getInstance()->get(array());

\$texts[]  = '';
\$values[] = '';

foreach(\$dataSelect as \$v)
{
    \$texts[]  = \$v['title'];
    \$values[] = \$v['id'];
}

\$fields.= IB_Form_Select::create('$field', \$texts, \$values)
           ->label(__('$label'))
           ->value(\$P->getValue('$field'))
           ->get();";

        continue;
    }


                         $typeField = '';
        if($v['upload']) $typeField = ",'file'";
    elseif($v['hidden']) $typeField = ",'hidden'";

                           $typeClass = 'Input';
        if($v['textarea']) $typeClass = "Textarea";
    elseif($v['checkbox']) $typeClass = "Checkbox";

        $d.= "
\$fields.= IB_Form_$typeClass::create('$field'$typeField)
           ->label(__('$label'))";

        if(!$v['upload'])
        {
            $d.= "
           ->value(\$P->getValue('$field'))";
        }
        if($v['calendar'] && $typeClass === 'Input')
        {
            $d.= "
           ->calendar()";
        }
        if($v['require'])
        {
            $d.= "
           ->required()";
        }
        if($v['colorpicker'])
        {
            $d.= "
           ->colorpicker()";
        }
        if($v['editor'] && $typeClass === 'Textarea')
        {
            $d.= "
           ->editor()";
        }

        $d.= "
           ->get();";
}

$d.= "
\$fields.= div('groupBtn', button(__('Save')).cancel()); #.resetButton()

echo \$fields;
";

        IB_File::getInstance()->writeFile($file, $d);
    }


    function createClass()
    {
        $path = PATH.'lib/IB/';
        $path.= $this->namespace ? ucfirst($this->namespace).'/' : '';

        $this->createFolder($path);

        $file = $path.ucfirst($this->name).'.php';

        if(file_exists($file)) return 'This class already exist..';

        
        $order = $this->sortable ? 'ORDER BY weight ASC' : 'ORDER BY id DESC';


        $adminTest = $this->adminOnly ? 'if(!isAdmin()) return;' : '';


        $d = "<?php Class IB_{$this->classname} extends IB_DB
{
    static \$s = false;static function getInstance(){if(!self::\$s){self::\$s=new IB_{$this->classname}();}return self::\$s;}

    function getArray()
    {
        return array
        (
            ";
        
        foreach($this->F as $v)
        {
            $field = $v['field'];

            if($v['upload']) continue;

            if($v['field'] === 'user_id')
            {
                $d.= "'$field' => reader(),
            ";
            }
            else
            {
                if($v['editor'])
                {
                    $d.= "'$field' => \$_POST['$field'],
            ";
                }
                else
                {
                    $d.= "'$field' => Clean::string(\$_POST['$field']),
            ";
                }
                
            }
        }
        
        if($this->dates)
        {
            $d.= "'updated_at' => now()";
        }
        $d.= "
        );
    }

    function create()
    {
        $adminTest
        
        \$r = \$this->getArray();
";
    if($this->dates)
    {
        $d.= "        \$r['created_at'] = now();";
    }


    foreach($this->F as $v)
    {
        if($v['upload'])
        {
            $field = $v['field'];
            $d.= "        \$this->addFiles(\$this->lastInsertId(),'$field');";
        }
    }


    $d.= "
        \$this->insert('{$this->table}',\$r);
    }
    
    function edit(\$id)
    {
        $adminTest
        
        \$this->update('{$this->table}',\$this->getArray(),\"id=\$id\");

        ";

        foreach($this->F as $v)
        {
            if($v['upload'])
            {
                $field = $v['field'];
                $d.= "        \$this->addFiles(\$id,'$field');";
            }
        }


    $d.=
    "
    }

    ";


    $d.= 
    "
    function addFile(\$id, \$field)
    {
        if(!empty(\$_FILES[\$field]['name']))
        {
            \$path = PATH.UPLOAD_FOLDER.'{$this->table}/'.\$field.'/';
            if(!file_exists(\$path)) mkdir(\$path, 0755, true);
            \$ext = getExtension(\$_FILES[\$field]['name']);
            \$filename = \$id.'.'.\$ext;
            \$file = \$path.\$filename;

            if(file_exists(\$file)) @unlink(\$file);

            IB_File::getInstance()->upload(\$_FILES[\$field], \$filename, \$path);
            
            #IB_Image::getInstance()->_resize(\$file, 200, 100);
            \$this->update('{$this->table}',array(\$field => \$filename),\"id=\$id\");
        }
    }

    ";

    $d.=
    "

    function buildQuery(\$options)
    {
        \$default = array
        (
            'id'       => false,
            'order'    => ' $order',
            ";

        foreach($this->F as $v)
        {
            $field = $v['field'];

            if($v['filter'])
            {
                $d.= "
            '$field' => false,";
            }
        }


    $d.= "
            ".($this->search ? "'search'    => false," : '')."
        );
        \$opt = array_merge(\$default,\$options);
        
        \$w = '';
        
        if(\$opt['id'])
        {
            \$w.='id = '.\$opt['id'];
        }
        ";

        foreach($this->F as $v)
        {
            $field = $v['field'];

            if($v['filter'] && strpos($v['type'],"int"))
            {
                $d.= "
        if(\$opt['$field'])
        {
            \$w.='$field = '.\$opt['$field'];
        }";
            }
            elseif($v['filter'])
            {
                $d.= "
        if(\$opt['$field'])
        {
            \$w.='$field = \"'.\$opt['$field'].'\"';
        }";
            }
        }


    $d.= "
        return \$w.\$opt['order'];
    }

    function get(\$options,\$number=0,\$offset=0,\$select='*')
    {
        \$d = \$this->select('{$this->table}',\$select,\$this->buildQuery(\$options),\$number,\$offset);
        
        if(\$d && \$number == 1) return \$d[0];
        
        return \$d;
    }

    function getTotal(\$options = array())
    {
        \$c = \$this->buildQuery(\$options);
        
        if(!empty(\$c))
        {
            \$c = trim(\$c);
        
            if(substr(\$c,0,5) !== 'ORDER') \$c = \"WHERE \$c\";
        }
        return \$this->count('SELECT id FROM {$this->table} '.\$c);
    }
    
    /*
    function getName(\$id)
    {
        return \$this->selectOne('{$this->table}','name',\"id = \$id\");
    }
    */

    function remove(\$id)
    {
        $adminTest
        
        \$this->delete('{$this->table}',\"id=\$id\");
    }";
    
    if($this->sortable)
    {
        $d.= "
    function updateOrder(\$ids)
    {
        $adminTest
    
        \$i = 0;
        foreach(\$ids as \$id)
        {
            \$id = (int) \$id;
    
            \$this->query(\"UPDATE {$this->table} SET weight = \$i WHERE id = \$id\");
            \$i++;
        }
    }
";
    }
    
    $d.= "}";
       
        IB_File::getInstance()->writeFile($file, $d);
    }



    function createCss($simple = false)
    {
        $pathName = $this->namespace ? $this->namespace.'/'.$this->name : $this->name;

        $filePath = PATH.'css/page/'.$pathName.'.css';
        
        if($this->namespace)
        {
            $this->createFolder(PATH.'css/page/'.$this->namespace);
        }

        if(file_exists($filePath)) return; 
        
        if(!$simple)
        {
            $d = "/* ul.{$this->name}List
{
    li.adminItem
    {
        a
        {
            
        }
    }
}
*/";
        }
        else
        {
            $d = "div.{$this->name}
{
    float:left;
    width:100%;
}
";
        }
        
        $f = IB_File::getInstance();
        
        $f->writeFile($filePath, $d);
        
        $includeFile = PATH.'css/include.php';
        
        $content = $f->readFile($includeFile);
        
        $include = "  'page/$pathName',\n#placeholder4scaffold (do not remove !)";
        
        if(!strpos($content,"'$pathName',"))
        {
            $content = str_replace('#placeholder4scaffold (do not remove !)',$include,$content);

            $f->writeFile($includeFile, $content);
        }
    }




    function createJs($toInclude = true, $simple = false)
    {
        $pathName = $this->namespace ? $this->namespace.'/'.$this->name : $this->name;

        $filePath = PATH.'js/page/'.$pathName.'.js';
        
        if($this->namespace)
        {
            $this->createFolder(PATH.'js/page/'.$this->namespace);
        }
        
        if(file_exists($filePath)) return;
        
        $jsClassname = $this->namespace ? $this->namespace.'.'.$this->name : $this->name;


        $fluid1 = $this->fluid ? '{fluid:{},e:{}}': '{}';
        $fluid2 = $this->fluid ? "IB.$jsClassname.fluid.init();" : '';

        $d = '';

        if($this->namespace)
        {
            $d.= "IB.{$this->namespace} = {};
IB.{$this->namespace}.init = function()
{
};";
        }

        if(!$simple)
        {
            $d.= "IB.$jsClassname = $fluid1;

IB.$jsClassname.init = function()
{
    $fluid2
    $('ul.{$this->name}List a.delete').click(IB.$jsClassname.del);
};

IB.$jsClassname.del = function()
{
    if(confirm('Are you sure ?'))
    {
        $.loading();
        var item = $(this).parent();

        var id = item.attr('id').replace('{$this->name}Id_','');

        '{$this->classname}_delete'.ajax('id='+id,function(d)
        {
            $.displayMessage(d).loaded();

            IB.hide(item);
        });
    }
};

";      

        }
        else
        {
            $d.= "IB.$jsClassname = {};

IB.$jsClassname.init = function()
{
    
};
";
        }

        $f = IB_File::getInstance();
        
        $f->writeFile($filePath, $d);
        
        // include the new js file
        $includeFile = PATH.'js/include.php';
        
        $content = $f->readFile($includeFile);
        
        $admin = $this->adminOnly ? ',true' : '';
        
        $include = "->js('page/$pathName'$admin)\n#placeholder4scaffold (do not remove !)";
        
        if(!$toInclude)
        {
            $include = '#'.$include;
        }
        
        if(!strpos($content,"->js('page/$pathName'$admin)"))
        {
            $content = str_replace('#placeholder4scaffold (do not remove !)',$include,$content);

            $f->writeFile($includeFile, $content);
        }
        

        if(!$this->fluid) return;


        $filePath = PATH.'js/page/'.$pathName.'.fluid.js';
        
        if(file_exists($filePath)) return;
        
        $d = "
IB.$jsClassname.fluid.init = function()
{
    return IB.$jsClassname.fluid;
};
IB.$jsClassname.fluid.apply = function()
{

};";
        $f = IB_File::getInstance();
        
        $f->writeFile($filePath, $d);
        
        // include the new js file
        $includeFile = PATH.'js/include.php';
        
        $content = $f->readFile($includeFile);
        
        $include = "#->js('page/$pathName.fluid'$admin)\n#placeholder4scaffold (do not remove !)";
        
        if(!strpos($content,"#->js('page/$pathName.fluid'$admin)"))
        {
            $content = str_replace('#placeholder4scaffold (do not remove !)',$include,$content);

            $f->writeFile($includeFile, $content);
        }
    }


    function createAjax()
    {
        $path = PATH.'lib/Ajax/';
        $path.= $this->namespace ? ucfirst($this->namespace).'/' : '';

        $this->createFolder($path);

        $file = $path.ucfirst($this->name).'.php';
        
        if(file_exists($file)) return;

        $d = "<?php class Ajax_{$this->classname}
{
    static function create()
    {
        IB_{$this->classname}::getInstance()->create();
        echo \"$.displayMessage('\".Clean::strJs(__('{$this->name} created')).\"');display('{$this->viewPath}');\";
    }
    static function edit()
    {
        \$id = (int) \$_POST['id'];
        
        IB_{$this->classname}::getInstance()->edit(\$id);
        echo \"$.displayMessage('\".Clean::strJs(__('{$this->name} updated')).\"');display('{$this->viewPath}');\";
    }
    static function delete()
    {
        IB_{$this->classname}::getInstance()->remove(intval(\$_POST['id']));
        echo __('This {$this->name} deleted.');
    }
";
    
    if($this->sortable)
    {
        $d.= "
    static function updateOrder()
    {
        IB_{$this->classname}::getInstance()->updateOrder(\$_POST['{$this->name}Id']);
    }
";
    
    }
    $d.= "}";

        IB_File::getInstance()->writeFile($file, $d);
    }




    function createJson()
    {
        $path = PATH.'lib/Json/';
        $path.= $this->namespace ? $this->namespace.'/'.$this->name : $this->name;
        
        $this->createFolder($path);
        
        $file = $path.'/list.php';
        
        if(file_exists($file)) return;
        
        $d = "<?php
\$pager = new IB_Pager(ITEM_PER_PAGE);

\${$this->name} = IB_{$this->classname}::getInstance();

\$data{$this->classname} = \${$this->name}->get(array(),\$pager->number,\$pager->offset);

if(\$data{$this->classname})
{
    echo \$this->json_encode(\$data{$this->classname});
}
";
        IB_File::getInstance()->writeFile($file, $d);
    }
    
    
    function createSimplePage()
    {
        $view = $this->namespace ? $this->namespace.'/'.$this->name : $this->name;
        $this->createView($view, 'simple');


        $path = PATH.'lib/Controller/';
        $path.= $this->namespace ? ucfirst($this->namespace).'/' : '';

        $this->createFolder($path);

        $file = $path.ucfirst($this->name).'.php';

        $viewPath = $this->namespace ? $this->namespace.'/'.$this->name : $this->name;

        if(!file_exists($file))
        {
            $d = "<?php Class Controller_{$this->classname} extends IB_Controller
{
    static function i(){return new Controller_{$this->classname}();}
    function index(\$arg)
    {
        \$this->set('title','{$this->classname}')->view('{$viewPath}');
    }
}";
            IB_File::getInstance()->writeFile($file, $d);
        }
        
        if($this->css) $this->createCss(true);

        if($this->js) $this->createJs(true, true);

        if($this->mobile) $this->createMobile(true);

    }



    function createView($widget, $type = '')
    {
        $widget = trim(strtolower($widget),'/');

        if($widget==='') return false;
        
        $path = '';
        
        if(!strpos($widget,'/'))
        {
            $file = strtolower($widget);
        }
        else
        {
            $w = explode('/',$widget.'.php');
            
            foreach($w as $fileName)
            {                
                if(!strpos($fileName,'.php'))
                {
                    $path.= $fileName.'/';
                }
                else
                {
                    $file = substr($fileName,0,-4);
                }
            }
            
            $this->createFolder(PATH.'lib/Html/'.$path);
        }
        
        if($file==='') return false;


        $label = 'id';

        foreach($this->F as $v)
        {
            if($v['field'] === 'title')
            {
                $label = "v['title']";
                break;
            }
            if($v['field'] === 'name')
            {
                $label = "v['name']";
                break;
            }
        }
        

        $uri = $this->namespace ? "/{$this->namespace}/{$this->name}" : "/{$this->name}";

        if($type === 'list')
        {
            $sortableClass = $this->sortable ? ' sortable' : '';

            $content = "<?php

\$data = '<div><a href=\"{$uri}/create\" class=\"btn btn-primary\"><i class=\"icon-plus icon-white\"></i> '.__('Create').'</a></div>';
            
\$data.= '<ul id=\"".ucfirst($this->classname)."\" class=\"{$this->name}List adminList{$sortableClass}\">';

\${$this->name} = IB_{$this->classname}::getInstance();
";

$filters = '';

if($this->search)
{
    $content.= "\$search = \$P->get('search');";

    $filters.= "'search' => \$search,";
}

foreach($this->F as $v)
{
    $field = $v['field'];

    if($v['filter'])
    {
        $content.= "\${$field} = \$P->get('$field');
";

        $filters.= "'$field' => \${$field},";
    }
}
$filters = trim($filters,',');

$content.= "

\$pager = new IB_Pager(ITEM_PER_PAGE, \${$this->name}->getTotal(array($filters)));

if(\$data{$this->classname} = \${$this->name}->get(array($filters),\$pager->number,\$pager->offset))
{
    foreach(\$data{$this->classname} as \$v)
    {
        \$P->set('value',\$v);

        \$data.= view('{$this->viewPath}item');
    }

    \$data.= view('layout/pager');
}
echo \$data.'</ul>';";
}
elseif($type === 'item')
{
    $content = "<?php

\$v = \$P->get('value');

\$id = \$v['id'];

\$data = '<li id=\"{$this->name}Id_'.\$id.'\" class=\"adminItem\"><a href=\"{$uri}/edit/'.\$id.'\">'

.\${$label}.'</a>';

if(isAdmin()) #|| reader() == \$v['user_id'] <- allow owner to edit
{
    \$data.= '<a title=\"'.__('Delete').'\" class=\"tt icon delete\"></a>';
    \$data.= '<a title=\"'.__('Edit').'\" class=\"tt icon edit\" href=\"{$uri}/edit/'.\$id.'\"></a>';
    ";
    if($this->sortable)
    {
        $content.= "\$data.= '<a title=\"'.__('Move').'\" class=\"tt icon move\"></a>';
";
    }
$content .= "}

\$data.= '</li>';

echo \$data;";
}
elseif($type === 'simple')
{
    $content = "<div class=\"$this->name\">
    
    <h2>{$this->name}</h2>
    
    ";

    if(!empty($this->ressource))
    {
        $ressource = $this->ressource;

        $ressourceName = str_replace('IB_', '', $this->ressource);

        $content.= "

    <?php

    \$$ressourceName = $ressource::getInstance();

    \$options = array();
    
    \$total = \${$ressourceName}->getTotal(\$options);

    \$pager = new IB_Pager(ITEM_PER_PAGE, \$total);


    if(\$data$ressourceName = \${$ressourceName}->get(\$options, \$pager->number, \$pager->offset))
    {
        foreach(\$data$ressourceName as \$v)
        {
            ?>
            <div class=\"\">
                
                <?php echo \$v['id']; ?>

            </div>
            <?php
        }


        echo view('layout/pager');
    }

    ?>

        ";
    }


    $content.= "
    
</div>";
}
else
{
    $content = "<div class=\"$this->name\">
    
    <h2>{$this->name}</h2>
    
    
</div>";
}
        
        IB_File::getInstance()->writeFile(PATH.'lib/Html/'.$path.$file.'.php', $content);
        
        return $uri;
    }

    function createController()
    {
        $path = PATH.'lib/Controller/';
        $path.= $this->namespace ? ucfirst($this->namespace).'/' : '';

        $this->createFolder($path);

        $file = $path.ucfirst($this->name).'.php';

        if(file_exists($file)) return 'This controller already exist..';
        
        if($this->adminOnly)
        {
            $i = "isAdmin() ? new Controller_{$this->classname}() : new Controller_Home()";
        }
        else
        {
            $i = "new Controller_{$this->classname}()";
        }

        $d = "<?php Class Controller_{$this->classname} extends IB_Controller
{
    static function i(){return $i;}
    function index(\$arg=array())
    {
        \$this->{$this->name}s(\$arg);
    }
    function create()
    {
        if(!DEV) \$this->set('cached',5000);

        \$this->set('title',__('Create').TITLE_SEPARATOR.__('{$this->name}'))
              ->view('admin/menu')
              ->view('{$this->viewPath}form/create');
    }
    function edit(\$arg)
    {
        \$this->set('title',__('{$this->name}').TITLE_SEPARATOR.__('Edit').TITLE_SEPARATOR.'id : '.\$arg[0])
              ->set('id',\$arg[0])
              ->view('admin/menu')
              ->view('{$this->viewPath}form/edit');
    }
    function {$this->name}s(\$arg = array())
    {
        \$this->getUrlValues(\$arg);
        
        \$this->set('title',__('{$this->name}').TITLE_SEPARATOR.__('List'))
              ->view('admin/menu')
              ->view('{$this->viewPath}list');
    }
    function show(\$arg)
    {
        \$this->init('Menu')->set('title',__('{$this->name}').TITLE_SEPARATOR.__('id : ').\$arg[0])
              ->set('id',\$arg[0])
              ->view('admin/menu')
              ->view('{$this->viewPath}item');
    }
}";
        
        IB_File::getInstance()->writeFile($file, $d);
    }





    function createRss()
    {
        $path = PATH.'lib/Rss/';
        $path.= $this->namespace ? ucfirst($this->namespace).'/'.ucfirst($this->name) : ucfirst($this->name);
        
        $this->createFolder($path);
        
        $file = $path.'/List.php';
        
        if(file_exists($file)) return;


        $content = "    \$pager = new IB_Pager(ITEM_PER_PAGE);
        
        \$nbr    = \$pager->number;
        \$offset = \$pager->offset;

        \${$this->name} = IB_{$this->classname}::getInstance();
        
        \$data{$this->classname} = \${$this->name}->get(array(),\$nbr,\$offset);
        
        if(\$data{$this->classname})
        {
            foreach(\$data{$this->classname} as \$v)
            {
                \$item = self::\$feed->createNewItem();

                \$item->setTitle(utf8_decode(\$v['title']));
                \$item->setLink(URL.'{$this->name}/show/'.\$v['id']);
                \$item->setDate(\$v['created_at']);
                \$item->setDescription(\$v['txt']);
                
                self::\$feed->addItem(\$item);
            }
        }
        ";

        $d = "<?php class Rss_{$this->classname}_List extends IB_Rss
{
    static function getInstance(){return new Rss_{$this->classname}_List();}
    function preRender()
    {
    $content
    }

}";
    
        IB_File::getInstance()->writeFile($file, $d);
    }


    function createXml()
    {
        $path = PATH.'lib/Xml/';
        $path.= $this->namespace ? ucfirst($this->namespace).'/'.ucfirst($this->name) : ucfirst($this->name);
        
        $this->createFolder($path);
        
        $file = $path.'/List.php';
        
        if(file_exists($file)) return;

        $content = "    \$data = '';
        
        \$pager = new IB_Pager(ITEM_PER_PAGE);
        
        \$nbr    = \$pager->number();
        \$offset = \$pager->offset();

        \${$this->name} = IB_{$this->classname}::getInstance();
                
        \$data{$this->classname} = \${$this->name}->get(array(),\$nbr,\$offset);
        
        if(\$data{$this->classname})
        {
            foreach(\$data{$this->classname} as \$v)
            {
                \$data = '
          <item
        ';
        
                foreach(\$v as \$key => \$value)
                {
                    \$data.=  '    '.\$key . '=\"' . Clean::xmlentities(\$value) . '\" 
        ';
                }
                
                \$data.= '  />';
            }
        }
        

        return \$data;
        ";

        $d = "<?php class Xml_{$this->classname}_List extends IB_Xml
{
    static function getInstance(){return new Xml_{$this->classname}_List();}
    function preRender()
    {
    $content
    }

}";
    
        IB_File::getInstance()->writeFile($file, $d);

    }

    function createMobile($simple = false)
    {

        

        
    }

}