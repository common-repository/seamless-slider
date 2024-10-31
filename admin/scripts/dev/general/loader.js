(function(app) {

    function routeLoadingIndicator( $rootScope,Config,$timeout ) {
        return {
            restrict: 'E',
            templateUrl: Config['templates_dir_url'] + 'loader/index.html',
            link: function(scope, elem, attrs) {

                scope.images_dir_url = Config['images_dir_url'];

                $rootScope.isRouteLoading = true;

                $rootScope.$on('$routeChangeStart', function() {
                    $rootScope.isRouteLoading = true;
                });

                $rootScope.$on('$routeChangeSuccess', function() {

                    if( Config.debug ) {

                        $timeout( function() {

                            $rootScope.isRouteLoading = false;

                        },300 );

                    }
                    else
                        $rootScope.isRouteLoading = false;


                });
            }
        };
    }
    routeLoadingIndicator.$inject = ['$rootScope','Config','$timeout'];

    app.directive('routeLoadingIndicator', routeLoadingIndicator);

}(angular.module('faster-slider')));