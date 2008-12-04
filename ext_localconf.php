<?php

if (TYPO3_MODE=='BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['GLOBAL']['softRefParser']['tx_imagemapwizard'] = "EXT:imagemap_wizard/classes/class.tx_imagemapwizard_softrefproc.php:&tx_imagemapwizard_softrefproc";
    //$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_imagemapwizard_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'classes/class.tx_imagemapwizard_wizicon.php';
}

    require_once(t3lib_extMgm::extPath('imagemap_wizard') . 'classes/controller/class.tx_imagemapwizard_wizardController.php');
	$typoscript = '
		includeLibs.imagemap_wizard = EXT:imagemap_wizard/classes/class.tx_imagemapwizard_parser.php
		tt_content.imagemap_wizard < tt_content.image
		tt_content.imagemap_wizard.20.imgMax = 1
		tt_content.imagemap_wizard.20.1.imageLinkWrap >
		tt_content.imagemap_wizard.20.1.params = usemap="####IMAGEMAP_USEMAP###"
		tt_content.imagemap_wizard.20.1.stdWrap.postUserFunc = tx_imagemapwizard_parser->applyImageMap
       tt_content.imagemap_wizard.20.1.stdWrap.postUserFunc.map.data = field:tx_imagemapwizard_links
       tt_content.imagemap_wizard.20.1.stdWrap.postUserFunc.map.name = field:titleText // field:altText // field:imagecaption // field:header
       tt_content.imagemap_wizard.20.1.stdWrap.postUserFunc.map.name.crop = 20
       tt_content.imagemap_wizard.20.1.stdWrap.postUserFunc.map.name.case = lower
	';

	t3lib_extMgm::addTypoScript($_EXTKEY,'setup',$typoscript,43);

?>