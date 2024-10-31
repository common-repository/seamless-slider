(function(app) {

    function SliderFactory( $rootScope,$location,Request,Notification ) {

        angular.extend( this,Notification );

        this.createSlider = function( sliderForm ) {

            this.sliderCU( 'createSlider',sliderForm )

        };
        this.updateSlider = function( sliderForm ) {

            this.sliderCU( 'updateSlider',sliderForm )

        };
        this.deleteSlider = function( slider_id ) {

            $rootScope.isRouteLoading = true;

            Request.post({
                action: 'deleteSlider' ,
                slider_id: slider_id
            }).then( function( result ) {

                this.storeResponse( result.data );

                if( !this.responseFail() )
                    $location.path('/')

            }.bind( this ) );

        };
        this.sliderCU = function( action,sliderForm ) {

            this.sliderFormSubmitted = true;

            console.log( this.sliderFormSubmitted && sliderForm.alias.$error.required );

            if( sliderForm.$valid ) {

                $rootScope.isRouteLoading = true;

                var values = angular.copy( this.data );

                values.options = JSON.stringify( values.options );

                Request.post({
                    action: action ,
                    values: values
                }).then( function( result ) {

                    console.log( result.data );

                    this.storeResponse( result.data );

                    if( this.responseFail() )
                        this.showNotification();
                    else
                        $location.path('/');

                }.bind(this));

            }

        };
        this.getSliderBySliderID = function( slider_id ) {

            return Request.post({
                action:'getSliderBySliderID',
                slider_id:slider_id
            });

        };

        $rootScope.$on('$routeChangeSuccess', function() {

            this.notifications = {};

        }.bind(this));

        return this;

    }

    SliderFactory.$inject = ['$rootScope','$location','Request','Notification'];

    app.factory('SliderFactory', SliderFactory);

}(angular.module('faster-slider')));