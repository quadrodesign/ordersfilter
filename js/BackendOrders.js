$(function(){
    $('.filter-wrap > .heading').on('click',function(){
        var cnt = $(this).closest('.filter-wrap').find('.filter-list');
        
        if($(this).hasClass('active')){
            $('.heading i').removeClass('darr').addClass('rarr');
            $(this).removeClass('active');
        }else{
            $('.heading i').removeClass('rarr').addClass('darr');
            $(this).addClass('active');
        }
        
        cnt.toggle();
    });
    
    $('body').on('click', '.submit-filter', function(){
        var button = $(this);
        var form = button.closest('form');
        var href = form.serialize();
        var view = $('#s-orders-views li.selected').data('view');
        button.attr('href','#/orders/hash=' + encodeURIComponent(href) + '&view=' + view + '/');
    });
    
    
    
    $('.filter-items .filter-head').on('click',function(){
        $(this).siblings('.filter-params').slideToggle();
        var arr = $(this).children('i');
        if($(this).hasClass('active')){
            arr.removeClass('darr').addClass('rarr');
            $(this).removeClass('active');
        }else{
            arr.removeClass('rarr').addClass('darr');
            $(this).addClass('active');
        }
    });
    
    $( "#from" ).datepicker({
          changeMonth: true,
          dateFormat: 'yy-mm-dd',
          changeYear: true,
          numberOfMonths: 1,
          maxDate: new Date(),
          onClose: function( selectedDate ) {
            $( "#to" ).datepicker( "option", "minDate", selectedDate );
          }
        });
        
    $( "#to" ).datepicker({
      changeMonth: true,
      dateFormat: 'yy-mm-dd',
      changeYear: true,
      numberOfMonths: 1,
      maxDate: new Date(),
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
});

function str_replace(haystack, needle, replacement) { 
	var temp = haystack.split(needle); 
	return temp.join(replacement); 
}