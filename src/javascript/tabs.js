(function($) {
$(function() {

	$('ul.tabs').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
	})



	$('ul.tabs2').on('click', 'li:not(.current2)', function() {
		$(this).addClass('current2').siblings().removeClass('current2')
			.parents('div.section2').find('div.box2').eq($(this).index()).fadeIn(150).siblings('div.box2').hide();
	})

})
})(jQuery)