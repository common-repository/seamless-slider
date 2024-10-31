(function( app,$ ) {

    function PostBox( Config ) {
        return {
            restrict: 'E',
            scope: {
                contentUrl: '=',
                closed: '=',
                title: '@',
                column: '=',
                useAlias: '@'
            },
            templateUrl: Config['templates_dir_url'] + 'post-box/index.html',
            controller: function( $scope ) {

                $scope.findAlias = function() {

                    if( 'undefined' !== typeof $scope.$parent ) {

                        if( 'undefined' !== typeof $scope.$parent[ $scope.useAlias ] )
                            return $scope.$parent[ $scope.useAlias ];

                        $scope.findAlias();

                    }
                    return false;

                };

                if( 'undefined' !== typeof $scope.useAlias )
                    $scope[ $scope.useAlias ] =  $scope.findAlias();


            },
            link: function(scope, elem, attrs) {



                console.log( scope );

                $(elem).find('.handlediv').on('click', function() {

                    $(this).parent().toggleClass('closed');

                });

            }
        };
    }
    PostBox.$inject = ['Config'];

    app.directive('postBox', PostBox);

}( angular.module('faster-slider'),jQuery ));