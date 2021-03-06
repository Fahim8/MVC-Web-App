<?php
require_once ("../simpletest/autorun.php");

/**
 *
 * Class for testing the methods validation class
 *        
 */
class ValidationClassTests extends UnitTestCase {
	private $validation;
	public function setUp() {
		require_once ("../app/Validation.php");
		$this->validation = new Validation ();
	}
	public function testIsEmailValid() {
		$this->assertTrue ( $this->validation->isEmailValid ( "luca.longo@dit.ie" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( "luca.@.com" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( ".com" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( "luca" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( "@" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( "123" ) );
		$this->assertFalse ( $this->validation->isEmailValid ( null ));
		$this->assertFalse ( $this->validation->isEmailValid ( array()));
		$this->assertFalse ( $this->validation->isEmailValid ( -1));
		$this->assertFalse ( $this->validation->isEmailValid ( 9999));
		$this->assertFalse ( $this->validation->isEmailValid ( "-1"));
	}
	public function testIsNumberInRangeValid() {
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( 5, 4, 6 ) );
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( "5", 4, 6 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( "ww", 4, 6 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( 5, 7, 6 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( 5, 4, 3 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( 5, "4a", 6 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( 5, 4, "ff" ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( "a", "b", -5 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( "ads", null, 50 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( null, array(), "-50" ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( -5, -10, -7 ) );
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( -7, -10, -5 ) );
		$this->assertFalse ( $this->validation->isNumberInRangeValid ( -7, -5, -10 ) );
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( -7, -10, -5 ) );
		$this->assertTrue ( $this->validation->isNumberInRangeValid ( -7, -7, -7 ) );
		
	}
	public function testIsLengthStringValid() {
		$this->assertFalse ( $this->validation->IsLengthStringValid ( "luca", "5" ) );
		$this->assertTrue ( $this->validation->IsLengthStringValid ( "luca", 6 ) );
		$this->assertFalse ( $this->validation->IsLengthStringValid ( "luca", 4 ) );
		$this->assertFalse ( $this->validation->IsLengthStringValid ( "luca", 4.6 ) );
		$this->assertFalse ( $this->validation->IsLengthStringValid ( 1, 5 ) );
		$this->assertFalse ( $this->validation->IsLengthStringValid ( "luca", "a" ) );
	}
}
?>