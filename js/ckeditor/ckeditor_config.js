/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'uk';
	config.filebrowserUploadMethod = 'form'; // Added for file browser
	config.contentsCss = 'http://fonts.googleapis.com/css?family=Lobster';
	config.font_names =  'serif;sans serif;monospace;cursive;fantasy;Lobster;'+config.font_names;
	// config.uiColor = '#AADC6E';
};
