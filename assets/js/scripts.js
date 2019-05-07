;(function($){
    $(window).on("elementor/frontend/init",function(){
        elementorFrontend.hooks.addAction('frontend/element_ready/ProgressbarWidget.default',function($scope, $){
            // $(".progress").each(function(){
                $scope.find(".progress").each(function(){
                    var element = $(this)[0];
                    if(element) {
                        var bar = new ProgressBar.Line(element, {
                            strokeWidth: 4,
                            easing: 'easeInOut',
                            duration: 1400,
                            color: '#FFEA82',
                            trailColor: '#eee',
                            trailWidth: 1,
                            svgStyle: {width: '95%', height: '10px'},
                            text: {
                                style: {
                                    // Text color.
                                    // Default: same as stroke color (options.color)
                                    color: '#999',
                                    position: 'absolute',
                                    right: '0',
                                    top: '0px',
                                    padding: 0,
                                    margin: 0,
                                    transform: null
                                },
                                autoStyleContainer: false
                            },
                            step: (state, bar) => {
                                bar.setText(Math.round(bar.value() * 100) + ' %');
                            }
                        });

                        bar.animate(0.8);  // Number from 0.0 to 1.0
                    }
                });



            // });
        }) ;
    });

    $(document).ready(function(){
        //do something


    });
})(jQuery);