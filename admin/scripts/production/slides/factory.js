(function(app) {

    function SlidesFactory( Request,Notification,$route,$rootScope,$location ) {

        angular.extend( this,Notification );

        this.getSlidesBySliderID = function( slider_id ) {

            return Request.post({
                action:'getSlidesBySliderID',
                parent_slider_id:slider_id
            });

        };

        this.createSlides = function( images,slider_id ) {

            $rootScope.isRouteLoading = true;

            var request = null;

            if( images.length === 1 ) {

                request = Request.post({
                    action:'createSlide',
                    values: {
                        parent_slider_id: slider_id,
                        image_url: images[0].url,
                        alt:images[0].alt,
                        sort:images[0].sort
                    }

                });

            }
            else
                request = Request.post({
                    action:'createSlides',
                    values: {
                        parent_slider_id: slider_id
                    },
                    images:images
                });

            request.then( function( result ) {

                this.storeResponse( result.data );

                if( this.responseFail() )
                    this.showNotification();
                else
                    $route.reload();

            }.bind(this));

        };
        this.deleteSlide = function( slider_id,slide_id ) {

            $rootScope.isRouteLoading = true;

            Request.post({
                action: 'deleteSlide' ,
                slider_id: slider_id,
                slide_id: slide_id
            }).then( function( result ) {

                this.storeResponse( result.data );

                if( !this.responseFail() )
                    $location.path('slides/' + slider_id)

            }.bind( this ) );

        };
        this.updateSlidesSort = function( slides ) {

            Request.post({
                action: 'updateSlidesSort' ,
                slides: slides
            });

        };

        $rootScope.$on('$routeChangeSuccess', function() {

            this.notifications = {};

        }.bind(this));

        return this;

    }

    SlidesFactory.$inject = ['Request','Notification','$route','$rootScope','$location'];

    app.factory('SlidesFactory', SlidesFactory);

}(angular.module('faster-slider')));