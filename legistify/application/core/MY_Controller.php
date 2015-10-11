<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Controller extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		$this->ci =& get_instance();		
		$this->load->helper(array('url','form','html'));
		$this->load->library('form_validation');
		$this->ci->load->library('session');

		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
	}
	
}
// END Controller class

/* End of file MY_Controller.php */
/* Location: ./system/core/MY_Controller.php */