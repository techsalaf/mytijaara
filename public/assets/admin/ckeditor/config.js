/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Allow all content including styles from Word
	config.allowedContent = true;
	config.extraAllowedContent = '*(*);*{*}';
	
	// Paste from Word settings
	config.pasteFromWordRemoveFontStyles = false;
	config.pasteFromWordRemoveStyles = false;
	config.pasteFromWordNumberedHeadingToList = true;
	config.pasteFromWord_inlineImages = true;
	
	// General paste settings
	config.forcePasteAsPlainText = false;
	config.pasteFilter = null;
	
	// Height
	config.height = 300;
};
