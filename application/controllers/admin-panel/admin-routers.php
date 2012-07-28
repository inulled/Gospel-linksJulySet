<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class aRouters extends CI_Controller {
	public function aR/* admin requests */() {
		$this->load->view('admin-panel/admin-request.php');
	}
}