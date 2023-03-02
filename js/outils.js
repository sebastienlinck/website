function onSubmit(token) {
	document.getElementById("contact-form").submit();
}

window.addEventListener('scroll', event => {
	if (document.documentElement.scrollTop > 300) {
		document.querySelector('.enhaut').classList.add('visible');
	} 
	else {
		document.querySelector('.enhaut').classList.remove('visible');
	}
});

document.querySelectorAll('.expand').forEach(item => {
	item.addEventListener('click', event => {
		item.nextElementSibling.classList.toggle('visible');
	})
})