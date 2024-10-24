/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    config.filebrowserImageBrowseUrl = '/file-manager/ckeditor';

    config.allowedContent = {
        script: true,
        $1: {
            elements: CKEDITOR.dtd,
            attributes: true,
            styles: true,
            classes: true
        }
    }

    config.versionCheck = false;

    config.contentsCss = '/vendor/fonts.css';
    config.font_names  = 'Basis Grotesque Pro;Cera Pro;Kidentosca;Unbounded;';

    config.extraPlugins = 'fontweight';
    config.fontWeight   = '100;300;500;700;900'
};
