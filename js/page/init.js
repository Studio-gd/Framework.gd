var IB = 
{
    hist:[],
    page:0,
    fluid:{},
    contentWidth:null,
    pageName:[],
    lastFullWidth:0
};

IB.init = function()
{
    IB.setPageName();
    
    var p = IB.pageName;

    if(IB[p[0]])
    {
        IB[p[0]].init();

        if(IB[p[0]][p[1]] && IB[p[0]][p[1]]['init'])
        {
            IB[p[0]][p[1]].init();
        }
    }

    IB.fluid.apply(false);
    setTimeout(function(){$(window).trigger('resize.fluid')},1800);
    setTimeout(function(){$(window).trigger('resize.fluid')},3800);
    setTimeout(function(){$(window).trigger('resize.fluid')},5100);
    
    var t = $('#T'); if(t[0]) document.title = TITLE + t.text();

    form.init();
    
    //IB.getFeeds();

    /*  
    $('nav a.sel').removeClass('sel');
    if($('nav a.'+IB.pageName[0])[0])
    {
        $('nav a.'+IB.pageName[0]).addClass('sel');
    }
    */

    IB.initAdd();
};


IB.setPageName = function()
{
    if(html5.history)
    {
        if($.browser.safari && parseInt($.browser.version,10) < 534)
        {
            var url = IB.hist[IB.page];
            if(!url)
            {
                // from dom
            }
        }
        else
        {
            var url = window.location.pathname.substr(1);
        }
        
        var h = $.urlFilter(url);
    }
    else
    {
        var h = $.urlFilter(document.location.hash.substr(1));
    }
    
    if(h) IB.pageName = h.split('/');
    
    if(!IB.pageName[0]) IB.pageName[0] = h;
};



$(function()
{
    $.e =
    {
        container   : $('div.container:first'),
        div_content : $('#C'),
        div_loading : $('#loading'),
        footer      : $('footer'),
        header      : $('header')
    };
});


/*
this allow to add a "name.js" to page with another name
ex: activity.js is automaticaly apply to activity page (#activity/all) but not user activity (#user/activity)
*/
IB.initAdd = function()
{    
    var add = false;
    
    if(IB.pageName[2] === 'translate')
    {
        add = 'translate';
    }

    if(add && IB[add])
    {
        IB[add].init();
    }
};

IB.hide = function(e)
{
    e.hide(300, function()
    {
        e.remove();
        IB.fluid.footer();
    });
};

IB.goBack = function()
{
    display(IB.hist[--IB.page]);IB.page--;
    return false;
};

/*
IB.getFeeds = function()
{
    var rss = $('a.rss',$.e.div_content);
    
    $('link.rss').remove();
    
    if(rss[0])
    {        
        $('head').append('<link rel="alternate" type="application/rss+xml" title="'+rss.attr('title')+'" href="'+rss.attr('href')+'" class="rss" />');
    }
    
    var atom = $('a.atom',$.e.div_content);
    
    $('link.atom').remove();
    
    if(atom[0])
    {
        $('head').append('<link rel="alternate" type="application/atom+xml" title="'+atom.attr('title')+'" href="'+atom.attr('href')+'" class="atom" />');
    }
};
*/

