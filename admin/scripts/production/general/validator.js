(function(app) {

    function Validator ( Config ) {

        console.log( Config );

        this.debug = Config.debug;

        this.log = function( data ) {

            if( this.debug ) {

                console.error( 'Error trace' );

                console.log( data );

            }

        };

        return this;

    }
    Validator.$inject = ['Config'];

    app.factory('Validator', Validator);

}(angular.module('faster-slider')));