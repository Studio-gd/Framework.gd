IB.user = {fluid:{},e:{}};

IB.user.init = function()
{
    //IB.user.fluid.init();
    
    
    if(IB.pageName[1]==='edit')
    {
        $('form a.deleteAvatar').click(function()
        {
            if(confirm('Are you sure ?'))
            {
                $.loading();
                
                var e = $(this);
                
                'Avatar_delete'.ajax('id='+e.attr('id').replace('userId','')+'&object=user',function(d)
                {
                    e.prev().fadeOut(400);
                    e.remove();
                    $.displayMessage(d).loaded();
                });
            }
            
        });
        
        
        $('form a.editPwd').toggle(function()
        {
            $(this).next().slideDown(350,IB.fluid.footer).children().val('');
        },
        function()
        {
            $(this).next().slideUp(350,IB.fluid.footer).children().val('');
        });

    }
    
    
    
};

IB.user.search = function()
{
    var search = $.trim($('form.search .q').val());
    
    if(!search) return false;
    
    display('user/search/'+encodeURIComponent(search));
    
    return false;
};