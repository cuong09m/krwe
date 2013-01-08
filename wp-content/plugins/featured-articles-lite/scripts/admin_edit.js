/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */
var FA_opened_dialog = false;
(function($){
	
	$(document).ready(function(){
		
		// start sortables
		$(".FA_sortable").sortable({
			'axis':'y',
			'containment':'parent',
			'cursor':'move',
			'tolerance':'pointer',
			'update': function(event, ui){
				var a = $(this).sortable('toArray');
				$.each(a, function(i, e){
					$('#'+e+' .ord_save').val(i);
				})
			}
		});
		// on mouse over and mouse out, display/hide the remove slider link
		$(".FA_sortable li").live('mouseover', function(){
			$(this).children('.remove_item').css('display', 'inline');
		}).live('mouseout', function(){
			$(this).children('.remove_item').css('display', 'none');
		})
		// remove items from list
		$("a.remove_item").live('click', function(e){
			e.preventDefault();
			$(this).parent().remove();	
		})
		// toggle between different content types
		$('#displayed_content').change(function(){
			$('.FA_content_display').css('display', 'none');
			$('#FA_content_'+$(this).val()).css('display', '');
		})
		
		var dialog_items = $('a.FA_dialog');
		$.each(dialog_items, function(i, e){
			$(this).after('<div style="display:none;" id="FA_dialog_'+i+'"></div>');
			var url = $(this).attr('href');
			var dialog = $('#FA_dialog_'+i).dialog({
				'autoOpen':false,
				'width':900,
				'height':750,
				'maxWidth':900,
				'maxHeight':750,
				'minWidth':900,
				'minHeight':750,
				'modal':true,
				'dialogClass':'wp-dialog',
				'title':$(this).attr('title'),
				'resizable':true,
				'open':function(ui){
					FA_opened_dialog = this;
					$(ui.target).append('<iframe src="'+url+'" frameborder="0" width="100%" height="100%"></iframe>');
				},
				'close':function(ui){
					FA_opened_dialog = false;
					$(ui.target).empty();
				}
			})
			$(this).click(function(e){
				e.preventDefault();
				dialog.dialog('open');
			})
		})
		
		
		$("#FeaturedArticles_settings .FA_number").keyup(function (E) {
            var C = $(this).attr("value");
            if ($(this).hasClass("float")) {
                if (E.keyCode == 190 || E.keyCode == 110) {
                    return
                }
                var D = parseFloat(C)
            } else {
                var D = parseInt(C)
            }
            $(this).attr("value", D ? D : 0)
        });
		
		// thumbnail settings visibility
		$('#thumbnail_display').click(function(e){
			var d = $(this).is(':checked') ? '' : 'none';
			$('div#has_thumbnail').css('display', d);
		})
		
		$('.fa_has_extra[type=checkbox]').click(function(){
			var extra = '#'+($(this).attr('id'))+'_extra';
			if( $(this).is(':checked') ){
				$(extra).fadeIn(200);
			}else{
				$(extra).fadeOut(200);
			}			
		}).each(function(i, e){
			var extra = '#'+($(this).attr('id'))+'_extra';
			if( $(this).is(':checked') ){
				$(extra).show();
			}else{
				$(extra).hide();
			}
		})
		
		// switch image sizes based on source: WordPress sizes or custom image size entered by user
		$('input[name=fa_image_source]').click(function(){
			var elemId = $(this).attr('id');
			switch( elemId ){
				case 'fa_image_source_wp':
					$('#fa_image_source_wp_options').show();
					$('#fa_image_source_custom_options').hide();
				break;
				case 'fa_image_source_custom':
					$('#fa_image_source_wp_options').hide();
					$('#fa_image_source_custom_options').show();
				break;		 
			} 
		})
		
		addCheckboxActions('show_post_author', ['link_post_author']);
		addCheckboxActions('allow_all_tags', ['allowed_tags', 'allowed_tags_note'], true);
	})
	
	// Helper functions
	/** 
	 * Hide various elements based on whether a checkbox is checked or not
	 * @param string checkboxId - is of checkbox to check
	 * @param array elems - array of element ids to hide/show
	 */
	var addCheckboxActions = function( checkboxId, elems, inverse ){
		var d1 = inverse ? 'none' : 'inline',
			d2 = inverse ? 'inline' : 'none';
		var isChecked = $('#'+checkboxId).click(function(){
			var display = $(this).is(':checked') ? d1 : d2;
			$.each(elems, function(i, el){
				$('#'+el).css({'display' : display});
				$('label[for='+el+']').css({'display' : display});
			})
			
		}).is(':checked');
		
		if( !isChecked ){
			$.each(elems, function(i, el){
				$('#'+el).css({'display' : d2});
				$('label[for='+el+']').css({'display' : d2});
			})
		}
	}
	
})(jQuery)