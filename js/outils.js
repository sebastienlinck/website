function onSubmit(token) {
	document.getElementById("contact-form").submit();
}

$(document).ready(function() {
	
	$(window).scroll(function () {
        if ($(this).scrollTop() > 200) 
		{
            $('.enhaut').fadeIn("slow");
		} 
		else 
		{
            $('.enhaut').fadeOut("slow");
		}
	});
	
    $('.enhaut').click(function () {
		$('html, body').animate({scrollTop:0}, 'slow');
		return false;
	});
	
	$('.ssmenu').click(function() {
		$(this).siblings().toggle();
	});
});
