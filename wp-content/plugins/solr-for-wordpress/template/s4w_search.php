<?php
/*
 * Template Name: Search
 */
?>

<?php get_header(); ?>
<link rel="stylesheet"
	href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />

<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
<script
	src="<?php echo plugins_url('solr-for-wordpress/template/jquery.ui.monthpicker.js')?>"></script>
<script
	src="<?php echo plugins_url('solr-for-wordpress/template/jquery.coolfieldset.js')?>"></script>
<script language="javascript" type="text/javascript">
    $(function() {
    	jQuery("#wpcf-start-date_dt").monthpicker({
			showOn: "both",
			dateFormat: "yy-mm"
    	});
    	jQuery("#wpcf-end-date_dt").monthpicker({
			showOn: "both",
			dateFormat: "yy-mm"
    	});
    });


    </script>

<div class="solr clearfix">
	
<?php
$results = s4w_search_results ();
if (! isset ( $results ['results'] ) || $results ['results'] === NULL) {
	?>
	<script language="javascript" type="text/javascript">
	    $(function() {
	    	$('#advance_search').coolfieldset();
	    });
    </script>
    
	<div id="content" class="eight columns" role="main">

		<div class="solr_search">
	<form name="searchbox" method="get" id="searchbox" action="">
				<fieldset id="advance_search" name="Advance search" class="coolfieldset">
					<legend>Advanced search</legend><br>
					<div  class="row">
					<input id="qrybox" name="s" type="text" class="solr_field"
					value="<?php echo $results['query'] ?>" />
			        <?php echo $serverval; ?>
			        <br />
				</div>
					<div  class="row">
						<label for="allwords">All of these words</label> <input id="allwords" name="allwords" type="text" class="solr_field"
					value="<?php echo $results['allwords'] ?>" />
					</div>
					<div  class="row">
						<label for="allwords">This exact word or phrase</label> <input id="exactwords" name="exactwords" type="text" class="solr_field"
					value="<?php echo $results['exactwords'] ?>" />
					</div>
					<div  class="row">
						<label for="anywords">Any of these words</label> <input id="anywords" name="anywords" type="text" class="solr_field"
					value="<?php echo $results['anywords'] ?>" />
					</div>
					<div  class="row">
						<label for="nonewords">None of these words</label> <input id="nonewords" name="nonewords" type="text" class="solr_field"
					value="<?php echo $results['nonewords'] ?>" />
					</div>
					<div  class="row">
						<label for="wpcf-start-date_dt">Between</label> <input type="text"
							value="<?php if(isset($_GET['wpcf-start-date_dt'])) echo $_GET['wpcf-start-date_dt'];?>"
							class="datepicker solr_field" name="wpcf-start-date_dt"
							id="wpcf-start-date_dt" /> and <input type="text"
							value="<?php if(isset($_GET['wpcf-end-date_dt'])) echo $_GET['wpcf-end-date_dt'];?>"
							class="datepicker solr_field" name="wpcf-end-date_dt"
							id="wpcf-end-date_dt" />
					</div>
					<div  class="row">
						<label for="topicid">Select topic </label><?php wp_dropdown_categories('id=selectedtopic&name=selectedtopic&show_option_all=All&class=solr_field&orderby=name&order=ASC');?>
					</div>
					<div  class="row">
						<label for="topicid">Select nation </label><?php wp_dropdown_categories('taxonomy=nation&show_option_all=All&id=selectednation&name=selectednation&class=solr_field&orderby=name&order=ASC');?>
					</div>
                    <div  class="row">
                        <input id="searchbtn" type="submit" value="Search" class="right"/>
                    </div>
                </fieldset>
				<input type="hidden" id="fq" name="fq"/>
			</form>
			</div>
			</div>
	<?php
} else {
	?>
	<script language="javascript" type="text/javascript">
	    $(function() {
	    	$('#advance_search').coolfieldset({<?php if($results['allwords'] || $results['exactwords'] || $results['anywords'] || $results['nonewords']) echo 'collapsed:false'; else echo 'collapsed:true'?>});

            jQuery('#Nation_taxonomy_facet li:gt(20)').hide();
            jQuery('.expandSalesListNation_taxonomy').live('click',function() {
                jQuery('#Nation_taxonomy_facet li:gt(20):visible').animate({height: 'toggle'}, 1000, function() {});
                jQuery('#Nation_taxonomy_facet li').not(':visible').animate({height: 'toggle'}, 1000, function() {});
            });

            jQuery('#Person_taxonomy_facet li:gt(20)').hide();
            jQuery('.expandSalesListPerson_taxonomy').live('click',function() {
                jQuery('#Person_taxonomy_facet li:gt(20):visible').animate({height: 'toggle'}, 1000, function() {});
                jQuery('#Person_taxonomy_facet li').not(':visible').animate({height: 'toggle'}, 1000, function() {});
            });
	    });
    </script>
			<div id="content" class="eight columns" role="main">

		<div class="solr_search">
		    <?php
	
if ($results ['qtime']) {
		printf ( "<label class='solr_response'>Response time: <span id=\"qrytime\">{$results['qtime']}</span> s</label>" );
	}
	
	// if server id has been defined keep hold of it
	$server = $_GET ['server'];
	if ($server) {
		$serverval = '<input name="server" type="hidden" value="' . $server . '" />';
	}
	
	?>

            <form name="searchbox" method="get" id="searchbox" action="">
				
				<div  class="row">
					<input id="qrybox" name="s" type="text" class="solr_field"
					value="<?php echo $results['query'] ?>" />
			        <?php echo $serverval; ?> <br />
				</div>
				<fieldset id="advance_search" name="Advance search" class="coolfieldset">
					<legend>Advanced search</legend><br>
					<div  class="row">
						<label for="allwords">All of these words</label> <input id="allwords" name="allwords" type="text" class="solr_field"
					value="<?php echo $results['allwords'] ?>" />
					</div>
					<div  class="row">
						<label for="allwords">This exact word or phrase</label> <input id="exactwords" name="exactwords" type="text" class="solr_field"
					value="<?php echo $results['exactwords'] ?>" />
					</div>
					<div  class="row">
						<label for="anywords">Any of these words</label> <input id="anywords" name="anywords" type="text" class="solr_field"
					value="<?php echo $results['anywords'] ?>" />
					</div>
					<div  class="row">
						<label for="nonewords">None of these words</label> <input id="nonewords" name="nonewords" type="text" class="solr_field"
					value="<?php echo $results['nonewords'] ?>" />
					</div>
					<div  class="row">
						<label for="wpcf-start-date_dt">Between</label> <input type="text"
							value="<?php if(isset($_GET['wpcf-start-date_dt'])) echo $_GET['wpcf-start-date_dt'];?>"
							class="datepicker solr_field" name="wpcf-start-date_dt"
							id="wpcf-start-date_dt" /> and <input type="text"
							value="<?php if(isset($_GET['wpcf-end-date_dt'])) echo $_GET['wpcf-end-date_dt'];?>"
							class="datepicker solr_field" name="wpcf-end-date_dt"
							id="wpcf-end-date_dt" />
					</div>
                    <div  class="row">
                        <label for="topicid">Select topic </label><?php wp_dropdown_categories('id=selectedtopic&name=selectedtopic&show_option_all=All&class=solr_field&orderby=name&order=ASC');?>
                    </div>
                    <div  class="row">
                        <label for="topicid">Select nation </label><?php wp_dropdown_categories('taxonomy=nation&show_option_all=All&id=selectednation&name=selectednation&class=solr_field&orderby=name&order=ASC');?>
                    </div>
					<div  class="row">
						<input id="searchbtn" type="submit" value="Search" class="right"/>
					</div>
				</fieldset>
				
			</form>
		</div>

		<?php
	
if ($results ['dym']) {
		printf ( "<div class='solr_suggest'>Did you mean: <a href='%s'>%s</a> ?</div>", $results ['dym'] ['link'], $results ['dym'] ['term'] );
	}
	?>

				<div class="solr_results_header clearfix">
			<div class="solr_results_headerL">

				<?php
	
if ($results ['hits'] && $results ['query'] && $results ['qtime']) {
		if ($results ['firstresult'] === $results ['lastresult']) {
			printf ( "Displaying result %s of <span id='resultcnt'>%s</span> hits", $results ['firstresult'], $results ['hits'] );
		} else {
			printf ( "Displaying results %s-%s of <span id='resultcnt'>%s</span> hits", $results ['firstresult'], $results ['lastresult'], $results ['hits'] );
		}
	}
	?>

			</div>
			<div class="solr_results_headerR">
				<ol class="solr_sort2">
					<li class="solr_sort_drop"><a
						href="<?php echo $results['sorting']['scoredesc'] ?>">Relevance<span></span></a></li>
					<li><a href="<?php echo $results['sorting']['datedesc'] ?>">Newest</a></li>
					<li><a href="<?php echo $results['sorting']['dateasc'] ?>">Oldest</a></li>
<!-- 					<li><a href="<?php echo $results['sorting']['commentsdesc'] ?>">Most
							Comments</a></li>
					<li><a href="<?php echo $results['sorting']['commentsasc'] ?>">Least
					Comments</a></li> -->
				</ol>
				<div class="solr_sort">Sort by:</div>
			</div>
		</div>

		<div class="solr_results">
			
<?php
	
	if ($results ['hits'] === "0") {
		printf ( "<div class='solr_noresult'>
										<h2>Sorry, no results were found.</h2>
										<h3>Perhaps you mispelled your search query, or need to try using broader search terms.</h3>
										<p>For example, instead of searching for 'Apple iPhone 3.0 3GS', try something simple like 'iPhone'.</p>
									</div>\n" );
	} else {
		printf ( "<ol>\n" );

		foreach ( $results ['results'] as $result ) {
			$post = get_post($result['id']);

			if($post!=null) {
				$result ['permalink'] = $result ['permalink'] . '?fs=1';
				printf ( "<li onclick=\"window.location='%s'\">\n", $result ['permalink'] );
				$nation = str_replace(" ", "-",get_post_meta($post->ID, 'wpcf-nation', true));
				printf ( "<h6><a href='%s'>%s</a> (<span class='nation'><a href='http://recordofworldevents.com/nation/%s'>%s</a></span>)</h6>\n", $result ['permalink'], $result ['title'], $nation, $nation );
				$startdate = date("M, Y",get_post_meta($post->ID, 'wpcf-start-date', true));
				printf ( "<p>%s</p>\n", $result ['teaser']);
				printf ( "<span class='start_date'>%s</span>\n", $startdate);
				printf ( "</li>\n" );
			}
		}
		printf ( "</ol>\n" );
	}
	?>

			<?php
	
if ($results ['pager']) {
		printf ( "<div class='solr_pages'>" );
		$itemlinks = array ();
		$pagecnt = 0;
		$pagemax = 10;
		$next = '';
		$prev = '';
		$found = false;
		foreach ( $results ['pager'] as $pageritm ) {
			if ($pageritm ['link']) {
				if ($found && $next === '') {
					$next = $pageritm ['link'];
				} else if ($found == false) {
					$prev = $pageritm ['link'];
				}
				
				$itemlinks [] = sprintf ( "<a href='%s'>%s</a>", $pageritm ['link'], $pageritm ['page'] );
			} else {
				$found = true;
				$itemlinks [] = sprintf ( "<a class='solr_pages_on' href='%s'>%s</a>", $pageritm ['link'], $pageritm ['page'] );
			}
			
			$pagecnt += 1;
			if ($pagecnt == $pagemax) {
				break;
			}
		}
		
		if ($prev !== '') {
			printf ( "<a href='%s'>Previous</a>", $prev );
		}
		
		foreach ( $itemlinks as $itemlink ) {
			echo $itemlink;
		}
		
		if ($next !== '') {
			printf ( "<a href='%s'>Next</a>", $next );
		}
		
		printf ( "</div>\n" );
	}
	?>


		</div>
	</div>

	<aside id="sidebar" class="four columns" role="complementary">
			<article class="row widget">
				<div class="sidebar-section twelve columns">
					<ul class="solr_facets">
						<li class="solr_active">
							<ol>
								<?php
				
			if ($results ['facets'] ['selected']) {
					foreach ( $results ['facets'] ['selected'] as $selectedfacet ) {
						printf ( "<li><span></span><a href=\"%s\">%s<b>x</b></a></li>", $selectedfacet ['removelink'], $selectedfacet ['name'] );
					}
				}
				?>
							</ol>
								</li>
			
						<?php	
						if ($results ['facets'] && $results ['hits'] != 1) {
							foreach ( $results ['facets'] as $facet ) {
								if (sizeof ( $facet ["items"] ) > 1) { // on't display facets with only 1
								                                   // value
									if($facet['name']=='Categories') printf ( "<li>\n<h2>Topics</h2>\n");
									elseif($facet['name']=='Nation_taxonomy') printf ( "<li>\n<h2>Countries</h2>\n");
									elseif($facet['name']=='Person_taxonomy') printf ( "<li>\n<h2>People</h2>\n");
									elseif($facet['name']=='Reg_taxonomy') continue;
									elseif($facet['name']=='Volumeid_taxonomy') continue;
									else printf ( "<li>\n<h2>%s</h2>\n", $facet ['name'] );
                                    if($facet['name']=='Categories') s4w_print_facet_items ( $facet ["items"], "<ol id='".$facet['name']."_facet'>", "</ol>", "<li>", "</li>", "<li><ol>", "</ol></li>", "<li>", "</li>" );
                                    else s4w_print_facet_items ( $facet ["items"], "<ol id='".$facet['name']."_facet'>", "</ol><div class='expandSalesList".$facet['name']."'>More...</div>", "<li>", "</li>", "<li><ol>", "</ol></li>", "<li>", "</li>" );
									printf ( "</li>\n" );
								}
							}
						} ?>
					</ul>
				</div>
			</article>
		</div>
	</aside>

</div>
<?php 
                
    } 
                get_footer(); ?>
