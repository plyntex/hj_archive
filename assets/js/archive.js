(function($)
{
    $(document).ready(function(){
        $('#hjarchive > ul > li > span').click(function(){
			$(this).next('ul').slideToggle('fast');
            console.log('a');
		});
			
	});
})(jQuery);