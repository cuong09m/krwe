<?php get_header(); ?>

		<!-- Row for main content area -->
		<div id="content" class="eight columns" role="main">
	
			<div class="post-box">
				<h1>Breaking history:</h1>
				<h1>In-depth special reports</h1>
				
				<p>Welcome to Breaking History: In-Depth Special Reports. Here we exploit the breadth and depth of the Keesing's archive to put today's breaking news into rich historical context. These free articles provide comprehensive context on breaking stories from around the world, tracing the countless links between the places, people, and events of the last century.

Each Breaking History: In-Depth Special Report includes a detailed timeline linked to full coverage from the Keesing's 82-year archive. You can use these features to achieve greater understanding of today’s current events in the light of  82 years of history.</p>
				
				<div class="row">
		<?php get_breaking_hitory_articles('Africa', 'Africa')?>
		<?php get_breaking_hitory_articles('Europe', 'Europe')?>
		
	</div>
	<div class="row">
		<?php get_breaking_hitory_articles('Americas', 'Americas')?>
		<?php get_breaking_hitory_articles('Middle East & Arab World', 'middle-east')?>
	</div>
	<div class="row">
		<?php get_breaking_hitory_articles('International', 'International')?>
		<?php get_breaking_hitory_articles('Asia', 'Asia')?>
	</div>
			</div>

		</div><!-- End Content row -->
		
		<?php get_sidebar(); ?>
		
<?php get_footer(); ?>