( function( app ) {

    function SliderController ( $route, $routeParams, Config, SliderFactory,$scope,Tools,$sce ) {

        var controller = this;

        angular.extend( this,SliderFactory );

        this.getRootScope = function() {

            return $scope;

        };

        this.templates = {

            general: Config['templates_dir_url'] + 'slider/general.html',
            size: Config['templates_dir_url'] + 'slider/size.html',
            effects: Config['templates_dir_url'] + 'slider/effects.html',
            options: Config['templates_dir_url'] + 'slider/options.html',
            arrows: Config['templates_dir_url'] + 'slider/arrows.html',
            bullets: Config['templates_dir_url'] + 'slider/bullets.html'

        };

        this.template_dir = Config['templates_dir_url'] + 'slider/index.html';

        this.images_dir_url = Config['images_dir_url'];

        this.$route = $route;

        this.$routeParams = $routeParams;

        this.effects = Config.effects;

        this.arrowIcons = Config.icons.arrows.map( function( el ) {  return $sce.trustAsHtml( el.right ); } );

        console.log( this.arrowIcons );

        this.bulletIcons = Config.icons.bullets.map( $sce.trustAsHtml );

        this.on = {
            change: {
                arrowSize: function() {

                    var style = controller.data.options.arrows.style;

                    style['line-height'] = style['font-size'];

                    if( parseInt( style['font-size'] ) > parseInt( style.width ) )
                        style.width = angular.copy( style['font-size'] );

                    if( parseInt( style['font-size'] ) > parseInt( style.height ) )
                        style.height = angular.copy( style['font-size'] );

                }
            }
        };

        this.data = {
            title:'',
            alias:'',
            short_code:'',
            options: {
                styles: {
                    width:'100%',
                    height:'700px',
                    transitionDuration:'500ms'
                },
                settings: {
                    type:0,
                    auto_slideshow: true,
                    delay: 7000,
                    stop_on_hover: true
                },
                arrows: {
                    show: true,
                    icon: '0',
                    style:{
                       'font-size':'40px',
                        'line-height': '40px',
                        width: '60px',
                        height: '60px',
                        color: '#00a0d2',
                        'background-color' : 'transparent'
                    }
                },
                bullets: {
                    show: true,
                    icon: '0',
                    style:{
                        'font-size':'25px',
                        'line-height': '25px',
                        width: '30px',
                        height: '30px',
                        color: '#00a0d2',
                        'background-color' : 'transparent'
                    }
                },
                effect:'0'
            }
        };

        if( $route.current && $route.current.locals.slider )
            this.data = $route.current.locals.slider;


        this.onTitleChange = function() {

            this.data.alias = this.data.title === '' || 'undefined' === typeof this.data.title ? '' : this.data.title.replace(/ /g,"_").toLowerCase();

            this.onAliasChange();

        };

        this.onAliasChange = function() {

            this.data.short_code = this.data.alias === '' ? '' : '[seamless-slider ' + this.data.alias + ']';

        };

    }

    SliderController.$inject = ['$route', '$routeParams','Config','SliderFactory','$scope','Tools','$sce'];

    app.controller( 'SliderController', SliderController );

}( angular.module('faster-slider' ) ) );

