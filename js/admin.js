var admin =
{
    'translate':
    {
        'select': function(lang)
        {
            display('admin/translate/'+lang);
        },
        'edit': function(id)
        {
            IB.box.show('View_get&name=admin/dev/translate/form/Key&id='+id+'&width=425');
        },
        'addKey': function(id)
        {
            IB.box.show('View_get&name=admin/dev/translate/form/add&width=425');
        },
        'addLang': function(id)
        {
            IB.box.show('View_get&name=admin/dev/translate/form/addLang&width=425');
        },
        'addTranslator': function()
        {
            IB.box.show('View_get&name=admin/dev/translate/form/addTranslator&width=425');
        },
        'delete': function(id,mess)
        {
            if(confirm(mess))
            {
                $.loading();
                'Admin_deleteTranslation'.ajax('id='+id,function()
                {
                    $.displayMessage('key has been deleted').loaded();
                });
            }
        }
    }
};

admin.cleanCache = function()
{
    $.loading();
    'Admin_cleanCache'.ajax('',function(d)
    {
        $.displayMessage(d).loaded();
    });
};
admin.cleanLog = function()
{
    $.loading();
    'Admin_cleanLog'.ajax('',function(d)
    {
        $.displayMessage(d).loaded();
    });
};
admin.pack = function()
{
    $.loading();
    'Admin_pack'.ajax('',function(d)
    {
        $.displayMessage(d).loaded();
    });
};
admin.createWidget = function(e)
{
    var name = $.trim(e.prev().val());
    
    if(!name) return;
    $.loading();
    'Admin_createWidget'.ajax('widget='+name,function(d)
    {
        e.prev().val('');
        $.displayMessage('Widget created !').loaded();
        
        display(d);
    });
};
admin.deleteWidget = function(widget)
{
    if(!widget) return;
    if(confirm("Are you sure ?"))
    {
        $.loading();
        'Admin_deleteWidget'.ajax('widget='+widget,function(d)
        {
            $.displayMessage(d).loaded();

            IB.goBack();
        });
    }
};
admin.createController = function(e)
{
    var name = $.trim(e.prev().val());
    
    if(!name) return;
    $.loading();
    'Admin_createController'.ajax('name='+name,function(d)
    {
        e.prev().val('');
        $.displayMessage(d).loaded();
    });
};
admin.createSimplePage = function(e)
{
    var name = $.trim(e.prev().val());
    
    if(!name) return;
    $.loading();
    'Admin_createSimplePage'.ajax('name='+name,function(d)
    {
        e.prev().val('');
        $.displayMessage(d).loaded();
    });
};

admin.togglePack = function()
{
    'Admin_togglePack'.ajax('',refresh);
};