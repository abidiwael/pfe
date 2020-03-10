<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projets extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('projets_model','projets');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('projets_view');
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->projets->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $projet) {
			$no++;	 
			$row = array();
			$row[] = $projet->titre;
			$row[] = $projet->date_debut;
			$row[] = $projet->date_fin;
			$row[] = $projet->date_creation;
			$row[] = $projet->date_modification;
			$row[] = $projet->id_entreprises ;
			/*if($tache->photo)
				$row[] = '<a href="'.base_url('upload/'.$tache->photo).'" target="_blank"><img src="'.base_url('upload/'.$tache->photo).'" class="img-responsive" /></a>';
			else
				$row[] = '(No photo)';*/

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_projet('."'".$projet->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Modifier</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_projet('."'".$projet->id."'".')"><i class="glyphicon glyphicon-trash"></i> Supprimer</a>';
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->projets->count_all(),
						"recordsFiltered" => $this->projets->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->projets->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(		
				'titre' => $this->input->post('titre'),
				'date_debut' => $this->input->post('date_debut'),
				'date_fin' => $this->input->post('date_fin'),
				'date_creation' => $this->input->post('date_creation'),
				'date_modification' => $this->input->post('date_modification'),
				'id_entreprises' => $this->input->post('id_entreprises'),
			);

		
		$insert = $this->projets->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
				'titre' => $this->input->post('titre'),
				'date_debut' => $this->input->post('date_debut'),
				'date_fin' => $this->input->post('date_fin'),
				'date_creation' => $this->input->post('date_creation'),
				'date_modification' => $this->input->post('date_modification'),
				'id_entreprises' => $this->input->post('id_entreprises'),
			);


		$this->projets->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		//delete file
		$projet = $this->projets->get_by_id($id);
		$this->projets->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		
		if($this->input->post('titre') == '')
		{
			$data['inputerror'][] = 'titre';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}	 		 
		if($this->input->post('date_debut') == '')
		{
			$data['inputerror'][] = 'date_debut';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('date_fin') == '')
		{
			$data['inputerror'][] = 'date_fin';
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
