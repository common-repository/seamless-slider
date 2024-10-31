( function( app ) {

    function Router( $routeProvider,Config ) {

        $routeProvider
            .when('/', {
                templateUrl: Config['templates_dir_url'] + 'index/index.html',
                controller:'IndexController',
                controllerAs:'_ic',
                resolve: {
                    sliders: function( IndexFactory ) {

                        return IndexFactory.getSliders().then(function( result ) {

                            return  result['data'];

                        });

                    }
                }
            })
            .when('/add_slider', {
                templateUrl: Config['templates_dir_url'] + 'slider/add_slider.html',
                controller:'SliderController',
                controllerAs:'_sc',
                resolve: {}
            })
            .when('/edit_slider/:slider_id', {
                templateUrl: Config['templates_dir_url'] + 'slider/edit_slider.html',
                controller:'SliderController',
                controllerAs:'_sc',
                resolve: {
                    slider: function( $route,SliderFactory ) {

                        var slider_id = $route.current.params['slider_id'];

                        return SliderFactory.getSliderBySliderID( slider_id ).then(function( result ) {

                            return result['data'];

                        });

                    }
                }
            })
            .when('/delete_slider/:slider_id', {
                controller:'SliderController',
                controllerAs:'_sc',
                resolve: {
                    deleteStatus: function( $route,SliderFactory ) {

                        var slider_id = $route.current.params['slider_id'];

                        return SliderFactory.deleteSlider( slider_id );

                    }
                }
            })
            .when('/slides/:slider_id', {
                templateUrl: Config['templates_dir_url'] + 'slides/index.html',
                controller:'SlidesController',
                controllerAs:'_ssc',
                resolve: {
                    slides: function( $route,SlidesFactory ) {

                        var slider_id = $route.current.params['slider_id'];

                        return SlidesFactory.getSlidesBySliderID( slider_id ).then(function( result ) {

                            return  result['data'];

                        });

                    }
                }
            })
            .when('/delete_slide/:slider_id/:slide_id', {
                controller:'SlidesController',
                controllerAs:'_ssc',
                resolve: {
                    deleteStatus: function( $route,SlidesFactory ) {

                        var slider_id = $route.current.params['slider_id'],
                            slide_id = $route.current.params['slide_id'];

                        return SlidesFactory.deleteSlide( slider_id,slide_id );

                    }
                }
            })
            .when('/howtouse', {
                templateUrl: Config['templates_dir_url'] + 'howtouse/index.html'
            });

        return this;
    }

    Router.$inject = ['$routeProvider','Config'];

    app.config( Router );

}( angular.module('faster-slider' ) ) );
