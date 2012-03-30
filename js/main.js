
$.translation = {};

$(function()
{
    $(window).bind('resize.fluid',IB.fluid.apply);
    setTimeout(IB.fluid.apply,300);
    
    $.initTooltip('.tt');

    
    /* user conf
    if(LOGGEDIN)
    {
        'Conf_get'.ajax('',function(d)
        {
             var conf = d.split('||');
        });
    }
    */
    
    
    $.translation.close   = 'close';
    $.translation.loading = 'loading';
    
    'Translate_js'.ajax('',function(d)
    {
        var txt = d.split('||');

        $.translation.close   = txt[0];
        $.translation.loading = txt[1];
        
        $('form.search',$.e.div_H).submit(IB.user.search).find('div.sf').mousedown(function()
        {
            $(this).parent().submit();
        });
        
    });
    
    $.stats();

    $('#logout').mousedown(function()
    {
        'User_logout'.ajax('',function()
        {
            $.delCookie('ib');
            refresh();
        });
    });

    //document.body.style.display = 'block';
    
    if(LOGGEDIN)
    {
        
    }
    else /* unlogged sign link */
    {
        
        $('#signin').mousedown(function()
        {
            IB.box.show('View_get&name=user/form/login&width=350');
        });
        $('#signup').mousedown(function()
        {
            IB.box.show('View_get&name=user/form/register&width=350');
        });
        
    }
    
    
    $('#flag_menu a').mousedown(function()
    {
        $.lang($(this).attr('id').replace('l_',''));
        
    }).click(function(){return false});
    

/*    
    if($.browser.msie)
    {
        $('body').addClass('ie');
    }
*/
/*   
    var kkeys = [], keys = "67,79,78,78,69,88,73,79,78";
    $(document).keydown(function(e){
      kkeys.push(e.keyCode);
      if(kkeys.toString().indexOf(keys) >= 0){
        $(document).unbind('keydown',arguments.callee);
        IB.box.show('Form_login&width=350');
      }
    });
*/    

    $(document.body).on('click', 'a.newWindow', function()
    {
        window.open($(this).attr('href'));
        return false;
    });


    CFInstall.check({
     mode: "overlay",
     destination: "http://www.waikiki.com"
   });

    //console.log('Studio.gd - graphisme & développement web');
});

$.stats = function()
{
    if(window['_gaq']) _gaq.push(['_trackPageview', '/'+IB.hist[IB.page]]);
};


$.lang = function(lang)
{
    $.loading();
    'Lang_set'.ajax('lang='+lang,refresh);
};
$.loading = function(){$.e.div_loading.show()};
$.loaded  = function(){$.e.div_loading.hide()};

$.displayMessage = function(v)
{
    $.noticeAdd({text:v});
    return $;
};

$.initTooltip = function(s)
{
    $(s || '#C .tt').tipsy();
};

function __(s)
{
    'Translate_it'.ajax('s='+s,function(d){return d});
}
