$(function()
{
    $('div.customCheckbox b').live('click', function()
    {
        var e = $('input',$(this).toggleClass('icon-ok').parent().toggleClass('selected'));
        
        e[0].value = e.val()=='0' ? 1 : 0;
        
    });
    $('div.customCheckbox span').live('click', function()
    {
        $(this).prev().click();
    });
    
    $('button.reset').live('click', function()
    {
        if(!confirm('Reset all infos ?'))
        {
            return false;
        }
    });
    
    // CALENDAR 
    $('body').on('focus', 'input.calendar', function()
    {
        var e = $(this);
        
        var dateFormat = $.getParamValue('dateFormat',e); 
        
        var date = $.getParamValue('date',e); 
        
        if(!date) date = '-25y';
        
        if(dateFormat)
        {
            dateFormat = (dateFormat[0]+dateFormat[0]+dateFormat[1]+dateFormat[2]+dateFormat[2]+dateFormat[3]+dateFormat[4]+dateFormat[4]).toLowerCase();
        }
        else
        {
            dateFormat = 'yy-mm-dd';
        }
        
        var d = new Date();
        var Y = parseInt(d.getFullYear(),10)+2;

        e.datepicker(
        {
            changeYear: true,
            yearRange: '1910:'+Y,
            defaultDate: date,
            dateFormat: dateFormat,
            closeText: 'X',
            showAnim: 'slideDown'
        });
    });
    
    
    $('#C').on('click', 'button.btn-danger', IB.goBack);
});

var form = {};

form.init = function()
{
    form.placeholder();
    form.textarea();
    form.autocomplete();
    form.counter();
    form.sortable();
    $('#C form').validate({});
};

form.placeholder = function()
{
    if(!html5.input.placeholder)
    {
        var e = $('input[placeholder]:empty');
        
        e.each(function()
        {
            var input = $(this);
            
            var val = input.attr('placeholder');
            
            input.focusin(function()
            {
                $.clearTxt(this,val);
                
            }).focusout(function()
            {
                $.resetTxt(this,val);
                
            }).val(val);
        });
    
    }
};

form.counter = function()
{
    $('input.counter,textarea.counter').bind('textchange focus', function()
    {
        var counter = $(this).parent().find('.charLeft').show();

        counter.html(parseInt($(this).attr('maxlength'),10) - parseInt($(this).val().length,10));
    });
};

form.textarea = function()
{
    $('textarea',$.e.div_content).elastic();
    var wysihtml5ParserRules = {
    tags: {
      strong: {},
      b:      {},
      i:      {},
      em:     {},
      br:     {},
      p:      {},
      div:    {},
      span:   {},
      ul:     {},
      ol:     {},
      li:     {},
      h1:     {},
      h2:     {},
      h3:     {},
      a:      {
        set_attributes: {
          target: "_blank",
          rel:    "nofollow"
        },
        check_attributes: {
          href:   "url" // important to avoid XSS
        }
      }
    }
  };
    if($('textarea.editor')[0]) $('textarea.editor').wysihtml5({ "parserRules" : wysihtml5ParserRules });
};

form.autocomplete = function()
{
    $('input.autocomplete').each(function()
    {
        var e = $(this);
        
        if(!e.next('[type=hidden]')[0]) return;
        
        var source = e.next('[type=hidden]').val().split(',');
        
        e.autocomplete({
            source: source
        });
    });
};
form.sortable = function()
{
    $('ul.adminList.sortable').sortable(
    {
        update: function()
        {
            //alert($('ul.adminList.sortable').sortable('serialize'));
            var ajaxClass = $(this).attr('id')+'_updateOrder';
            ajaxClass.ajax($(this).sortable('serialize'));
        },
        handle:'.move',
        axis:'y'
    });
};

