// JavaScript Document
(function($) {
    "use strict";
    
    //calling foundation js
    jQuery(document).foundation();

    $(document).ready(function() {
    
        $(".wgby_exclude_categories").select2({
			placeholder: "+ Categories"
		});

        $(".wgby_exclude_products").select2({
			placeholder: "+ Products"
		});


        function selectItem(target, id) { // refactored this a bit, don't pay attention to this being a function
            var option = $(target).children('[value='+id+']');
            option.detach();
            $(target).append(option).change();
        } 

        function customPreSelect() {
        // let items = $('#selected_items').val().split(',');
        $(".wgby_sort_categories").val('').change();
        initSelect(items);
        }

        function initSelect(items) { // pre-select items
        items.forEach(item => { // iterate through array of items that need to be pre-selected
            let value = $('.wgby_sort_categories option[value='+item+']').text(); // get items inner text
            $('.wgby_sort_categories option[value='+item+']').remove(); // remove current item from DOM
            $('.wgby_sort_categories').append(new Option(value, item, true, true)); // append it, making it selected by default
        });
        }

        $(".wgby_sort_categories").select2({
			placeholder: "+ Categories"
		});

        $('.wgby_sort_categories').on('select2:select', function(e){
            selectItem(e.target, e.params.data.id);
        });

    });

})(jQuery); //jQuery main function ends strict Mode on