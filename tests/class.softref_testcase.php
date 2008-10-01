<?php

require_once(t3lib_extMgm::extPath("imagemap_wizard")."classes/class.tx_imagemapwizard_softrefproc.php");

class softref_testcase extends tx_phpunit_testcase {

	function test_emptySoftRefsWork() {
		$parser = t3lib_div::makeInstance("tx_imagemapwizard_softrefproc");
		$emptyStrings = array('','<map></map>');

		foreach($emptyStrings as $str) {
			$result = $parser->findRef('', '', '', $str, '', '', '');
			$this->assertEquals(true,is_array($result),'array expected');
			$this->assertEquals(true,isset($result["content"]),' "content"-part in array expected');
			$this->assertEquals(true,isset($result["elements"]) && is_array($result["elements"]),' "elements"-part in array  as sub-array expected');
		}
	}

	function test_basicSoftRefsWork() {
		$parser = t3lib_div::makeInstance("tx_imagemapwizard_softrefproc");
		$mapContent = $this->_demoMap1();
		$result = $parser->findRef('', '', '', $mapContent, '', '', '');

		$this->assertEquals(1,count($result["elements"]),'Wrong Reference-Count found');

		foreach($result["elements"] as $elem) {
			$this->assertEquals('1',$elem['matchString'],'Wrong Reference found');
			$this->assertEquals('db',$elem['subst']['type'],'Wrong Reference-Type found');
			$this->assertEquals('pages:1',$elem['subst']['recordRef'],'Wrong Reference-Records found');
		}
	}

	function test_multipleSoftRefsWork() {
		$parser = t3lib_div::makeInstance("tx_imagemapwizard_softrefproc");
		$mapContent = $this->_demoMap2();
		$result = $parser->findRef('', '', '', $mapContent, '', '', '');

		$this->assertEquals(3,count($result["elements"]),'Wrong Reference-Count found');

		$supposed = array(
						array('1','db','pages:1'),
						array('2','db','pages:2'),
						array('3','db','pages:3'),
				);
		$i=0;
		foreach($result["elements"] as $elem) {
			$this->assertEquals($supposed[$i][0],$elem['matchString'],'Wrong Reference found');
			$this->assertEquals($supposed[$i][1],$elem['subst']['type'],'Wrong Reference-Type found');
			$this->assertEquals($supposed[$i][2],$elem['subst']['recordRef'],'Wrong Reference-Records found');
			$i++;
		}
	}

	function _demoMap1() {
		return '<map><area coords="0,0,100,100" shape="rect">1</area></map>';

	}
	function _demoMap2() {
		return '<map><area>1</area><area>2</area><area>3</area></map>';

	}

/*
	function setUp() {
		$this->createDatabase();
		$db = $this->useTestDatabase();
		// $this->importDataSet(dirname(__FILE__). '/fixtures/dbContentWithVisibilityTestdata.xml');
		// order of extension-loading is important !!!!
		// $this->importExtensions(array('corefake','cms','templavoila')); // you won't need it - if you have any extensions which deal with core-tables just let me know and I'll provide you the "corefake" ext.
	}
	function tearDown() {
		$this->cleanDatabase();
   		$this->dropDatabase();
		$GLOBALS['TYPO3_DB']->sql_select_db(TYPO3_db);
	}
*/
}



?>
