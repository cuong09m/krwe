<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

<!--[if lt IE 7]>
<div class="alert">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different
    browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to
    experience this site.
</div><![endif]-->

<?php
// Use Bootstrap's navbar if enabled in config.php
if (current_theme_supports('bootstrap-top-navbar')) {
    get_template_part('templates/header-top-navbar');
} else {
    get_template_part('templates/header');
}
?>
<div id="wrap" class="container" role="document">
<div id="content" class="row">
<?php $results = mss_search_results(); ?>
<aside id="sidebar" class="<?php echo roots_sidebar_class(); ?>" role="complementary">
    <?php
    if ($results['facets']['output']) {
        ?>
		<section class="widget">
			<!--
            <li class="solr_active">
				<ol>
					<?php
                if ($results['facets']['selected']) {
                    foreach ($results['facets']['selected'] as $selectedfacet) {
                        printf("<li><span></span><a href=\"%s\">%s<b>x</b></a></li>", $selectedfacet['removelink'], $selectedfacet['name']);
                    }
                }
                ?>
				</ol>
			</li>
			-->

            <?php
            //if ($results['facets'] && $results['hits'] != 1) {
            foreach ($results['facets'] as $facet) {
                //if (sizeof($facet["items"]) > 1) { #don't display facets with only 1 value
                if (isset($facet['name'])) {
                    if($facet['name']=='Categories') printf("<div class='widget-inner'><h3>Sort by topic</h3></div>\n");
                    elseif($facet['name']=='Nation') printf("<div class='widget-inner'><h3>Sort by country</h3></div>\n");
                    elseif($facet['name']=='Org') printf("<div class='widget-inner'><h3>Sort by organization</h3></div>\n");
                    else printf("<div class='widget-inner'><h3>Sort by %s</h3></div>\n", $facet['name']);
                    mss_print_facet_items($facet["items"], "<div class='widget_body''><ul id='"
                        . $facet['name'] . "_facet'>", "</ul><span class='viewmore expandSalesList" . $facet['name'] . "' >View more</span></div>", "<li>", "</li>", "<li><ol>", "</ol></li>", "<li>", "</li>");
                    printf("</li>\n");
                }
                //}
            }
            //}
            ?>
		</section>
        <?php
    }
    ?>
</aside>

<div id="main" class="<?php echo roots_main_class(); ?>" role="main">
    <?php
    function spanOrder($sort, $order, $thisSpan)
    {
        if ($sort . $order == $thisSpan) {
            return '<span/>';
        }
        return '';
    }
    ?>

	<div>
		<div>
			<div>
                <?php if ($results['qtime']) {
                printf("<label class='solr_response'>Response time: <span id=\"qrytime\">{$results['qtime']}</span> s</label>");
            } ?>

				<form name="searchbox" method="get" id="searchbox" action="" class="form-horizontal">

					<fieldset>
						<legend>SEARCH ARCHIVE</legend>
						<div class="control-group">
							<label class="control-label">All of these words</label>

							<div class="controls"><input id="qrybox" name="s" type="text"
							                             value="<?php echo $results['query'] ?>" class="input-xlarge"/></div>

						</div>
						<div class="control-group">
							<label class="control-label">This exact word or phrase</label>

							<div class="controls"><input id="exactwords" name="exactwords" type="text"
							                             value="<?php echo $results['exactwords'] ?>" class="input-xlarge"/></div>

						</div>

						<div class="control-group">
							<label class="control-label">None of these words</label>

							<div class="controls"><input id="nonewords" name="nonewords" type="text" value="<?php
                            echo $results['nonewords'] ?>" class="input-xlarge"/></div>

						</div>

                        <?php 
                            $selectedTopic=$_GET['selectedtopic'];
                            $selectedNation=$_GET['selectednation'];
                        ?>

						<div class="control-group">
							<label class="control-label">Select topic </label>
							<div class="controls"><?php wp_dropdown_categories('id=selectedtopic&name=selectedtopic&show_option_all=All&class=solr_field&orderby=name&order=ASC&exclude=68493&selected='.$selectedTopic);?></div>
						</div>

                        <div class="control-group">
							<label class="control-label">Select nation </label>
                            <div class="controls"><?php wp_dropdown_categories('taxonomy=nation&show_option_all=All&id=selectednation&name=selectednation&class=solr_field&orderby=name&order=ASC&selected='.$selectedNation);?></div>
						</div>

						<div class="control-group">
							<label class="control-label">Between</label>

							<div class="controls"><input type="text"
							                             value="<?php if (isset($_GET['wpcf-start-date_dt'])) echo $_GET['wpcf-start-date_dt'];?>"
							                             class="datepicker input-medium" name="wpcf-start-date_dt"
							                             id="wpcf-start-date_dt"/> and <input type="text"
							                                                                  value="<?php if (isset($_GET['wpcf-end-date_dt'])) echo $_GET['wpcf-end-date_dt'];?>"
							                                                                  class="datepicker input-medium"
							                                                                  name="wpcf-end-date_dt"
							                                                                  id="wpcf-end-date_dt"/>
							</div>

						</div>

						<div class="control-group">
							<div class="controls"><button type="submit" class="btn">Search</button></div>
						</div>

					</fieldset>
				</form>

                <?php
                if ($results['facets']['selected']) {
                    foreach ($results['facets']['selected'] as $selectedfacet) {
                        printf("<p><span></span><a href=\"%s\">%s&nbsp;<b>x</b></a></p>", $selectedfacet['removelink'], $selectedfacet['name']);
                    }
                }
                ?>


			</div>

            <?php if ($results['dym']) {
            printf("<div class='solr_suggest'>Did you mean: <a href='%s'>%s</a> ?</div>", $results['dym']['link'], $results['dym']['term']);
        } ?>

		</div>

		<div>

			<div class="solr_results_header clearfix">
                <?php if ($results['hits'] && $results['query'] && $results['qtime']) {
                if ($results['firstresult'] === $results['lastresult']) {
                    printf("Displaying result %s of <span id='resultcnt'>%s</span> hits", $results['firstresult'], $results['hits']);
                } else {
                    printf("Displaying results %s-%s of <span id='resultcnt'>%s</span> hits", $results['firstresult'], $results['lastresult'], $results['hits']);
                }
            } ?>


                <?php
                $sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'score';
                $order = (isset($_GET['order'])) ? $_GET['order'] : 'desc';
                ?>
				Sort by: <a href="<?php echo $results['sorting']['scoredesc'] ?>">Relevance<?php echo spanOrder
            ($sort, $order, 'scoredesc');?></a>,
				<a href="<?php echo $results['sorting']['datedesc'] ?>">Newest<?php echo spanOrder($sort,
                    $order, 'datedesc'); ?></a>,
				<a href="<?php echo $results['sorting']['dateasc'] ?>">Oldest<?php echo spanOrder($sort, $order, 'dateasc'); ?></a>
			</div>

			<div class="solr_results">

                <?php if ($results['hits'] === "0") {
                printf("<div class='solr_noresult'>
										<h2>Sorry, no results were found.</h2>
									</div>\n");
            } else {
                foreach ($results['results'] as $result) {
                    $post = get_post($result['id']);
                    ?>
                    <article id="post-<?php echo $result['id']; ?>" <?php post_class('archivearticle'); ?>>
                        <header>
                            <h1><?php echo  get_post_meta($result['id'], 'wpcf-nation', true); ?></h1> <h2><a href="<?php echo $result ['permalink'] . '?fs=1'?>"><?php echo $result ['title']
                                ?></a></h2>
                            <?php echo date("M, Y",get_post_meta($result['id'], 'wpcf-end-date', true)); ?>
                        </header>
                        <div class="entry-summary">
                            <?php echo $result ['teaser']; ?>
                        </div>
                    </article>
                    <?php
                }
            } ?>

                <?php if ($results['pager']) {
                printf("<div class='solr_pages'>");
                $itemlinks = array();
                $pagecnt = 0;
                $pagemax = 10;
                $next = '';
                $prev = '';
                $found = false;
                foreach ($results['pager'] as $pageritm) {
                    if ($pageritm['link']) {
                        if ($found && $next === '') {
                            $next = $pageritm['link'];
                        } else if ($found == false) {
                            $prev = $pageritm['link'];
                        }

                        $itemlinks[] = sprintf("<a href='%s'>%s</a>", $pageritm['link'], $pageritm['page']);
                    } else {
                        $found = true;
                        $itemlinks[] = sprintf("<a class='solr_pages_on' href='%s'>%s</a>", $pageritm['link'], $pageritm['page']);
                    }

                    $pagecnt += 1;
                    if ($pagecnt == $pagemax) {
                        break;
                    }
                }

                if ($prev !== '') {
                    printf("<a href='%s'>Previous</a>", $prev);
                }

                foreach ($itemlinks as $itemlink) {
                    echo $itemlink;
                }

                if ($next !== '') {
                    printf("<a href='%s'>Next</a>", $next);
                }

                printf("</div>\n");
            } ?>


			</div>
		</div>


	</div>

</div>

</div>
<!-- /#content -->
</div>
<!-- /#wrap -->

<?php get_template_part('templates/footer'); ?>

</body>
</html>


