$(document).ready(function(){
	
	$('#rate_1').hover(function(){set_star(1)}, function(){set_star(0)});
	$('#rate_2').hover(function(){set_star(2)}, function(){set_star(0)});
	$('#rate_3').hover(function(){set_star(3)}, function(){set_star(0)});
	$('#rate_4').hover(function(){set_star(4)}, function(){set_star(0)});
	$('#rate_5').hover(function(){set_star(5)}, function(){set_star(0)});
	
	$('#rate_1').click(function(){voter(1); return false;});
	$('#rate_2').click(function(){voter(2); return false;});
	$('#rate_3').click(function(){voter(3); return false;});
	$('#rate_4').click(function(){voter(4); return false;});
	$('#rate_5').click(function(){voter(5); return false;});
});

function set_star(val) {
	if (val == 0)
		$('.stars_full').css('width', $('#stars_def').val());
	else
		$('.stars_full').css('width', val*16);
};

function voter(vote) {
	id = $('#livre_id').val();
	$.getJSON('livre-'+id+'.html?js&vote='+vote, function(data) {
		$('#vote_result').empty().css('display', 'none').append(data['text']).fadeIn(1500);
		$('.stars_full').css('width', data['size']);
		$('#stars_def').val(data['size']);
	});
};

