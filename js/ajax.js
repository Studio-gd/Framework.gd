$.ajaxSetup({type:'POST',timeout:0});

String.prototype.ajax=function(v,c)
{
    $.ajax(
    {
        url:'/',
        data:'q=ajax&a='+this+'&'+v,
        timeout:120000,
        success:c,
        error:function(x,er,er2)
        {
            dbg('Whoops! An unexpected error has occurred'+er+' '+er2);
            $.isLoading = false;
        }
    })
};

$.getJs = function(s,v)
{$.ajax({url:'/',data:'p=ajax&a='+s+'&'+v,dataType:'script',beforeSend:function(x){x.setRequestHeader('Accept','text/javascript');$.loading()}})};

$.btnLoading = function(e)
{
    var button = $('button[type="submit"]:first',e);
    if(!button.is('.loading'))
    {
        button.addClass('loading').prepend('<img src="/img/loadingBtn.gif"/>');
    }
};
$.btnLoaded = function()
{
    $('img',$('button.loading').removeClass('loading')).remove();
};


$.urlFilter = function(q)
{
    // remove double "/" && trim "/"
    q = q ? q.replace(/\/\//g,'/').replace(/^(\/)+|(\/)+$/g,'') : HOMEPAGE;
    /*
    q=q.replace('__page__',p);
    */
    return q || HOMEPAGE;
};

$.isLoading = false;
display = function(q, isBack)
{
    q = $.urlFilter(q);
    
    if(window['MOBILE'] && q.substr(0,6) !== 'mobile')
    {
        q = 'mobile/'+q;
    }
    
    IB.page++;
    
    IB.hist[IB.page] = q;
    
    if(html5.history)
    {
        if(!isBack) window.history.pushState('', '', '/'+q);
    }
    else
    {
        window.location.hash = q;
    }
    
    scroll(0,0);
    $.isLoading = true;
    
    /*
    if(html5.localstorage && localStorage[q])
    {
        //dbg(q + ' : try to get from cache');
        date = new Date();
        
        if(date.getTime() < localStorage[q+'time'])
        {
            //dbg(q + ' : cache got');
            displaid(localStorage[q],1);
            return;
        }
        
        delete localStorage[q];
        delete localStorage[q+'time'];
        delete localStorage[q+'skin'];
        //dbg(q + ' : ' +localStorage[q])
    }
    */

    $.ajax({url:'/',data:'q=x/'+q,success:displaid});
    $.loading();
    firstLoad =false;
};


displaid = function(d,cachedData)
{
    $.e.div_content.html(d);
    
    IB.init();
            
    $.hideTooltips();
    $.initTooltip();
    
    $.isLoading = false;
    $.stats();
    $.loaded();
    
    /*
    cache = $.getCookie('LS');

    if(html5.localstorage && cachedData!==1 && cache)
    {
        cache*= 1000;
        
        //dbg(IB.hist[IB.page] + ' : record cache');
        date = new Date();
        
        localStorage[IB.hist[IB.page]+'time'] = date.getTime() + cache;
        
        localStorage[IB.hist[IB.page]] = d;
        
        localStorage[IB.hist[IB.page]+'skin'] = $.getCookie('skin');
        
        $.delCookie('LS');
    }
    if(html5.localstorage && cachedData===1)
    {
        if(localStorage[IB.hist[IB.page]+'skin'] !== 'null')
        {
            $.setCookie('skin',localStorage[IB.hist[IB.page]+'skin'],1);
        }
        else
        {
            $.delCookie('skin');
        }
    }
    
    IB.skin();

    */
};


var firstLoad = 1;
$(function()
{
    var h = window.location.hash.substr(1);
    
    var v = decodeURIComponent($.getCookie('h'));
    $.delCookie('h');

    // dbg('h = '+h);
    // dbg('v = '+v);
    
    if(h && v.replace(/\//g,'') !== h.replace(/\//g,''))
    {
        display(h);
    }
    else
    {
        if(html5.history)
        {
            if(v === HOMEPAGE) v = '';

            window.history.replaceState('', '', '/'+v);
        }
        else
        {
            window.location.hash = IB.hist[IB.page] = $.urlFilter(v);
        }
        
        IB.init();
    }
    
    $(document)
    .on('mousedown', 'a[href^="/"]', function(e)
    {
        if(e.which === 2 || e.metaKey || e.ctrlKey)
        {
            return false;
        }
        display($(this).attr('href'));
    })
    .on('click', 'a[href^="/"]:not(.rss,.atom)', function(e)
    {
        if(e.which == 2 || e.metaKey || e.ctrlKey)
        {
            return true;
        }
        return false
    })
    .on('submit', 'form', function()
    {
        var e = $(this);
        
        if($.FreezeForm){return false;}
        $.FreezeForm = 1;
        
        var vars = IB.parseQuery(e.attr('action'));
        var postHtml = '';
        for(var key in vars)
        {
            if(!$('input[name="'+key+'"]',e)[0])
            {
                postHtml+= '<input type="hidden" name="'+key+'" value="'+vars[key]+'"/>';
            }
        }
        if(!$('input[name="p"]',e)[0])
        {
            postHtml+= '<input type="hidden" name="q" value="ajax"/>';
        }
        
        if(postHtml){e.append(postHtml);}
        
        var successForm = function(){$.btnLoaded();$.FreezeForm=false};
        
        var eid = e.attr('id');
        if(eid==='login' || eid==='register')
        {
            successForm = function(){$.FreezeForm=false};
            
            if(eid==='login')
            {
                var pass = e.find('input[type=password]');
                
                var key = 'backdraft82';

                var salt = rot13(base64_encode(e.find('input#username').val()+key));

                var crypt = rand(10,99)+c2sencrypt(salt+rand(10,99)+rot13(base64_encode(rot13(utf8_encode(salt+pass.val()+rand(10,99)))))+rand(10,99),key);

                if(!$('#md5',e)[0])
                {
                    e.append('<input type="hidden" name="md5" id="md5"/>');
                }
                
                $('#md5',e).val(crypt);
                
                //alert(str2hex(e.val()))
                
                pass.val('');
            }
            
        }

        e.ajaxSubmit(
        {
            url:'/',
            dataType:'script',
            type:'POST',
            timeout:0,
            beforeSend:function(xhr){xhr.setRequestHeader('Accept','text/javascript')},
            success:successForm,
            error:function(x,er,er2){dbg('Whoops! An unexpected error has occurred'+er+' '+er2);$.btnLoaded();$.FreezeForm=false}
        });
        
        $.btnLoading(e);
                
        return false;
    });
    
    
    if(html5.history)
    {
        onpopstate = function(event)
        {
            if(!$.isLoading && !firstLoad)
            {
                display(window.location.pathname,1);IB.page--;
            }
        };
    }
    else if(html5.hashchange)
    {
        $(window).bind('hashchange', function()
        {
            if(!$.isLoading)
            {
                display(window.location.hash.substr(1));
            }
        });
    }
    else
    {
        setInterval(function()
        {
            if(!$.isLoading && window.location.hash!=='#'+IB.hist[IB.page])
            {
                display(window.location.hash.substr(1));
            }
        },750);
    }

});


