function onSubmit(token) {
	document.getElementById("contact-form").submit();
}

$(document).ready(function() {
	
	$(window).scroll(function () {
        if ($(this).scrollTop() > 300) 
		{
            $('.enhaut').fadeIn("slow");
		} 
		else 
		{
            $('.enhaut').fadeOut("slow");
		}
	});
	
	$('.ssmenu').click(function() {
		$(this).siblings().toggle();
	});
});
