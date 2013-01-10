<div class="page-header">
    <?php be_taxonomy_breadcrumb();?>
	<h1><?php if (is_archive()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        if ($term) {
            echo $term->name;
        }
    }?>
		<span class="filter">
        <form name="filter_form" class="filter_form">
	        <select id="year" name="year">
		        <option selected value="">Browse by year</option>
                <?php
                $yearvalue=$_GET['year'];

                $yearRange = 26;

                $thisYear = date('Y');
                $startYear = ($thisYear - $yearRange);
                $selectYear = $thisYear;

                foreach (range($thisYear, $startYear) as $year) {
                    $selected = "";
                    if($year==$yearvalue) $selected="selected";
                    print '<option '.$selected.' value="' . $year . '">' . $year . '</option>';
                }
                ?>
	        </select>
            <?php wp_dropdown_categories('show_option_none=Browse by topics&orderby=name&name=cat&exclude=68493'); ?>
        </form>
       </span>
	</h1>
</div>