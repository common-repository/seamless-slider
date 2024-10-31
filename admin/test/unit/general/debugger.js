describe('Debugger', function () {

    var $injector = angular.injector(['faster-slider']),
        Debugger;

    beforeEach( angular.mock.inject( function() {

        Debugger = $injector.get('Debugger');

    } ) );


    it('debug', function () {

        expect( Debugger.debug ).toBe( true );

    });

});