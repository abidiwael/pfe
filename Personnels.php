<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personnels extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('personnels_model','personnels');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('personnels_view');
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->personnels->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $personnel) {
			$no++;	
			$row = array();
			$row[] = $personnel->nom;
			$row[] = $personnel->prenom;
			$row[] = $personnel->telephone;
			$row[] = $personnel->email;
			$row[] = $personnel->adresse;
			$row[] = $personnel->poste;
			$row[] = $personnel->date_creation;
			$row[] = $personnel->date_modification;
			$row[] = $personnel->login;
			$row[] = $personnel->password;
			$row[] = $personnel->id_entreprises;
			if($personnel->photo)
				$row[] = '<a href="'.base_url('upload/'.$personnel->photo).'" target="_blank"><img src="'.base_url('upload/'.$personnel->photo).'" class="img-responsive" /></a>';
			else
				$row[] = '(No photo)';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_personnel('."'".$personnel->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Modifier</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_personnel('."'".$personnel->id."'".')"><i class="glyphicon glyphicon-trash"></i> Supprimer</a>';
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->personnels->count_all(),
						"recordsFiltered" => $this->personnels->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->personnels->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(	
				'nom' => $this->input->post('nom'),
				'prenom' => $this->input->post('prenom'),
				'telephone' => $this->input->post('telephone'),
				'email' => $this->input->post('email'),
				'adresse' => $this->input->post('adresse'),
				'poste' => $this->input->post('poste'),
				'date_creation' => $this->input->post('date_creation'),
				'date_modification' => $this->input->post('date_modification'),
				'login' => $this->input->post('login'),
				'password' => $this->input->post('password'),
				'id_entreprises' => $this->input->post('id_entreprises'),
			);

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			$data['photo'] = $upload;
		}

		$insert = $this->personnels->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
				'nom' => $this->input->post('nom'),
				'prenom' => $this->input->post('prenom'),
				'telephone' => $this->input->post('telephone'),
				'email' => $this->input->post('email'),
				'adresse' => $this->input->post('adresse'),
				'poste' => $this->input->post('poste'),
				'date_creation' => $this->input->post('date_creation'),
				'date_modification' => $this->input->post('date_modification'),
				'login' => $this->input->post('login'),
				'password' => $this->input->post('password'),
				'id_entreprises' => $this->input->post('id_entreprises'),
			);

		if($this->input->post('remove_photo')) // if remove photo checked
		{
			if(file_exists('upload/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
				unlink('upload/'.$this->input->post('remove_photo'));
			$data['photo'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			
			//delete file
			$personnel = $this->personnels->get_by_id($this->input->post('id'));
			if(file_exists('upload/'.$personnel->photo) && $personnel->photo)
				unlink('upload/'.$personnel->photo);

			$data['photo'] = $upload;
		}

		$this->personnels->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		//delete file
		$personnel = $this->personnels->get_by_id($id);
		if(file_exists('upload/'.$personnel->photo) && $personnel->photo)
			unlink('upload/'.$personnel->photo);
		
		$this->personnels->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _do_upload()
	{
		$config['upload_path']          = 'upload/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100; //set max size allowed in Kilobyte
        $config['max_width']            = 1000; // set max width image allowed
        $config['max_height']           = 1000; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		return $this->upload->data('file_name');
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nom') == '')
		{
			$data['inputerror'][] = 'nom';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}	 		 
		if($this->input->post('prenom') == '')
		{
			$data['inputerror'][] = 'prenom';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('telephone') == '')
		{
			$data['inputerror'][] = 'telephone';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('email') == '')
		{
			$data['inputerror'][] = 'email';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('adresse') == '')
		{
			$data['inputerror'][] = 'adresse';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('poste') == '')
		{
			$data['inputerror'][] = 'poste';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('date_creation') == '')
		{
			$data['inputerror'][] = 'date_creation';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('date_modification') == '')
		{
			$data['inputerror'][] = 'date_modification';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('login') == '')
		{
			$data['inputerror'][] = 'login';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('password') == '')
		{
			$data['inputerror'][] = 'password';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('id_entreprises') == '')
		{
			$data['inputerror'][] = 'id_entreprises';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}

		
	}

}
