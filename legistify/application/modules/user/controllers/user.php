<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {


	public function __construct()
	{
		parent::__construct();
		$this->form_validation->set_error_delimiters('<p class="text-black">', '</p>');
		//$this->form_validation->set_message_delimiters('<p class="text-aqua">', '</p>');
		$this->load->model('user_model');

	}



	public function index()
	{
		if (!$this->user_model->logged_in()) {
			
			redirect('user/user_query','refresh');
		}
		else{

		$this->load->view('blank');

		}		
		
	}

	public function ajax_get_querylist()
	{
		//check if its an ajax request, exit if not
		 if(!$this->input->is_ajax_request()) {
		     die("request should be ajax");
		 }

		$result = $this->user_model->get_user_querylist();
		if ($result) {

			$res = array("valid"=>"true", "qdata" => $result);
			echo json_encode($res);			
		}
		else{

		$res = array("valid"=>"false");
		echo json_encode($res);

		}

	}


	/** Function for Admin's login **/
	public function login()
	{
		
		//validate form input		
		$this->form_validation->set_rules('identity', 'Identity', 'required|xss_clean|max_length[30]');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');

		if ($this->form_validation->run() == true)
		{

			if ($this->user_model->login($this->input->post('identity'), $this->input->post('password')))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->user_model->messages());
				redirect('user/index', 'refresh');
			}
			else
			{
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('errors', $this->user_model->errors());
				redirect('user/login', 'refresh'); 
			}
		}
		else
		{
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['errors'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('errors');
			$this->data['message'] = $this->session->flashdata('message');

			$this->load->view('login',$this->data);
		}
	}


public function check_docs($str)
{
	if (!$str) {
		$this->form_validation->set_message('check_docs', 'The %s field is required');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
}

	public function user_query()
	{
		$this->data['title'] = 'Query Form';
		//validate form input		
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean|valid_email|max_length[30]');
		$this->form_validation->set_rules('document', 'Document', 'required|xss_clean|callback_check_docs');
		$this->form_validation->set_rules('details', 'Details', 'required|xss_clean|max_length[150]');
		
		if ($this->form_validation->run() == true)
		{

			if ($this->user_model->save_document_query($this->input->post('email'), $this->input->post('document'),$this->input->post('details')))
			{
				$this->session->set_flashdata('message', $this->user_model->messages());
				redirect('user/user_query', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('errors', $this->user_model->errors());
				redirect('user/user_query', 'refresh'); 
			}
		}
		else
		{
			$this->data['errors'] = $this->session->flashdata('errors');
			$this->data['message'] = $this->session->flashdata('message');

			$this->data['doc_list'] = $this->user_model->get_documents();

			$this->load->view('query_form',$this->data);
		}

	}


	public function update_querylist($id)
	{
		if (!$this->user_model->logged_in() || !$id) {

			redirect('user/index','refresh');

		}elseif (!$this->user_model->get_user_querylist(trim($id))) {
			
			redirect('user/index','refresh');
		}

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'doc|docx';
		$config['overwrite'] = true;
		$config['max_size']	= '3072';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$this->load->library('upload', $config);

		$this->data['query_info'] = $this->user_model->get_user_querylist(trim($id));

		//validate form input		
		$this->form_validation->set_rules('answer', 'Answer', 'required|xss_clean|max_length[100]');
		
		if ($this->form_validation->run() == true && $this->upload->do_upload())
		{
			
			if ($this->_send_email($this->data['query_info']->email,$this->data['query_info']->message,$this->input->post('answer'),$this->upload->data()['full_path']))
			{

				//if message sent successfully
				//update queries data
				if($this->user_model->update_document_query($this->data['query_info']->id,$this->input->post('answer')))
				{
					$this->session->set_flashdata('message', $this->user_model->messages());
					redirect('user/index', 'refresh');
				}
				else{
				$this->session->set_flashdata('errors', $this->user_model->errors());
				redirect(uri_string(), 'refresh');
				}
			}
			else
			{
				//redirect them back to the login page
				$this->session->set_flashdata('errors', $this->user_model->errors());
				redirect(uri_string(), 'refresh'); 
			}
		}else{		
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['errors'] = ($this->upload->display_errors()) ? $this->upload->display_errors() : $this->session->flashdata('errors');
			$this->data['message'] = $this->session->flashdata('message');

			$this->load->view('edit',$this->data);

		}

	}


	protected function _send_email($to, $msg, $response_msg, $attachment){

		if (!$this->user_model->logged_in() || empty($to)) {

			redirect('user/index','refresh');

		}
			$this->load->library('email');

			$this->email->clear(TRUE);

			$this->email->from($this->email->smtp_user); 

			$this->email->to($to); 

			$this->email->subject('Legistify - Query Support');

			$body = $this->email->email_body($msg, $response_msg);

			$this->email->message($body);

			$this->email->attach($attachment);  				

			$result = $this->email->send();	

			if($result){

				$this->session->set_flashdata('message','e-mail sent successfully to your given email address');
				return TRUE;

			}else{

				$this->session->set_flashdata('errors','Unable to sent e-mail');
				return FALSE;
			}
		}



	public function logout()
	{
		//log the user out
		$logout = $this->user_model->logout();

		//redirect them to the login page
		$this->session->set_flashdata('message', $this->user_model->messages());
		redirect('user/login', 'refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */