;(function ($) {
    $(document).ready(function () {



    });

    elementor.hooks.addAction("panel/open_editor/widget/PricingWidget", function (panel, model, view) {
        $("input:hidden[value='style_select_hidden']").parents('.elementor-control').prev().find('select').on('change', function () {
            if ('blue' == $(this).val()) {
                $("input:hidden[value='items_hidden_selector']").parents(".elementor-control").prev().show();
            } else {
                $("input:hidden[value='items_hidden_selector']").parents(".elementor-control").prev().hide();
            }
        });

        if ('blue' == $("input:hidden[value='style_select_hidden']").parents('.elementor-control').prev().find('select').val()) {
            $("input:hidden[value='items_hidden_selector']").parents(".elementor-control").prev().show();
        } else {
            $("input:hidden[value='items_hidden_selector']").parents(".elementor-control").prev().hide();
        }


    });

    
})(jQuery);
