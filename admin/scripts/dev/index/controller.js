( function( app ) {

    function IndexController ( $location,$route, $routeParams,$rootScope,Config ) {

        this.sliders = false;

        if( $route.current ) {

            this.sliders = $route.current.locals.sliders;
            $rootScope.slidersLength = this.sliders.length;

        }

        this.$route = $route;

        this.$routeParams = $routeParams;

        this.isActiveMenu = function( path ) {

            return path === $location.path();

        };

        this.templates = {
            howto: {
                general: Config['templates_dir_url'] + 'howtouse/general.html',
                note: Config['templates_dir_url'] + 'howtouse/note.html',
                api: Config['templates_dir_url'] + 'howtouse/api.html'
            }
        }

    }

    IndexController.$inject = ['$location','$route', '$routeParams','$rootScope','Config'];

    app.controller( 'IndexController', IndexController );

}( angular.module('faster-slider' ) ) );

