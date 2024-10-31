(function(app) {

    function Notification ( Tools,$rootScope ) {

        this.cancelLoadOnShow = true;
        this.response = {};
        this.notifications = {};

        this.storeResponse = function( response ) {

            this.response = response;

        };
        this.showNotification = function() {

            this.notifications[ this.response.error_field || 0 ] = this.response.failText;

            if( this.cancelLoadOnShow )
                $rootScope.isRouteLoading = false;

        };
        this.closeNotification = function( alias ) {

            this.notifications[ alias ] = false;

        };
        this.responseFail = function() {

            return this.response.result === false || Tools.isFalse( this.response.result );

        };
        this.getResponseText = function() {

            return this.response.responseText;

        };

        return this;

    }
    Notification.$inject = ['Tools','$rootScope'];

    app.factory('Notification', Notification);

}(angular.module('faster-slider')));