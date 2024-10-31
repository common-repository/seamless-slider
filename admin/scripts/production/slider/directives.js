(function(app,_SS) {

    function SampleSlider( $rootScope,Config,$timeout ) {
        return {
            restrict: 'E',
            scope:{
                effect:'='
            },
            templateUrl: Config['templates_dir_url'] + 'slider/sample_slider.html',
            link: function(scope, elem, attrs) {

                _SS.sliders = [];

                var images_dir_url = Config['images_dir_url'] + 'sample-slides/';

                _SS.fakeData = {
                    options: {
                        styles: {
                            width:'400px',
                            height:'250px',
                            transitionDuration:'500ms'
                        },
                        settings: {
                            auto_slideshow: true,
                            delay: 4000,
                            stop_on_hover: true
                        },
                        arrows: {
                            show: false
                        },
                        bullets: {
                            show: false
                        },
                        effect: scope.effect
                    },
                    images:[
                        {
                            url: images_dir_url + '1.png',
                            alt:''
                        },
                        {
                            url: images_dir_url + '2.png',
                            alt:''
                        },
                        {
                            url: images_dir_url + '3.png',
                            alt:''
                        },
                        {
                            url: images_dir_url + '4.png',
                            alt:''
                        },
                        {
                            url: images_dir_url + '5.png',
                            alt:''
                        }
                    ]

                };

                _SS.init( JSON.stringify( Config ),JSON.stringify( { alias: 'sample_slider' } ) );

                var slider = _SS.getSlider('sample_slider');

                $timeout( slider.nextSlide.bind( slider ),1500 );

                scope.$watch( 'effect' ,function( newValue,oldValue ) {

                    console.log( newValue,oldValue );

                    if( +newValue !== +oldValue ) {

                        elem.find('.ss-iw').removeClass( Config['effects'][ oldValue ] )
                            .addClass( Config['effects'][ newValue ] );

                        $timeout( slider.nextSlide.bind( slider ),100 );

                    }

                });

            }
        };
    }
    SampleSlider.$inject = ['$rootScope','Config','$timeout'];

    app.directive('sampleSlider', SampleSlider);

}(angular.module('faster-slider'),_SS));