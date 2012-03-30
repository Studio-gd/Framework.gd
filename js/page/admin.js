IB.admin = {fluid:{},e:{}};

IB.admin.init = function()
{
    //IB.admin.fluid.init();
    
    var packed = $('#packed');

    if(packed[0])
    {
        'Admin_isPacked'.ajax('',function(d)
        {
            packed.html(d);
        });
    }
    


    $('div.adminScaffold a.btn-success').click(function()
    {
        var html = $('div.adminScaffold div.fieldGroup:first').html();

        $('div.adminScaffold div.fieldGroup:last').after('<div class="fieldGroup">'+html+'</div>');

        $('div.adminScaffold div.fieldGroup:last input').val('');
    });

};

