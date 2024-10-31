( function( app ) {

    function MediaStreamerFactory ( $http,$location ) {

        this.instagram = {

            client_id:'5d74b9be7ac4414d85ccaa7831bed048',
            getMediaByHashtag: function( tag,count,max_tag_id ) {

                var config = {
                    params: {
                        client_id: this.client_id,
                        count: count || 10,
                        callback: 'JSON_CALLBACK'
                    }
                };

                if( 'undefined' !== typeof max_tag_id )
                    config.params.max_tag_id = max_tag_id;

                return $http.jsonp( 'https://api.instagram.com/v1/tags/' + tag + '/media/recent' , config )

            }

        };

        return this;

    }

    MediaStreamerFactory.$inject = ['$http','$location'];

    app.factory( 'MediaStreamerFactory', MediaStreamerFactory );

}( angular.module('faster-slider' ) ) );