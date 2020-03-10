<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conges extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('conges_model','conges');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('conges_view');
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->conges->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $conge) {
			$no++;	
			$row = array(); 
			$row[] = $conge->date_debut;
			$row[] = $conge->date_fin;
			$row[] = $conge->date_demande;
			$row[] = $conge->date_validation;
			$row[] = $conge->commentaire;
			$row[] = $conge->etat;
		

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_conge('."'".$conge->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Modifier</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_conge('."'".$conge->id."'".')"><i class="glyphicon glyphicon-trash"></i> Supprimer</a>';
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->conges->count_all(),
						"recordsFiltered" => $this->conges->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->conges->get_by_id($id);
		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(		
				
				'date_debut' => $this->input->post('date_debut'),
				'date_fin' => $this->input->post('date_fin'),
				'date_demande' => $this->input->post('date_demande'),
				'date_validation' => $this->input->post('date_validation'),
				'commentaire' => $this->input->post('commentaire'),
				'etat' => $this->input->post('etat'),
			);

		
		$insert = $this->conges->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
					'date_debut' => $this->input->post('date_debut'),
				'date_fin' => $this->input->post('date_fin'),
				'date_demande' => $this->input->post('date_demande'),
				'date_validation' => $this->input->post('date_validation'),
				'commentaire' => $this->input->post('commentaire'),
				'etat' => $this->input->post('etat'),
			);

		

		$this->conges->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		//delete file
		$conge = $this->conges->get_by_id($id);
		
		$this->conges->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

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
		if($this->input->post('date_demande') == '')
		{
			$data['inputerror'][] = 'date_demande';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
        /*if($this->input->post('date_validation') == '')
		{
			$data['inputerror'][] = 'date_validation';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}*/
		if($this->input->post('commentaire') == '')
		{
			$data['inputerror'][] = 'commentaire';
			$data['error_string'][] = '*Ce champs est obligatoire.';
			$data['status'] = FALSE;
		}
		if($this->input->post('etat') == '')
		{
			$data['inputerror'][] = 'etat';
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
