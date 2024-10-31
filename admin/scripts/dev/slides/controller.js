( function( app,wp ) {

    function SlidesController ( $route, $routeParams,SlidesFactory,$scope ) {

        var controller = this;

        this.mediaStreamer = {
            show: false,
            open:function( mediaType ) {

                this.show = mediaType;

            },
            close:function( images ) {

                controller.mediaStreamer.show = false;

                if( images )
                    SlidesFactory.createSlides( images,controller.slider_id );

            }
        };

        this.uploadFrame = null;

        this.slides = $route.current ? $route.current.locals.slides : false;

        this.updateSlides = {};

        this.maxSort = Object.keys( this.slides ).length;

        $scope.$watch('_ssc.slides', function ( newValue,oldValue ) {

            this.updateSlides = {};

            angular.forEach( newValue,function( el,i ) {

                this.updateSlides[ el.slide_id ] = oldValue[ i ].sort;

            }.bind(this));

            SlidesFactory.updateSlidesSort( this.updateSlides );

        }.bind(this),true);

        this.$route = $route;

        this.$routeParams = $routeParams;

        this.slider_id = this.$route.current.params['slider_id'];

        this.addSlide = function() {

            if ( this.uploadFrame ) {
                this.uploadFrame.open();
                return;
            }

            this.uploadFrame = wp.media.frames.file_frame = wp.media({
                title: 'Select Files',
                button: {
                    text: 'Add to Seamless Slider'
                },
                multiple: true
            });

            this.uploadFrame.on( 'select', function() {

                var selections = this.uploadFrame.state().get('selection'),
                    images = [];

                if( selections.length ) {

                    angular.forEach( selections, function( selection ) {

                        images.push({ url:selection.attributes.url,alt:selection.attributes.alt,sort: ++this.maxSort });

                    }.bind(this));

                    SlidesFactory.createSlides( images,this.slider_id );

                }

            }.bind(this));

            this.uploadFrame.open();

        };





    }

    SlidesController.$inject = ['$route', '$routeParams','SlidesFactory','$scope'];

    app.controller( 'SlidesController', SlidesController );

}( angular.module('faster-slider' ),wp ) );



