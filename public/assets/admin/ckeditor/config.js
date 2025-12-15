/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here.
    // For complete reference see:
    // https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

    // Allow all content (don't filter out any HTML tags/attributes)
    config.allowedContent = true;

    // Paste from Word configuration
    config.pasteFromWordRemoveFontStyles = false;
    config.pasteFromWordRemoveStyles = false;
    config.pasteFromWord_inlineImages = true;

    // Force paste as plain text option (user can toggle)
    config.forcePasteAsPlainText = false;

    // Paste filter settings - allow all content types
    config.pasteFilter = null;

    // Additional paste settings
    config.clipboard_handleImages = true;

    // Remove extra formatting on paste
    config.pasteFromWordNumberedHeadingToList = true;
    config.pasteFromWordPromptCleanup = false;

    // Enable extra plugins for paste functionality
    config.extraPlugins = 'pastefromword,pastetext,clipboard';

    // Set the editor height
    config.height = 300;

    // Toolbar configuration - simplified but functional
    config.toolbar = [
        { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll' ] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        '/',
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
        { name: 'document', items: [ 'Source' ] }
    ];

    // Remove some buttons provided by the standard plugins, which are not needed
    config.removeButtons = '';

    // Set the most common block elements
    config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre;address;div';

    // Simplify the dialog windows
    config.removeDialogTabs = 'image:advanced;link:advanced';

    // Enable browser spell checking
    config.disableNativeSpellChecker = false;

    // Set language
    config.language = 'en';

    // Enable enterMode to create paragraphs on enter
    config.enterMode = CKEDITOR.ENTER_P;
    config.shiftEnterMode = CKEDITOR.ENTER_BR;

    // Auto-grow configuration
    config.autoGrow_minHeight = 200;
    config.autoGrow_maxHeight = 600;
};
