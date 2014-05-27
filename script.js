function ajaxTrCall(e) {
	$.ajax({
		type: 'POST',
		url: document.URL,
		dataType: 'json',
		data: { file: $(e).find('a').attr('href') }
	})
	 .success(function(data) {
	  console.log(data);
		$(e).find('td.last_mod').html(data.last_mod);
		$(e).find('td.size').html(data.size);
		if (data.is_being_written) setTimeout(ajaxTrCall, 5000, e);
		else $(e).find('img.loading').attr('src', 'icons/download.png');
	});
}

$(function() {
    $('tr.being_written').each(function(i, e) {
		$(this).find('a').after('<img class="loading" src="icons/loading.gif" />');
		ajaxTrCall(e);
	});
});
