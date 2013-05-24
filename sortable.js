jQuery(document).ready(function() {
    jQuery("#abc_product_categories_sortable").sortable({
        cursor: 'move'
    });

    jQuery('#abc_product_categories_sortable').disableSelection();
    jQuery('#abc_product_categories_sortable li').disableSelection();
});
