$(document).ready(function () {
	$('.save-buy').on('click', function () {
		localStorage.setItem('save-buy', 'true');
	});

	if ($('.buy-link').length) {
		if (localStorage.getItem('save-buy') == 'true') {
			setTimeout(function () {
				$('.buy-link').click();
			}, 2000);
			localStorage.removeItem('save-buy');
		}
	}
});