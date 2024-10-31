(function( window,document ) {

    'use strict';

    var _SS = function() {

        var _SS = this;

        this.__ = {
            init: 'init',
            active: 'active',
            id: 'ss-',
            ih: 'ss-ih',
            iw: 'ss-iw',
            slide: 'ss-slide',
            img: 'img',
            arrow: 'nav-arrow',
            left: 'l',
            right: 'r',
            fontIcon: 'font-icon',
            nbh: 'nav-bullet-holder',
            nb: 'nav-bullet',
            f: 'f',
            b: 'b',
            out: 'out',
            in: 'in'
        };

        this.fakeData = false;

        this.config = {};

        this.sliders = {};

        this.init = function( JSONConfig,JSONArgs ) {

            this.storeConfig( JSON.parse( JSONConfig ) );

            this.createSlider( JSON.parse( JSONArgs ) );

        };

        this.storeConfig = function( config ) {

            this.config = config;

        };

        this.getSlider = function( alias ) {

            if( this.sliders[ alias ].length > 0 ) {

                return this.sliders[ alias ].length > 1 ? this.sliders[ alias ] : this.sliders[ alias ][0] ;

            }
            return false;

        };

        this.createSlider = function( args ) {

            this.sliders[ args.alias ] = this.sliders[ args.alias ] || [];

            this.sliders[ args.alias ].push( new SeamlessSlider( args ) );

        };

        this.ajax = function(data,callback, x) {

            try {
                data[ this.config['ajax_validator']['key'] ] = this.config['ajax_validator']['value'];
                x = new window.XMLHttpRequest('MSXML2.XMLHTTP.3.0');
                x.open('POST', this.config['ajax_url'], 1);
                x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                x.onreadystatechange = function () {
                    x.readyState > 3 && callback && callback( JSON.parse( x.responseText ) );
                };
                x.send( JSON.stringify( data ) )
            } catch (e) {
                window.console && console.log(e);
            }
        };

        this.hasClass = function( el, className ) {
            if ( 'undefined' !== typeof el.classList )
                return el.classList.contains( className );
            return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
        };
        this.addClass = function( el, className ) {

            'undefined' !== typeof el.classList ? el.classList.add( className ) : el.className += " " + className;

        };
        this.removeClass = function( el, className ) {

            'undefined' !== typeof el.classList ? el.classList.remove(className) : el.className = el.className.replace( new RegExp('(\\s|^)' + className + '(\\s|$)'), ' ');

        };

        function SeamlessSlider( args ) {

            this.sliderInterval = null;

            this.sliderTimeout = null;

            this.args = args;

            this.options = {};

            this.images = [];

            this.DOM = {
                slides:[]
            };

            this.Style = {};

            this.getDOMElements();

            var activeSlide = 0,
                prevActiveSlide = 0,
                direction = '';
            Object.defineProperty( this , 'activeSlide', {
                get: function() { return activeSlide; },
                set: function( newSlide ) {

                    direction = newSlide > activeSlide ? _SS.__.f : _SS.__.b;

                    if( this.options.settings.auto_slideshow )
                        this.stopAutoSlider();

                    prevActiveSlide = activeSlide - 1;

                    if( prevActiveSlide === -1  )
                        prevActiveSlide = this.totalSlides - 1;

                    if( newSlide === this.totalSlides )
                        newSlide = 0;

                    if( newSlide === -1 )
                        newSlide = this.totalSlides - 1;

                    if( this.sliderTimeout )
                        this.handleAnimationEnd( this.DOM.slides[ prevActiveSlide ], this.DOM.slides[ activeSlide ], direction, true );

                    this.handleAnimation( this.DOM.slides[ activeSlide ], this.DOM.slides[ newSlide ],direction );

                    if( 'undefined' !== typeof this.DOM.bullets ) {

                        _SS.removeClass( this.DOM.bullets[ activeSlide ],_SS.__.active );

                        _SS.addClass( this.DOM.bullets[ newSlide ],_SS.__.active );

                    }

                    activeSlide = newSlide;

                    if( this.options.settings.auto_slideshow )
                        this.startAutoSlider();

                }.bind(this),
                enumerable: true,
                configurable: true
            });

            if( _SS.fakeData === false )
                _SS.ajax( { action:'getSliderWithSlides',alias: this.args.alias } , this.parseServerData.bind(this) );
            else
                this.parseServerData( _SS.fakeData );
        }
        SeamlessSlider.prototype.getDOMElements = function() {

            this.DOM.outerHolder = document.getElementById( _SS.__.id + this.args.alias );

            this.Style.outerHolder = this.DOM.outerHolder.style;

            this.DOM.innerHolder = this.DOM.outerHolder.getElementsByClassName( _SS.__.ih )[0];

            this.DOM.innerWrapper = this.DOM.innerHolder.getElementsByClassName( _SS.__.iw )[0];

            this.Style.innerWrapper = this.DOM.innerWrapper.style;

        };
        SeamlessSlider.prototype.parseServerData = function( response ) {

            if( Object.keys( response ).length ) {

                this.options = response.options;

                this.images = response.images;

                if( this.images.length ) {

                    this.attachStyles( this.Style.outerHolder,this.options.styles );

                    _SS.addClass( this.DOM.innerWrapper,_SS.config.effects[ this.options.effect ] );

                    this.createSlides();

                    this.createArrows();

                    this.createBullets();

                    if( this.options.settings.auto_slideshow ) {

                        this.startAutoSlider();

                        if( this.options.settings.stop_on_hover ) {

                            this.DOM.outerHolder.addEventListener('mouseenter', this.stopAutoSlider.bind(this) );

                            this.DOM.outerHolder.addEventListener('mouseleave', this.startAutoSlider.bind(this) );

                        }

                    }

                    this.finishInitialization();

                }

            }

        };
        SeamlessSlider.prototype.createSlides = function() {

            this.images.map( this.createSlide.bind(this) );

            this.totalSlides = this.DOM.slides.length;

        };
        SeamlessSlider.prototype.createSlide = function( el,i ) {

            var holder = document.createElement('div'),
                imagediv = document.createElement('div'),
                img = document.createElement('img'),
                className = _SS.__.slide;

            if( i === 0 )
                className += ' ' + _SS.__.active;

            holder.className = className;

            imagediv.className = _SS.__.img;

            imagediv.style.backgroundImage = 'url(' + this.images[ i ].url + ')';

            img.src = this.images[ i ].url;

            img.alt = this.images[ i ].alt;

            holder.appendChild( imagediv );

            holder.appendChild( img );

            this.DOM.slides.push( holder );

            this.DOM.innerWrapper.appendChild( holder );

        };
        SeamlessSlider.prototype.attachStyles = function( elementStyle,styles ) {

            function attachStyle( stylePropName ) {

                elementStyle[ stylePropName ] = styles[ stylePropName ];

            }

            Object.keys( styles ).map( attachStyle.bind(this) )

        };
        SeamlessSlider.prototype.createArrows = function() {

            if( this.options.arrows.show ) {

                var arrows = {},
                    icons = {};

                arrows.left = document.createElement('div');

                icons.left = document.createElement('div');

                arrows.right = document.createElement('div');

                icons.right = document.createElement('div');

                icons.left.className = icons.right.className = _SS.__.fontIcon;

                arrows.left.className = _SS.__.arrow + ' ' + _SS.__.left;

                arrows.right.className = _SS.__.arrow + ' ' + _SS.__.right;

                icons.left.innerHTML = _SS.config.icons.arrows[ this.options.arrows.icon ].left;

                icons.right.innerHTML = _SS.config.icons.arrows[ this.options.arrows.icon ].right;

                arrows.left.appendChild( icons.left );

                arrows.right.appendChild( icons.right );

                this.attachStyles( arrows.left.style ,this.options.arrows.style );

                this.attachStyles( arrows.right.style ,this.options.arrows.style );

                arrows.right.addEventListener( 'click', this.nextSlide.bind(this) );

                arrows.left.addEventListener( 'click', this.previousSlide.bind(this) );

                this.DOM.innerHolder.appendChild( arrows.left );

                this.DOM.innerHolder.appendChild( arrows.right );

            }

        };
        SeamlessSlider.prototype.createBullets = function() {

            if( this.options.bullets.show ) {

                var bulletHolder = document.createElement('div'),
                    bullet = document.createElement('div'),
                    icon = document.createElement('div'),
                    append = null;

                bulletHolder.className = _SS.__.nbh;

                bullet.className = _SS.__.nb;

                icon.className = _SS.__.fontIcon;

                icon.innerHTML = _SS.config.icons.bullets[ this.options.bullets.icon ];

                bullet.appendChild( icon );

                this.attachStyles( bullet.style ,this.options.bullets.style );

                this.DOM.bullets = [];

                for( var i = 0, l = this.DOM.slides.length; i < l; i++ ) {

                    append = bullet.cloneNode( true );

                    if( i === 0 )
                        _SS.addClass( append,_SS.__.active );

                    this.DOM.bullets.push( append );

                    append.addEventListener( 'click', this.setSlide.bind( this, i ) );

                    bulletHolder.appendChild( append );

                }

                this.DOM.innerHolder.appendChild(  bulletHolder );

            }

        };
        SeamlessSlider.prototype.setSlide = function( number ) {

            this.activeSlide = number;

        };
        SeamlessSlider.prototype.nextSlide = function() {

            this.activeSlide++;

        };
        SeamlessSlider.prototype.previousSlide = function() {

            this.activeSlide--;

        };
        SeamlessSlider.prototype.startAutoSlider = function() {

            if( this.sliderInterval === null )
                this.sliderInterval = setInterval( this.nextSlide.bind(this) , this.options.settings.delay || 7000 );

        };
        SeamlessSlider.prototype.stopAutoSlider = function() {

            clearInterval( this.sliderInterval );

            this.sliderInterval = null;

        };
        SeamlessSlider.prototype.finishInitialization = function() {

            this.DOM.outerHolder.classList.remove( _SS.__.init );

        };
        SeamlessSlider.prototype.handleAnimation = function( outEl, inEl, direction ) {

            _SS.addClass( this.DOM.innerWrapper,direction );

            _SS.addClass( outEl, _SS.__.out );

            _SS.addClass( inEl, _SS.__.in );

            this.sliderTimeout = setTimeout( this.handleAnimationEnd.bind( this, outEl, inEl, direction ), parseInt( this.options.styles.transitionDuration ) );

        };
        SeamlessSlider.prototype.handleAnimationEnd = function( outEl, inEl, direction, force ) {

            clearTimeout( this.sliderTimeout );

            _SS.removeClass( outEl, _SS.__.active );

            _SS.removeClass( outEl, _SS.__.out );

            _SS.removeClass( inEl, _SS.__.in );

            _SS.addClass( inEl, _SS.__.active );

            _SS.removeClass( this.DOM.innerWrapper,direction );

            this.sliderTimeout = null;

        };

    };


    window._SS = new _SS();

}( window,document ));