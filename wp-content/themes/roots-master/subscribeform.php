<?php
/*
 * Template Name: SubscribeForm
 */
?>
<!-- Row for main content area -->

    <div class="span8">
	    <h3 style="font-size: 14px"><a href="/">Home</a></h3>

		<h2 style="font-size: 26px; color: #EF4123;">Subscribe Now</h2>

		<p style="font-weight: bold; text-align: justify;">Stay better informed
			by reading the single most comprehensive publication on vital world
			events each month. Get to a higher level of understanding by exploring
			the historical connections and evolution of events with our 25 year
			archive.</p>


    <div class="span4">
		<p style="font-style: italic;">Subscribers get:</p>

		<p style="text-align: center;">
			<span style="font-size: 18px">Keesing’s Record of World Events each
				month in your choice of ebook format</span><br/> <span
				style="font-style: italic; text-align: center; font-family: 11px;">The
				single most thorough review of the world’s political, social and
				economic events. Published monthly.</span>
		</p>
        <p>&nbsp;</p>

		<p style="text-align: center; font-size: 18px">Full, unlimited access
			to the all the articles in our archive back to January 1, 1987</p>

		<p style="font-style: italic; text-align: center; font-family: 11px;">Putting
			today’s news into historical context makes it more valuable to you.</p>

		<p>&nbsp;</p>
		<p>&nbsp;</p>
        <p>&nbsp;</p>

		<p style="text-align: center; font-size: 18px">Access to exclusive,
			subscriber only online features and special reports</p>
	</div>
	<div class="span4">
		<p>&nbsp;</p>
		<ul style="list-style-position: outside;">
			<li>More than 140 concise articles each month, packed with
				information
			</li>
			<li>Thoroughly researched and complete so you don’t miss any
				important world events.
			</li>
			<li>All articles hyperlinked to our 25 year online archive
				delivering instant historical context.
			</li>
		</ul>
		<br/>
		<ul style="list-style-position: outside;">
			<li>More than 30,000 accurate articles covering political, social and
				economic events from every part of the world.
			</li>
			<li>Robust advanced search options let you explore events and find
				their historical connections in virtually endless combinations
			</li>
			<li>Extensive linking of articles opens the door for you to explore
				the paths of historical development of today’s top world news events
			</li>

		</ul>
		<br/>
		<ul style="list-style-position: outside;">
			<li><strong>Breaking History</strong> explores the historical context
				of today’s breaking news events by providing excerpts and links to
				relevant historical information.
			</li>
			<li><strong>Breaking History in-depth special reports</strong> are
				issued two times per month with exclusive articles detailing the
				immediate and historical context of a recent major event. Each
				report includes a lengthy timeline of related events linked to
				stories in our archive.
			</li>
		</ul>
	</div>
    </div>

    <aside id="sidebar" class="span4" role="complementary">
        <div class="sidebar-box"
             style="text-align: right; padding-top: 15px; font-weight: bold; height: 160px;">
            <a href="http://recordofworldevents.com/wp-login.php">Subscriber Login</a>
        </div>
        <div class="sidebar-box">
		    <?php while (have_posts()) : the_post(); ?>
		    <?php the_content(); ?>
		    <?php endwhile; // End the loop ?>
        </div>
    </aside>

<!-- End Content row -->
