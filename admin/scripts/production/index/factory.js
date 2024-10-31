(function(app) {

    function IndexFactory( Request,Config ) {

        this.getSliders = function() {

            return Request.post({
                action:'getSliders'
            });

        };

        return this;

    }

    IndexFactory.$inject = ['Request','Config'];

    app.factory('IndexFactory', IndexFactory);

}(angular.module('faster-slider')));