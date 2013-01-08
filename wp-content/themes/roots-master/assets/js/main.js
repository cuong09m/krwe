/* Author:

 */
jQuery(document).ready(function () {
    jQuery('#mycarousel').jcarousel({
        wrap:'circular'
    });

    jQuery(".filter_form").change(function () {
        this.submit();
    });

    jQuery(".select_category_form").change(function () {
        this.submit();
    });

    jQuery('#Nation_facet li:gt(10)').hide();
    jQuery('.expandSalesListNation').live('click',function() {
        jQuery('#Nation_facet li:gt(10):visible').animate({height: 'toggle'}, 1000, function() {});
        jQuery('#Nation_facet li').not(':visible').animate({height: 'toggle'}, 1000, function() {});
    });

    jQuery('#City_facet li:gt(10)').hide();
    jQuery('.expandSalesListCity').live('click',function() {
        jQuery('#City_facet li:gt(10):visible').animate({height: 'toggle'}, 1000, function() {});
        jQuery('#City_facet li').not(':visible').animate({height: 'toggle'}, 1000, function() {});
    });

    jQuery('#Org_facet li:gt(10)').hide();
    jQuery('.expandSalesListOrg').live('click',function() {
        jQuery('#Org_facet li:gt(10):visible').animate({height: 'toggle'}, 1000, function() {});
        jQuery('#Org_facet li').not(':visible').animate({height: 'toggle'}, 1000, function() {});
    });

    jQuery('#Person_facet li:gt(10)').hide();
    jQuery('.expandSalesListPerson').live('click',function() {
        jQuery('#Person_facet li:gt(10):visible').animate({height: 'toggle'}, 1000, function() {});
        jQuery('#Person_facet li').not(':visible').animate({height: 'toggle'}, 1000, function() {});
    });

    jQuery("#wpcf-start-date_dt").monthpicker({
        dateFormat: "yy-mm",yearRange: 'c-26:c+0'
    });
    jQuery("#wpcf-end-date_dt").monthpicker({
        dateFormat: "yy-mm",yearRange: 'c-26:c+0'
    });
});

jQuery(function ($) {

    $('.circle').mosaic({
        opacity:0.8            //Opacity for overlay (0-1)
    });

    $('.fade').mosaic();

    $('.bar').mosaic({
        animation:'slide'        //fade or slide
    });

    $('.bar2').mosaic({
        animation:'slide'        //fade or slide
    });

    $('.bar3').mosaic({
        animation:'slide', //fade or slide
        anchor_y:'top'        //Vertical anchor position
    });

    $('.cover').mosaic({
        animation:'slide', //fade or slide
        hover_x:'400px'        //Horizontal position on hover
    });

    $('.cover2').mosaic({
        animation:'slide', //fade or slide
        anchor_y:'top', //Vertical anchor position
        hover_y:'80px'        //Vertical position on hover
    });

    $('.cover3').mosaic({
        animation:'slide', //fade or slide
        hover_x:'400px', //Horizontal position on hover
        hover_y:'300px'        //Vertical position on hover
    });

});



