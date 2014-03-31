$(function () {
	$('#datalist_head').affix({
		offset: {
			top: $('#datalist_head').position().top + $('#msg').height(),
			bottom: $('#page-wrapper').height() - $('#datalist').position().top - $('#datalist').outerHeight()
		}
	});
});