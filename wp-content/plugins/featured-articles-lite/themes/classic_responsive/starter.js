/**
 * @package Featured articles PRO - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @url http://www.codeflavors.com/featured-articles-pro/
 * @version 2.4
 */
(function($){
	
	$(window).load(function(){		
		
		$('.FA_overall_container_classic_responsive').FeaturedArticles({
			load: classicTheme_onLoad,
			before: classicTheme_beforeSlide,
			after: classicTheme_afterSlide
		});
		
	})
	
	var classicTheme_onLoad = function(options){
		var self = this,
			slides = self.slides;
		
		this.slidesContainer = $(this).find('.FA_featured_articles');
		
		$.each(slides, function(i, slide){
			var video = $(slide).find('.fa-video');
			if( video.length > 0 ){
				var vidW = $(video).width(),
					vidH = vidW * 0.57;
				$(video).css({'height':vidH});
			}			
			
			var h = $(slide).outerHeight();
			$(slide).data('height', h);
			if( i == self.currentKey ){
				$(self.slidesContainer).stop().animate({'height': h}, {queue:false, duration:200});
			}
		})
		
		// window resize event
		$(window).resize(function(){
			$.each(slides, function(i, slide){
				var video = $(slide).find('.fa-video');
				if( video.length > 0 ){
					var vidW = $(video).width(),
						vidH = vidW * 0.57;
					$(video).css({'height':vidH});
				}
				
				var h = $(slide).outerHeight();
				$(slide).data('height', h);
				if( i == self.currentKey ){
					$(self.slidesContainer).stop().animate({'height': h}, {queue:false, duration:200});
				}
			})
		})		
	}
	// before sliding animation callback
	var classicTheme_beforeSlide = function(d){
		var h = $(d.nextElem).data('height');
		this.slidesContainer.stop().animate({'height': h}, {queue:false, duration:200});
	};
	// after sliding animation callback
	var classicTheme_afterSlide = function(d){
		
	};	
	
})(jQuery)