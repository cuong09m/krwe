<?php
$is_google_bot = false;
if ((strstr($_SERVER['HTTP_USER_AGENT'], "Googlebot")) == true) {
    $ip = $_SERVER['REMOTE_ADDR'];

    $name = gethostbyaddr($ip);
    $host = gethostbyname($name);
    if (strstr($name, "googlebot.com") == true) {
        if ($ip == $host) {
            $is_google_bot = true;
        }
    }
}
$ref = $_SERVER['HTTP_REFERER'];

if (current_user_can("access_s2member_level1") || $is_google_bot == true || in_category('free') || get_post_type($post)=="breaking_history"  || get_post_type($post)=="daily_history") {
    get_template_part('templates/content', 'single');
} else {
    ?>

<script>
    function unCheckRadio(btngrp)
    {
        if(document.getElementById(btngrp).length) {
            for(var i=0; i < document.getElementById(btngrp).length; i++)
            {
                if( document.getElementById(btngrp)[i].checked == true ) {
                    document.getElementById(btngrp)[i].checked = false;
                }
            }
        }
        else {
            if( document.getElementById(btngrp).checked == true )
                document.getElementById(btngrp).checked = false;
        }
    }

    function processsubmit(){
        if(document.getElementById('selectyear').checked==true) {
            location.href="http://recordofworldevents.com/annual-subscribe/";

        }
        if(document.getElementById('selectmonth').checked==true) {
            location.href="http://recordofworldevents.com/monthly-subscribe/";

        }
    }
</script>
<!-- Row for main content area -->

<div class="span8">
    <h3 style="font-size: 14px"><a href="/">Home</a></h3>

	<?php while (have_posts()) : the_post(); ?>
    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
        <header>
			<span class="nation"><a
                    href="http://recordofworldevents.com/nation/<?php echo get_post_meta($post->ID, 'wpcf-nation', true);?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></span>

            <h1 class="entry-title"><?php the_title(); ?></h1>
			<?php //reverie_entry_meta(); ?>
        </header>
        <div class="entry-content">
			<?php echo excerpt(100); ?>
        </div>
    </article>
	<?php endwhile; // End the loop ?>
    <p>&nbsp</p>

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
    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="subyear"
          style="background: url('<?php echo get_template_directory_uri(); ?>/assets/img/subbg3.png') no-repeat;padding-top: 27px;height: 100px;">
        <input type="hidden" name="cmd" value="_s-xclick"> <input
            type="hidden" name="hosted_button_id" value="VTKD8W7Z3DEPE"> <input
            type="radio" id="selectyear" name="options"
            onclick="unCheckRadio(selectmonth)"/> <span
            style="color: #FFF; font-size: 21px;">Annual Subscription</span><br/>
        <br/>

        <p style="text-align: center;">
				<span
                        style="font-weight: bold; font-size: 18px; font-style: normal;">12
					month for $197</span><br/> <span>just $16.41/month, save more than
					30%</span>
        </p>
    </form>

    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr"
          method="post" id="submonth"
          style="background: #939598; padding-top: 10px;margin-top: 8px; height: 39px; width: 280px;
		      margin-bottom: 60px;">
        <input type="hidden" name="cmd" value="_s-xclick"> <input
            type="hidden" name="hosted_button_id" value="8PCXDKP7BZK88"> <input
            type="radio" id="selectmonth" name="options"
            onclick="unCheckRadio(selectyear)"/> <span
            style="color: #FFF; font-size: 21px;">Monthly Subscription</span><br/>
        <br/>

        <p style="text-align: center;">
            <span style="font-size: 18px; font-style: normal;">Just <strong>$24.99</strong></span>
        </p>
    </form>

    <button onclick="processsubmit();"
            style="background: url('<?php echo get_template_directory_uri(); ?>/assets/img/subbg4.png') no-repeat;height: 36px; width: 286px;border: none;color:#FFF; font-size:18px;padding-bottom: 5px;cursor: pointer;">
        Get
        Keesing's Now
    </button>
</aside>
<!-- End Content row -->
<?php } ?>
