
IB.box =
{
    protect: false
    //customPosition:false
};

IB.box.show = function(vars, cb)
{
    if(IB.box.protect)
    {
        __display("Sorry you cannot do that while uploading");
        return;
    }
    
    if(!$('#TB_window')[0])
    {
        $('body').append('<div id="TB_window"><div id="TB_border"></div></div>'); //<p class="TB_move"></p>
    }

    var TB_window = $('div#TB_window');

    var url = '/?a='+vars;

    var params = IB.parseQuery(url.replace(/^[^\?]+\??/,''));

    IB.box.width = parseInt(params['width'],10) || 630;

    if(!TB_window.is(':visible'))
    {
        $("#TB_border",TB_window).append('<div id="TB_content" style="width:'+IB.box.width+'px"></div>');
    }
    $.ajax({url:'/',data:'q=ajax&a='+vars,success:function(d)
    {
        $("div#TB_content",TB_window).html('<a class="icon-remove-sign tt" title="'+$.translation.close+'"></a>'+d).css({'width':IB.box.width+'px'});
        TB_window.css({display:"block"});//.draggable({handle:'.TB_move',stop:function(){IB.box.customPosition=1}});
        
        IB.box.position();
        
        //$('div#TB_content textarea:not(.markItUpEditor)').markItUp(myBbcodeSettings);
        $('a.icon-remove-sign,button.btn-danger',TB_window).mousedown(IB.box.rm);
        
        form.init();
        
        $.initTooltip('#TB_window .tt');
        
        var input = $('form input:visible:first',TB_window);
        if(input[0]){input.focus()}else if($('form textarea:first',TB_window)[0]){$('form textarea:first',TB_window).focus()}
        
        
        document.onkeyup = function(e)
        {
            if (e == null) { // ie
                keycode = event.keyCode;
            } else { // mozilla
                keycode = e.which;
            }
            if(keycode == 27){ // close
                IB.box.rm();
            }
        };
        
        if(cb) cb();
        
    }});
    scroll(0,0);
    
/* to protect lightbox
    if(vars.match(/Form_post/))
    {
        IB.box.protect = 1;
    }

*/
};
IB.box.rm = function()
{
    $('div#TB_window').remove();
    document.onkeyup = '';
    IB.box.protect = false;
    $.hideTooltips();
    $(window).unbind('.box');
    //IB.box.customPosition = false;
};
IB.box.position = function(cb)
{
    //if(IB.box.customPosition) return;
    
    var tbw = $("#TB_window");
    
    tbw.css({top:'42%',marginLeft:'-'+parseInt((IB.box.width / 2)+23,10)+'px',width:IB.box.width+'px'});

    if(!($.browser.msie && $.browser.version < 7))
    {
        tbw.css({marginTop:'-'+(tbw.height()/2)+'px'});
    }

    if(tbw.offset().top < 7) // prevent box to go up too far
    {
        tbw.css({'margin-top':'7px',top:0});
    }
    
    if(cb)
    {
        clearInterval(IB.box.positionCallback);

        IB.box.positionCallback = setTimeout(function()
        {
            var inputFocused = tbw.find('input:focus').blur();
            
            inputFocused.focus();

        },300);
    }
    else
    {
        setTimeout(function()
        {
            $(window).unbind('.box').bind('resize.box', function()
            {
                IB.box.position(1);
            });

        },800);
    }
    
    
};
IB.parseQuery = function(query)
{
    var params={};
    if(!query){return {}}
    var pairs=query.split(/[;&]/);
    for(var i=0; i < pairs.length; i++)
    {
        var keyval=pairs[i].split('=');
        if(!keyval || keyval.length!=2){continue;}
        var key=unescape(keyval[0]);
        var val=unescape(keyval[1]);
        val=val.replace(/\+/g, ' ');
        params[key]=val;
    }
    return params;
};