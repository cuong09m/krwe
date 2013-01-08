/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @url http://www.codeflavors.com/featured-articles-pro/
 * @version 2.4
 */
;(function($){
	
	$(document).ready(function(){
		
		$('.fa_shortcode_slideshow').click(function(e){
			e.preventDefault();
			var id = $(this).attr('id').replace('fa_slideshow_', '');
			
			window.parent.send_to_editor('[FA_Lite id="'+id+'"]');
			var e = window.parent.jQuery(window.parent.window.FA_DIALOG_WIN).dialog('close');	
		})
		
	})
	
})(jQuery);