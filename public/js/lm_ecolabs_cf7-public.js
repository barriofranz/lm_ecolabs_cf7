(function( $ ) {
	'use strict';

	$(document).on('submit', '.adminform', function(e){
		$('.adminoverlays').show();

	});




	$(document).on('submit', '#filterform', function(e){
		e.preventDefault();
	});
	$(document).on('click', '#fb_filter_submit_btn', function(e){
		loadLeadListTable(1);
	});
	$(document).on('click', '.lm_ecolabs_cg7_maindiv .pagination .page-link', function(e){
		e.preventDefault();
		var page = $(this).attr('data-page');
		var pageitem = $(this).parents('.page-item');

		if ( $(pageitem).hasClass('active') ) {
			return;
		}
		var datatable = $(this).parents('.tablediv').attr('data-table');

		if ( datatable == 'leadlist') {
			loadLeadListTable(page);
		}

	});
	$(document).on('click', '.fb_filter_export_btn', function(e){
		var url = $('#exporturl').val() + "&" + $('#filterform').serialize();
		window.open(url, '_blank');
	});
	function loadLeadListTable(page)
	{
		$('.leadlist-table').html('<div class="loadertd md12"><div class="loader md12"></div></div>');
		var formdata = $('#filterform').serialize();
		var request = $.ajax({
			url: ajaxArr.ajaxDatasource,
			type: 'POST',
			data: 'ajax=1&action=getLeadList' +
			"&page=" + page +
			"&" + formdata,
			dataType: "html"
		});

		request.done(function(response) {
			$('.leadlist-table').html(response);
		});
		request.fail(function(response) {

		});
	}

	$(document).on('click', '.seemore', function(e){
		var dataval = $(this).attr('data-val');
		if (dataval == '0') {
			$(this).siblings('.fullDetails').show();
			$(this).siblings('.partialDetails').hide();
			$(this).text('less...').attr('data-val', 1);
		} else {
			$(this).siblings('.fullDetails').hide();
			$(this).siblings('.partialDetails').show();
			$(this).text('See more...').attr('data-val', 0);
		}

	});

})( jQuery );
