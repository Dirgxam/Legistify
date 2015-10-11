<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User_model extends CI_Model {

	/**
	 * message (uses lang file)
	 *
	 * @var string
	 **/
	protected $messages;

	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 **/
	protected $errors;


	public function __construct()
	{
		parent::__construct();	
		$this->load->database();
		$this->lang->load('user');

		//using delimiters
		$this->message_start_delimiter = '<p class="text-black">';
		$this->message_end_delimiter   = '</p>';
		$this->error_start_delimiter   = '<p class="text-red">';
		$this->error_end_delimiter     = '</p>';
		

	}


	public function get_documents()
	{
		$query = $this->db->get('document_master');

		$row[0] = 'Select Document';
		if ($query->num_rows() > 0) {

			foreach ($query->result() as $res) {
				$row[$res->id] = $res->name;
			}
			return $row;
		}
		return $row;
	}


	public function get_user_querylist($id = '')
	{
		if (empty($id)) {

			$this->db->select('B.name as doc,A.*')
					 ->from('queries as A')
					 ->join('document_master as B', 'B.id = A.document_id')
					 ->where('A.email_send',0)
					 ->order_by('A.time','ASC');
			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				return $query->result_array();
			}
			return FALSE;
			
		}else{

			$this->db->select('B.name as doc,A.*')
					 ->from('queries as A')
					 ->join('document_master as B', 'B.id = A.document_id')
					 ->where(array('A.email_send' => 0 , 'A.id' => $id))
					 ->limit(1);
			$query = $this->db->get();

			if ($query->num_rows() == 1) {
				
				return $query->row();
			}
			return FALSE;
		}
	}



	public function login($identity, $password)
	{
		if (empty($identity) || empty($password)) {
			
			$this->set_error('login_unsuccessful');
			return FALSE;

		}

		$query = $this->db->select('id,username')
				 ->get_where('users',array('username' => $identity , 'password' => $password));

		if ($query->num_rows() == 1) {
			
			$this->set_session($query->row());
			$this->set_message('login_successful');
			return TRUE;
		}

		$this->set_error('login_unsuccessful');
		return FALSE;
	}



	public function save_document_query($email,$doc,$details)
	{
		if (empty($email) || empty($doc) || empty($details)) {

			$this->set_error('query_unsuccessful');
			return FALSE;
		}

		$save_data = array(
				'document_id' => $doc,
				'email' => $email,
				'message' => $details,
				'time' => date('Y-m-d H:m:s')
			);

		$this->db->insert('queries', $save_data);

		if ($this->db->insert_id()) {
			
			$this->set_message('query_successful');
			return TRUE;
		}

		$this->set_error('query_unsuccessful');
		return FALSE;
	}


	public function update_document_query($id,$response)
	{

		if (empty($id) || empty($response)) {

			$this->set_error('update_query_unsuccessful');
			return FALSE;
		}

		$save_data = array(
				'response_message' => $response,
				'email_send' => 1
			);

		$updated_query_db = $this->db->update('queries', $save_data,array('id' => $id));

		if ($updated_query_db) {
			
			$this->set_message('update_query_successful');
			
		}else{

			$this->set_error('update_query_unsuccessful');
		
	    }
	    return $updated_query_db;
	}




	protected function set_session($user)
	{
		$session_data = array(
		    'id'             => $user->id,
		    'username'       => $user->username
		);

		$this->session->set_userdata($session_data);

		return TRUE;
	}


	/**
	 * logged_in
	 *
	 * @return bool
	 **/
	public function logged_in()
	{
		return (bool) $this->session->userdata('id');
	}


		/**
		 * logout
		 *
		 * @return void
		 **/
		public function logout()
		{
			
	        $this->session->unset_userdata( array('id' => '', 'username' => '') );

			//Destroy the session
			$this->session->sess_destroy();

			//Recreate the session
			if (substr(CI_VERSION, 0, 1) == '2')
			{
				$this->session->sess_create();
			}
			else
			{
				$this->session->sess_regenerate(TRUE);
			}

			$this->set_message('logout_successful');
			return TRUE;
		}




		/**
		 * set_error
		 *
		 * Set an error message
		 *
		 * @return void
		 **/
		public function set_error($error)
		{
			$this->errors[] = $error;

			return $error;
		}
		
		/**
		 * errors
		 *
		 * Get the error message
		 *
		 * @return void
		 **/
		public function errors()
		{
			$_output = '';
			foreach ($this->errors as $error)
			{
				$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
				$_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			}

			return $_output;
		}


		/**
		 * set_message
		 *
		 * Set a message
		 *
		 * @return void
		 **/
		public function set_message($message)
		{
			$this->messages[] = $message;

			return $message;
		}

		/**
		 * messages
		 *
		 * Get the messages
		 *
		 * @return void
		 **/
		public function messages()
		{
			$_output = '';
			foreach ($this->messages as $message)
			{
				$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
				$_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			}

			return $_output;
		}
         

	







	}