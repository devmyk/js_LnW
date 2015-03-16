<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_test extends CI_Controller {
	public function index()
	{
		echo "test<br>";
        echo BASEPATH."<br>";
        echo VIEWPATH."<br>";
        echo getcwd()."<br>";
        echo SELF."<br>";
	}
}
