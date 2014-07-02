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

	$('.play-btn').fadeIn();
	$('.detail-photo, .play-btn').on('click', function () {
		$('.detail-photo, .play-btn, .more-photo').hide();
		$('.detail-video, .back-to-photos').fadeIn();
		$('.slider').addClass('black');
	});

	$('.back-to-photos').on('click', function () {
		$('.detail-photo, .play-btn, .more-photo').show();
		$('.detail-video, .back-to-photos').hide();
		$('.slider').removeClass('black');
	});

	$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});

});