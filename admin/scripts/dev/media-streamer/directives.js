(function(app) {

    function MediaStreamer( Config ) {
        return {
            restrict: 'E',
            scope:{
                mediaStreamer: '=',
                onClose: '='
            },
            controller:'MediaStreamerController',
            controllerAs:'_msc',
            templateUrl: Config['templates_dir_url'] + 'media-streamer/index.html',
            link: function( scope, elem, attrs ) {

                var ctrl = scope['_msc'];

                ctrl.config = scope.mediaStreamer;

                ctrl.callbacks = {

                    onClose: scope.onClose

                };

            }
        };
    }
    MediaStreamer.$inject = ['Config'];

    app.directive('mediaStreamer', MediaStreamer);

}(angular.module('faster-slider')));