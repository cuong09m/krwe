		</div><!-- End Main row -->

        <footer id="content-info" role="contentinfo" xmlns="http://www.w3.org/1999/html">
			<div class="row">
				<?php dynamic_sidebar("Footer"); ?>
			</div>
			<div class="row">
					<?php wp_nav_menu(array('theme_location' => 'utility_navigation', 'container' => false, 'menu_class' => 'twelve columns footer-nav')); ?>
					<div class="twelve columns">Copyright 1987-2012 Keesing’s Worldwide, LLC | <a href="http://recordofworldevents.com/terms-and-conditions-of-use/">Terms and Conditions of Use</a> |  <a href="http://recordofworldevents.com/terms-of-sale/">Terms of Sale</a> | <a href="http://recordofworldevents.com/privacy-policy/">Privacy Policy</a></div>
			</div>
		</footer>
			
	</div><!-- Container End -->
	
	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
	     chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7]>
		<script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->
	
	<?php wp_footer(); ?>
</body>
</html>