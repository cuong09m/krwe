/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @url http://www.codeflavors.com/featured-articles-pro/
 * @version 2.4
 */
(function($){
	$(document).ready(function(){
		
		// get already included items in list
		var in_parent = $(FA_parent_item+' li', window.parent.document);
		// count items - will be used later for settings order
		var items_no = in_parent.length;
		// uncheck all checkboxes
		$('input[name="display_content[]"]').attr('checked', false);
		// check in frame the elements set in parent window list
		$.each(in_parent, function(i, e){
			var id = $(this).attr('id').replace(FA_item_id_prefix, '');
			$('#content_'+id).attr('checked', true);
		})
		
		var counter = in_parent.length;
		if( counter > 0 ){
			counter--;
		}
		
		$('input[name="item_id[]"]').click(function(){
			var id = $(this).val();
			
			if( $(this).is(':checked') ){
				counter++;
				var lbl = $('#label_content_'+id);				
				
				var list = '<li id="'+FA_item_id_prefix+id+'">'+
								$(lbl).html()+
								'<input type="hidden" name="'+FA_fields_prefix+'[]" value="'+id+'" />'+
								'<a class="remove_item" href="#">&nbsp;</a>'+
							'</li>';
				$(FA_parent_item, window.parent.document).append(list);	
				
			}else{
				counter--;
				if( counter < 0 )
					counter = 0;
				$('#'+FA_item_id_prefix+id, window.parent.document).remove();
			}			
		});
		
		$('#fa_select_all').click(function(){
			$('input[name="item_id[]"]').trigger('click');
		})
		
		$('#close_window').click(function(){
			// close the dialog window
			var e = window.parent.jQuery(window.parent.window.FA_opened_dialog).dialog('close');				
		})				
	})			
})(jQuery)