
$(document).ready(function(){
	
	// Dialog
	$('#mod_user').dialog({
		autoOpen: false,
		width: 650,
		modal: true,
		buttons: {
			// Mise à jour
			"Mettre à jour": function() {
				$(":button:contains('Mettre à jour')").attr("disabled","d‌​isabled").text("Mise à jour en cours...");
				$.post(
					'admin-livres.html?js',
					$("#mod_user form").serialize(),
					function(data) {
						$("#row_" + $("#i_id").val()).html(data);
						$('#mod_user').dialog("close");
						$(":button:contains('Mise à jour en cours...')").removeAttr("disabled").text("Metre à jour");
						$("#row_" + $("#i_id") + " td").animate({ backgroundColor: "#E1D479" }, 2000).animate({ backgroundColor: "#F6F2EF" }, 2000);
					}
				);
			},
			"Annuler": function() {
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Cocher les cases si modif
	$("#mod_user input[type=text], #mod_user textarea").keyup(function () {
		$("#" + $(this).attr("id") + "_maj").attr("checked", "checked");
	});
	
	$("#mod_user select").change(function () {
		$("#" + $(this).attr("id") + "_maj").attr("checked", "checked");
	});
});

// Get datas
function open_dial(id){
	$.getJSON('admin-livres.html?js&get_livre='+id, function(data) {
		var items = [];
		var dial = true;
		
		$.each(data, function(key, val) {
			if(key == 'error')
				var dial = false;
			
			$("#i_"+key).val(val);
		});
		
		if (dial)
		{
			$("#mod_user input[type=checkbox]").removeAttr("checked");
			$('#mod_user').dialog('open');
		}
	});
	return false;
};

// Delete
function del(id) {
	if (confirm("Voulez-vous vraiment supprimer ce livre ?"))
	{
		$.get('admin-livres.html?js&del='+id, function(data) {
			if (data == 'error') 
				alert('Le livre n\'a pas pu etre supprimé');
			else
				$("#row_" + id).remove();
		});
	}
	return false;
};

// Timestamp
function validate(timestamp, id) {
	$.get('admin-livres.html?js&timestamp='+timestamp, function(data) {
		$('.row').each(function() {
			var this_id = parseInt($(this).attr("id").substr(4));
			if (this_id <= id)
			{
				$(this).removeClass('hl2');
				$('#val_'+this_id).remove();
			}
		});
	});
	
	return false;
};

