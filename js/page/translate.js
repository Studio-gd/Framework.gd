IB.translate = {fluid:{},e:{}};

IB.translate.init = function()
{
    //IB.translate.fluid.init();
    
    $('#C form.search').submit(function()
    {
        display('admin/dev/translate/'+$(this).parent().find('.l').val()+'/search/'+$(this).parent().find('.q').val());
        return false
    });
};