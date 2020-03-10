<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Taches extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('taches_model','taches');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('taches_view');
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->taches->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $tache) {
			$no++;	
			$row = array(); 


			$row[] = $tache->titre;
			$row[] = $tache->description;
			$row[] = $tache->date_debut;
			$row[] = $tache->date_fin;
			$row[] = $tache->date_creation;
			$row[] = $tache->date_modification;
			$row[] = $tache->etat;
			$row[] = $tache->date_validation;
			$row[] = $tache->id_personnels;
			$row[] = $tache->id_projets;
		

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_tache('."'".$tache->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Modifier</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_tache('."'".$tache->id."'".')"><i class="glyphicon glyphicon-trash"></i> Supprimer</a>';
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->taches->count_all(),
						"recordsFiltered" => $this->taches->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->taches->get_by_id($id);
		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(		
				'titre' => $this->input->post('titre'),
				'description' => $this->input->post('description'),
				'date_debut' => $this->input->post('date_debut'),
				'date_fin' => $this->input->post('date_fin'),
				'date_creation' => $this->input->post('date_creation'),
				'date_modification' => $this->input->post('date_modification'),
				'etat' => $this->input->post('etat'),
				'date_validation' => $this->input->post('date_validation'),
				'id_personnels' => $this->input->post('id_personnels'),
				'id_projets' => $this->input->post('id_projets'),
			);

		
		$insert = $this->taches->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
				'titre' => $this->input->post('titre'),
				'description' => $this->input->post('description'),
				'date_debut' => $this->input->post('date_debut'),
				'date_fin' => $this->input->post('date_fin'),
				'date_creation' => $this->input->post('date_creation'),
				'date_modification' => $this->input->post('date_modification'),
				'etat' => $this->input->post('etat'),
				'date_validation' => $this->input->post('date_validation'),
				'id_personnels' => $this->input->post('id_personnels'),
				'id_projets' => $this->input->post('id_projets'),
			);

		

		$this->taches->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		//delete file
		$tache = $this->taches->get_by_id($id);
		
		$this->taches->delete_by_id($id);
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
		if($this->input->post('description') == '')
		{
			$data['inputerror'][] = 'description';
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
		if($this->input->post('etat') == '')
		{
			$data['inputerror'][] = 'etat';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
        if($this->input->post('date_validation') == '')
		{
			$data['inputerror'][] = 'date_validation';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('id_personnels') == '')
		{
			$data['inputerror'][] = 'id_personnels';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('id_projets') == '')
		{
			$data['inputerror'][] = 'id_projets';
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
