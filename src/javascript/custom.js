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


	$('.blog-item-comments').on('click', function () {
        $("html, body").animate({ scrollTop: $('#comments').offset().top-40 }, 1000);
    });
    
    /**
     * Likes in blog
     * @return {null} NULL
     */
    $('.blog-item-like').on('click', function () {
        window.likedObj = $(this);
        $.ajax({
            type: 'POST',
            url: '/ajax/like.php',
            data: {element: $(this).data('object'), ib: $(this).data('ib')},
            success: function (result) {
                result = JSON.parse(result);
                if (true === result.plus) {
                    window.likedObj.addClass('active-like');
                } else {
                    window.likedObj.removeClass('active-like');
                }
                window.likedObj.html(' ' + result.val);
            }
        });
    });

    /**
     * Hack for blog container
     */
    $('.container').css('min-height', Math.ceil($('div.blog-item').length/3)*450 + 100);

});