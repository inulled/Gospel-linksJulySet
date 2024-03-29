<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * 
 * <?=base_url()?>.index.php/home
 * 
 * 
 */
class home extends CI_Controller {
	public function index() {
		// when user first visits the site, if user is logged in redirect to dashboard, else redirect to startpage
		if ($this->session->userdata("logged") == "1") {
		$username = $this->session->userdata("username");
		$query = $this->db->get_where('users', array('username' => $username));
		
		// the following function loads in all the user type and redirects to the correct dashboard
		$userType = $this->session->userdata('userType');
			if ($userType == 'regular') {
					foreach ($query->result() as $row) {
						$data = array('firstname' => $row->firstname);
						$this->load->view('reg-user/dashboard', $data);
					}
			} elseif ($userType == 'overseer') {
					foreach ($query->result() as $row) {
						$data = array('firstname' => $row->firstname);
						$this->load->view('dashboard', $data);
					}
			} elseif ($userType == 'admin') {
					foreach ($query->result() as $row) {
						$data = array('firstname' => $row->firstname);
						$this->load->view('dashboard', $data);
					}
			}
		// if user isn't logged in then redirect to the startpage
		} elseif ($this->session->userdata("logged") == "0") {
			$this->load->view('startpage');
		}
	} public function kill_sess() {
		// when the user clicks the logout button in the view, this function is fired. this kills the session
		$array_items = array('logged' => '');
		$this->session->unset_userdata($array_items);
		redirect(base_url(), 'location');
	} public function member_reg() {
		header('Content-type:application/json');
		$idgen = uniqid(rand(), false);

		// Below gets inserted into the users table
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$firstname = $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$email = $this->input->post('email');
		$hashedPass = $this->encrypt->sha1($password);
		
		mkdir(base_url() . 'user-data/User-PID{' . $idgen . '}', 0755);
		mkdir(base_url() . 'user-data/User-PID{' . $idgen . '}/photos', 0755);
		mkdir(base_url() . 'user-data/User-PID{' . $idgen . '}/videos', 0755);
		
		$this->db->query("INSERT IGNORE INTO users (username, password, firstname, lastname, email, account_type, userid, status, isLeader, defaultImgURI) VALUES('{$username}', '{$hashedPass}', '{$firstname}', '{$lastname}', '{$email}', 'Regular Member', '{$idgen}', 'Pass', 'Nope', 'vd/user-data/superman-facebook.jpg')");
		$query = $this->db->query("SELECT * FROM users WHERE account_type LIKE '%Administrator%'");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				if ($row->account_type == 'Administrator') {
					$leader_id = $row->userid;
					$this->db->query("INSERT IGNORE INTO friends (node1id, node2id, friendsSince, friendType)
					VALUES('$leader_id', '$idgen', NOW(), 'Full')");
				}
			}
			  echo "Thanks for registering. You can now login to your account!";
	} public function church_reg() {
		header('Content-type:application/json');
		$idgen = uniqid(rand(), false);
		$churchIdGen = uniqid(rand(), false);
		
		// Below gets inserted into the church_repo table
		$churchName = $this->input->post('church_name');
		$streetAddress = $this->input->post('street_address');
		$locationalCity = $this->input->post('locational_city');
		$locationalState = $this->input->post('locational_state');
		$locationalZip = $this->input->post('locational_zip');
		$locationalCountry = $this->input->post('locational_country');
		$taxNum = $this->input->post('tax_exemption_number');
		
		// Below gets inserted into the users table
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$firstname = $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$email = $this->input->post('email');
		$areacode = $this->input->post('areacode');
		$hashedPass = $this->encrypt->sha1($password);
		
		// Sets the trial mode setup
		$dateIn3Months = date('Y-m-d', strtotime('+3 Months'));
		$this->db->query("INSERT IGNORE INTO church_repo (church_name, street_address, locational_state, locational_zip, locational_country, locational_city, overseer_account_id, tax_exemption_number, status, date_trial_ends, areacode, churchId) VALUES('{$churchName}', '{$streetAddress}', '{$locationalState}', '{$locationalZip}', '{$locationalCountry}', '{$locationalCity}', '{$idgen}', '{$taxNum}', 'Pending', '{$dateIn3Months}', '{$areacode}', '{$churchIdGen}')");
		$this->db->query("INSERT IGNORE INTO users (username, password, firstname, lastname, email, account_type, userid, status, isLeader, defaultImgURI) VALUES('{$username}', '{$hashedPass}', '{$firstname}', '{$lastname}', '{$email}', 'Account Overseer', '{$idgen}', 'Pending', 'Nope', 'vd/user-data/superman-facebook.jpg')");
		$query = $this->db->query("SELECT * FROM users WHERE isLeader LIKE '%yes%'");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				if ($row->isLeader == 'Yessiree') {
					$leader_id = $row->userid;
					$this->db->query("INSERT IGNORE INTO friends (node1id, node2id, friendsSince, friendType)
					VALUES('$leader_id', '$idgen', NOW(), 'Full')");
				}
			}
		echo "Your application has been submitted. Please watch your email as we will contact you when a representative verifies your data.";
	} public function logsig() {
		header('Content-type:application/json');
		$postedUser = $this->input->post('username');
		$password = $this->input->post('password');
		$hashedPass = $this->encrypt->sha1($password);
		$query = $this->db->query("SELECT * FROM users WHERE username = '{$postedUser}' AND password = '{$hashedPass}'");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				if ($row->status == "Pass") {
					$userid = $row->userid;
					// Below takes the church that the user logging in is apart of and sets a session defining it.
					/*$churchSelector = $this->db->query("SELECT * FROM churchMember WHERE cMuserId = '{$userid}'");
					if ($churchSelector->num_rows() > 0) {
						$cSrow = $churchSelector->row(); // $cS(in($cSrow)) = churchSelector
						$this->session->set_userdata('churchMemId', '{cMuserId}');
					} */
					$this->session->set_userdata('logged', "1");
					$this->session->set_userdata('userid', "{$userid}");
					$this->session->set_userdata("username", "{$postedUser}");
						if ($row->account_type == "Administrator") {
							$this->session->set_userdata('userType', 'admin');
						} elseif ($row->account_type == "Account Overseer") {
							$this->session->set_userdata('userType', 'overseer');
						} elseif ($row->account_type == "Regular Member") {
							$this->session->set_userdata('userType', 'regular');
						}
					echo json_encode(array('session_state' => true));
				} elseif ($row->status == "Fail" || $row->status == "Pending") {
					echo json_encode(array('session_state' => false));
				}
			} else {
				echo json_encode(array('session_state' => false));
			}
		}
}