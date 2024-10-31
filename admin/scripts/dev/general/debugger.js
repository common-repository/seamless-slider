(function(app) {

    function Debugger ( Config ) {

        this.debug = Config.debug;

        this.log = function( data ) {

            if( this.debug )
                console.log( data );

        };

        this.error = function( data ) {

            if( this.debug ) {

                console.error( 'Error trace' );

                console.log( data );

            }

        };

        return this;

    }
    Debugger.$inject = ['Config'];

    app.factory('Debugger', Debugger);

}(angular.module('faster-slider')));