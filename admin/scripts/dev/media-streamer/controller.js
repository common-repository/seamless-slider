( function( app ) {

    function MediaStreamerController ( MediaStreamerFactory ) {

        var mediaStreamer = this;

        this.callbacks = {};

        this.state = {

            loading: false

        };
        this.instagram = {

            hashtag: '',
            count: 30,
            images: [],
            tag_ids: [],
            activePage: 0,
            selectedImages: {},
            activeKey: false,
            getByHashtag: function( hastag,count,next_max_tag_id ) {

                mediaStreamer.state.loading = true;

                MediaStreamerFactory.instagram.getMediaByHashtag( hastag,count,next_max_tag_id )
                    .success(function( response ) {

                        this.tag_ids.push( response['pagination']['next_max_tag_id'] );

                        angular.forEach( response.data, function( el ){

                            this.images.unshift({ url: el.images['standard_resolution'].url, alt: '' })

                        }.bind(this));

                        mediaStreamer.state.loading = false;

                    }.bind(this));

            },
            search: function() {

                if( this.hashtag !== '' ) {

                    this.images = [];

                    this.tag_ids = [];

                    this.activePage = 0;

                    this.getByHashtag( this.hashtag,this.count,this.tag_ids[ this.activePage ] );

                }

            },
            loadMore:function() {

                this.getByHashtag( this.hashtag,this.count,this.tag_ids[ this.activePage ] );

                this.activePage++;

            },
            selectImage: function( $event,key,image ) {

                this.activeKey = false;

                if( 'undefined' === typeof this.selectedImages[ key ] ) {

                    if( !$event.ctrlKey )
                        this.selectedImages = {};

                    this.selectedImages[ key ] = image;

                    this.activeKey = key;

                    this.activeImage = image;

                }
                else
                    delete this.selectedImages[ key ];

            }

        };
        this.instagramFreshCopy = angular.copy( this.instagram );


        this.close = function( data ) {

            this.instagram = angular.copy( this.instagramFreshCopy );

            if( 'undefined' !== typeof this.callbacks.onClose )
                this.callbacks.onClose( data );

        };

    }

    MediaStreamerController.$inject = ['MediaStreamerFactory'];

    app.controller( 'MediaStreamerController', MediaStreamerController );

}( angular.module('faster-slider' ) ) );