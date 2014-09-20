$(document).ready(function(){
	
	if ($('.book_el h4').width() > $('.book_el .etat').width())
		var width = $('.book_el h4').width();
	else
		var width = $('.book_el .etat').width();
	
	$('.book_el').css('width', width + 25);
});