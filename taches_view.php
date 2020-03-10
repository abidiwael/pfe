<!DOCTYPE html>
<html>
   <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TACHES</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
   </head> 
<body>
    <div class="container">
        <h1 style="font-size:20pt">LISTES DES TACHES </h1>

        <h3>--> TACHES :</h3>
        <br />
        <button class="btn btn-success" onclick="add_tache()"><i class="glyphicon glyphicon-plus"></i> Ajouter Tache</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Actualiser</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>	

                <tr>
				    <th>TITRE</th>
                    <th>DESCRIPTION</th>
                    <th>DATE DE DEBUT</th>
                    <th>DATE DE FIN</th>
					<th>DATE DE CREATION</th>
					<th>DATE DE MODIFICATION</th>
					<th>ETAT</th>
					<th>DATE DE VALIDATION</th>
                    <th>ID PERSONNELS</th>
                    <th>ID PROJETS</th>
                    <th style="width:300px;">ACTION</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                    <th>TITRE</th>
                    <th>DESCRIPTION</th>
                    <th>DATE DE DEBUT</th>
                    <th>DATE DE FIN</th>
					<th>DATE DE CREATION</th>
					<th>DATE DE MODIFICATION</th>
					<th>ETAT</th>
					<th>DATE DE VALIDATION</th>
                    <th>ID PERSONNELS</th>
                    <th>ID PROJETS</th>
                    <th>ACTION</th>
            </tr>
            </tfoot>
        </table>
    </div>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('taches/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -2 ], //2 last column (photo)
                "orderable": false, //set not orderable
            },
        ],

    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});



function add_tache()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Ajouter une tache:'); // Set Title to Bootstrap modal title
}

function edit_tache(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('taches/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
			$('[name="titre"]').val(data.titre);
			$('[name="description"]').val(data.description);
            $('[name="date_debut"]').val(data.date_debut);
			$('[name="date_fin"]').val(data.date_fin);
            $('[name="date_creation"]').val(data.date_creation);
			$('[name="date_modification"]').val(data.date_modification);
			$('[name="etat"]').val(data.etat);
			$('[name="date_validation"]').val(data.date_validation);
            $('[name="id_personnels"]').val(data.id_personnels);
            $('[name="id_projets"]').val(data.id_projets);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Modifier la tache:'); // Set title to Bootstrap modal title

          
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Erreur de récupération de données de Ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('taches/ajax_add')?>";
    } else {
        url = "<?php echo site_url('taches/ajax_update')?>";
    }

    // ajax adding data to database

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Enregistrer'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_tache(id)
{
    if(confirm('Voulez-vous vraiment supprimer ces données?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('taches/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Erreur lors de la suppression des données');
            }
        });

    }
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Taches Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">TITRE</label>
                            <div class="col-md-9">
                                <input name="titre" placeholder="Titre" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">DESCRIPTION</label>
                            <div class="col-md-9">
                                <textarea name="description" placeholder="Description" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                       
						<div class="form-group">
                            <label class="control-label col-md-3">DATE DE DEBUT</label>
                            <div class="col-md-9">
                                <input name="date_debut" placeholder="Date debut" class="form-control" type="date">
                                <span class="help-block"></span>
                            </div>
                        </div>
						 <div class="form-group">
                            <label class="control-label col-md-3">DATE DE FIN</label>
                            <div class="col-md-9">
                                <input name="date_fin" placeholder="Date fin" class="form-control" type="date">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">DATE DE CREATION</label>
                            <div class="col-md-9">
                                <input name="date_creation" placeholder="DATE DE CREATION" class="form-control" type="datetime-local">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">DATE DE MODIFICATION</label>
                            <div class="col-md-9">
                                <input name="date_modification" placeholder="DATE DE MODIFICATION" class="form-control" type="datetime-local">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">ETAT</label>
                            <div class="col-md-9">
                                <select name="etat" class="form-control">
                                    <option value="">--Sélectionner etat--</option>
                                    <option value="en cours">en cours</option>
                                    <option value="terminer">terminer</option>
									<option value="bloque">Bloqué</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">DATE DE VALIDATION</label>
                            <div class="col-md-9">
                                <input name="date_validation" placeholder="DATE DE VALIDATION" class="form-control" type="datetime-local">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">ID DE PERSONNELS</label>
                            <div class="col-md-9">
                                <input name="id_personnels" placeholder="Id personnels" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">ID DE PROJETS</label>
                            <div class="col-md-9">
                                <input name="id_projets" placeholder="Id projets" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
					    
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Quitter</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>