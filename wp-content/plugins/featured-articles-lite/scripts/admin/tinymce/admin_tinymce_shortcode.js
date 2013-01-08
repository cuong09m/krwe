/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @url http://www.codeflavors.com/featured-articles-pro/
 * @version 2.4
 */
var FA_DIALOG_WIN = false;

(function() {
	tinymce.PluginManager.requireLangPack('fa_shortcode');
	tinymce.create('tinymce.plugins.FaLitePlugin', {
		init : function(ed, url) {
			
			// Register the command
			ed.addCommand('mceFALite', function() {
				FA_DIALOG_WIN.dialog('open');
			});

			// Register button
			ed.addButton('fa_shortcode', {
				title : 'fa_shortcode.title',
				cmd : 'mceFALite',
				class: 'FA_dialog',
				url:'http://www.google.com',
				image : url + '/images/ico.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('example', n.nodeName == 'IMG');
			});
		},

		createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
				longname : 'Featured Articles for WordPress TinyMCE Button',
				author : 'CodeFlavors',
				authorurl : 'http://www.codeflavors.com',
				infourl : 'http://www.codeflavors.com',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('fa_shortcode', tinymce.plugins.FaLitePlugin);
})();

;(function($){	
	var FAelId = 'FA_slideshows_list';
	$(document).ready(function(){		
		$('body').append('<div id="'+FAelId+'"></div>');
		var url = FA_slideshows_page+'&fa_inline=true&noheader=true';		
		var dialog = $('#'+FAelId).dialog({
			'autoOpen':false,
			'width':900,
			'height':750,
			'maxWidth':900,
			'maxHeight':750,
			'minWidth':900,
			'minHeight':750,
			'modal':true,
			'dialogClass':'wp-dialog',
			'title':'',
			'resizable':true,
			'open':function(ui){
				$(ui.target).css({'overflow':'hidden'}).append('<iframe src="'+url+'" frameborder="0" width="100%" height="100%"></iframe>');
				
			},
			'close':function(ui){
				$(ui.target).empty();
			}
		})		
		FA_DIALOG_WIN = dialog;		
	});	
})(jQuery);