<?php

require_once('lib/smarty_tiki/modifier.duration.php');

/**
 * Test class for smarty_modifier_duration().
 * Generated by PHPUnit on 2010-08-05 at 10:04:14.
 */
class ModifierDurationTest extends TikiTestCase {

	protected $inputs = array();

	protected function setUp() {
		$this->inputs = array(6, 60, 66, 300, 2700, 3600, 4140, 5400, 7200, 43200, 86400, 172800, 178200);
	}

	public function testModifierDuration() {
		$expectedResults = array('6 seconds', '1 minute', '1 minute 6 seconds', '5 minutes', '45 minutes', '1 hour', '1 hour 9 minutes', '1 hour 30 minutes', '2 hours', '12 hours', '1 day', '2 days', '2 days 1 hour 30 minutes');

		foreach ($this->inputs as $key => $value) {
			$this->assertEquals($expectedResults[$key], smarty_modifier_duration($value));
		}
	}

	public function testModifierDurationWithParameterLongFalse() {
		$expectedResults = array('6s', '1m', '1m 06s', '5m', '45m', '1h', '1h 09m', '1h 30m', '2h', '12h', '1d', '2d', '2d 1h 30m');

		foreach ($this->inputs as $key => $value) {
			$this->assertEquals($expectedResults[$key], smarty_modifier_duration($value, false));
		}
	}

	public function testModifierDurationWithParameterLongFalseAndMaxLevelSecond() {
		$expectedResults = array('6s', '60s', '66s', '300s', '2700s', '3600s', '4140s', '5400s', '7200s', '43200s', '86400s', '172800s', '178200s');
		foreach ($this->inputs as $key => $value) {
			$this->assertEquals($expectedResults[$key], smarty_modifier_duration($value, false, 'second'));
		}
	}

	public function testModifierDurationWithParameterLongTrueAndMaxLevelSecond() {
		$expectedResults = array('6 seconds', '60 seconds', '66 seconds', '300 seconds', '2700 seconds', '3600 seconds', '4140 seconds', '5400 seconds', '7200 seconds', '43200 seconds', '86400 seconds', '172800 seconds', '178200 seconds');
		foreach ($this->inputs as $key => $value) {
			$this->assertEquals($expectedResults[$key], smarty_modifier_duration($value, true, 'second'));
		}
	}

	public function testModifierDurationWithParameterLongFalseAndMaxLevelHour() {
		$expectedResults = array('6s', '1m', '1m 06s', '5m', '45m', '1h', '1h 09m', '1h 30m', '2h', '12h', '24h', '48h', '49h 30m');
		foreach ($this->inputs as $key => $value) {
			$this->assertEquals($expectedResults[$key], smarty_modifier_duration($value, false, 'hour'));
		}
	}

	public function testModifierDurationWithParameterLongTrueAndMaxLevelHour() {
		$expectedResults = array('6 seconds', '1 minute', '1 minute 6 seconds', '5 minutes', '45 minutes', '1 hour', '1 hour 9 minutes', '1 hour 30 minutes', '2 hours', '12 hours', '24 hours', '48 hours', '49 hours 30 minutes');
		foreach ($this->inputs as $key => $value) {
			$this->assertEquals($expectedResults[$key], smarty_modifier_duration($value, true, 'hour'));
		}
	}

	public function testModifierDurationWithParameterLongFalseAndInvalidMaxLevel() {
		$expectedResults = array('6s', '1m', '1m 06s', '5m', '45m', '1h', '1h 09m', '1h 30m', '2h', '12h', '1d', '2d', '2d 1h 30m');

		foreach ($this->inputs as $key => $value) {
			$this->assertEquals($expectedResults[$key], smarty_modifier_duration($value, false, 1234));
		}
	}

	public function testModifierDurationShouldReturnUnchangedStringIfNotNumeric() {
		$string = 'asdf';
		$this->assertEquals($string, smarty_modifier_duration($string));
	}

}
?>
