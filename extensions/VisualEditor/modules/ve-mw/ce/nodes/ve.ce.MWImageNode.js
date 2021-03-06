/*!
 * VisualEditor ContentEditable MWImageNode class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

/**
 * ContentEditable MediaWiki image node.
 *
 * @class
 * @abstract
 * @extends ve.ce.GeneratedContentNode
 * @mixins ve.ce.FocusableNode
 * @mixins ve.ce.MWResizableNode
 *
 * @constructor
 * @param {jQuery} $figure Figure element
 * @param {jQuery} $image Image element
 * @param {Object} [config] Configuration options
 */
ve.ce.MWImageNode = function VeCeMWImageNode( $figure, $image, config ) {
	config = ve.extendObject( {
		enforceMax: false,
		minDimensions: { width: 1, height: 1 }
	}, config );

	// Properties
	this.$figure = $figure;
	this.$image = $image;
	// Parent constructor triggers render so this must precede it
	this.renderedDimensions = null;

	// Parent constructor
	ve.ce.GeneratedContentNode.call( this );

	// Mixin constructors
	ve.ce.FocusableNode.call( this, this.$figure, config );
	ve.ce.MWResizableNode.call( this, this.$image, config );

	// Events
	this.model.connect( this, { attributeChange: 'onAttributeChange' } );
};

/* Inheritance */

OO.inheritClass( ve.ce.MWImageNode, ve.ce.GeneratedContentNode );

OO.mixinClass( ve.ce.MWImageNode, ve.ce.FocusableNode );

// Need to mixin base class as well
OO.mixinClass( ve.ce.MWImageNode, ve.ce.ResizableNode );

OO.mixinClass( ve.ce.MWImageNode, ve.ce.MWResizableNode );

/* Static Properties */

ve.ce.MWImageNode.static.primaryCommandName = 'media';

/* Static Methods */

/**
 * @inheritdoc ve.ce.Node
 */
ve.ce.MWImageNode.static.getDescription = function ( model ) {
	var title = new mw.Title( model.getFilename() );
	return title.getMainText();
};

/* Methods */

/**
 * Update the rendering of the 'align', src', 'width' and 'height' attributes
 * when they change in the model.
 *
 * @method
 * @param {string} key Attribute key
 * @param {string} from Old value
 * @param {string} to New value
 */
ve.ce.MWImageNode.prototype.onAttributeChange = function () {};

/** */
ve.ce.MWImageNode.prototype.generateContents = function () {
	var xhr,
		width = this.getModel().getAttribute( 'width' ),
		height = this.getModel().getAttribute( 'height' ),
		deferred = $.Deferred();

	// If the current rendering is larger don't fetch a new image, just let the browser resize
	if ( this.renderedDimensions && this.renderedDimensions.width > width ) {
		return deferred.reject().promise();
	}

	xhr = new mw.Api().get( {
		action: 'query',
		prop: 'imageinfo',
		iiprop: 'url',
		iiurlwidth: width,
		iiurlheight: height,
		titles: this.getModel().getFilename()
	} )
		.done( this.onParseSuccess.bind( this, deferred ) )
		.fail( this.onParseError.bind( this, deferred ) );

	return deferred.promise( { abort: xhr.abort } );
};

/**
 * Handle a successful response from the parser for the image src.
 *
 * @param {jQuery.Deferred} deferred The Deferred object created by generateContents
 * @param {Object} response Response data
 */
ve.ce.MWImageNode.prototype.onParseSuccess = function ( deferred, response ) {
	var id, src, pages = ve.getProp( response, 'query', 'pages' );
	for ( id in pages ) {
		if ( pages[ id ].imageinfo ) {
			src = pages[ id ].imageinfo[ 0 ].thumburl;
		}
	}
	if ( src ) {
		deferred.resolve( src );
	} else {
		deferred.reject();
	}
};

/** */
ve.ce.MWImageNode.prototype.render = function ( generatedContents ) {
	this.$image.attr( 'src', generatedContents );
	// As we only re-render when the image is larger than last rendered size
	// this will always be the largest ever rendering
	this.renderedDimensions = ve.copy( this.model.getScalable().getCurrentDimensions() );
	if ( this.live ) {
		this.afterRender();
	}
};

/**
 * Handle an unsuccessful response from the parser for the image src.
 *
 * @param {jQuery.Deferred} deferred The promise object created by generateContents
 * @param {Object} response Response data
 */
ve.ce.MWImageNode.prototype.onParseError = function ( deferred ) {
	deferred.reject();
};
