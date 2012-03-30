
IB.fluid.apply = function()
{
    IB.fullWidth = $(document).width();

    /*
    
    COMMON RESIZE STUFF
    
    
    */
    
    
    /* CURRENT PAGE RESIZE STUFF */
    if(IB[IB.pageName[0]] && IB[IB.pageName[0]]['fluid'] && IB[IB.pageName[0]]['fluid']['apply'])
    {
        IB[IB.pageName[0]].fluid.apply();
    }
    
    IB.fluid.footer();
    
    IB.lastFullWidth = IB.fullWidth;

};


IB.fluid.footer = function()
{
    var f = $.e.footer.css('margin-top','20px');
    
    var totalHeight = $(window).height();
    
    var contentHeight = $.e.container.height();
    
    if(totalHeight > contentHeight)
    {
        var mt = totalHeight - contentHeight - f.height() - 1;
        
        if(mt < 20) mt = 20;
                
        f.css('margin-top', mt+'px');
    }
};
