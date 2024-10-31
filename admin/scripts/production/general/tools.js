(function(app) {

    function Tools () {

        this.isTrue = function( $n ) {

            return !this.isFalse( $n );

        };
        this.isFalse = function( $n ) {

            return $n === false || $n === 'false';

        };

        return this;

    }
    Tools.$inject = [];

    app.factory('Tools', Tools);

}(angular.module('faster-slider')));