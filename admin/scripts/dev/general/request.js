(function(app) {

    function Request( $http,Debugger,Config ) {

        Debugger.log( Config );

        this.post = function( data ) {

            return this.sendRequest( 'POST',data );

        };
        this.sendRequest = function( type,data ) {

            data[ Config['ajax_validator']['key'] ] = Config['ajax_validator']['value'];

            Debugger.log( data );

            return $http({
                method: type,
                url: Config['ajax_url'],
                data: data,
                cache: Config.cache
            }).error( function( data, status, headers, config ) {

                    Debugger.error( data );

                }.bind(this) );

        };

        return this;

    }

    Request.$inject = ['$http','Debugger','Config'];

    app.factory('Request', Request);

}(angular.module('faster-slider')));