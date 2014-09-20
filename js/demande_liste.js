$(document).ready(function(){

	$('.show').css('display', 'block');
	$('.show2').css('display', 'block');
	$('.hide').css('display', 'none');
	$('.hide2').css('display', 'none');

	$('.show').click(function() {
		$('.show').slideUp(800);
		$('.hide').slideDown(800);
		return false;
	});
	$('.show2').click(function() {
		$('.show2').slideUp(800);
		$('.hide2').slideDown(800);
		return false;
	});
});