<!DOCTYPE html>
<html>
   <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PERSONNELS</title>
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
        <h1 style="font-size:20pt">COMPTES DES PERSONNELS</h1>

        <h3>Données Personnels</h3>
        <br />
        <button class="btn btn-success" onclick="add_personnel()"><i class="glyphicon glyphicon-plus"></i> Ajouter Personnel</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Actualiser</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>	

                <tr>
				    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Telephone</th>
                    <th>E_mail</th>
                    <th>Adresse</th>
					<th>Poste</th>
					<th>Date de création</th>
					<th>date de modification</th>
                    <th>Login</th>
                    <th>Password</th>
                    <th>Id Entreprises</th>
					<th>Photo</th>
                    <th style="width:300px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Telephone</th>
                    <th>E_mail</th>
                    <th>Adresse</th>
					<th>Poste</th>
					<th>Date de création</th>
					<th>date de modification</th>
                    <th>Login</th>
                    <th>Password</th>
                    <th>Id Entreprises</th>
					<th>Photo</th>
                    <th>Action</th>
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
            "url": "<?php echo site_url('personnels/ajax_list')?>",
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

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
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



function add_personnel()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Ajouter Personnels'); // Set Title to Bootstrap modal title

    $('#photo-preview').hide(); // hide photo preview modal

    $('#label-photo').text('Ajouter Photo'); // label photo upload
}

function edit_personnel(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('personnels/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
			$('[name="nom"]').val(data.nom);
			$('[name="prenom"]').val(data.prenom);
            $('[name="telephone"]').val(data.telephone);
            $('[name="email"]').val(data.email);
            $('[name="adresse"]').val(data.adresse);
			$('[name="poste"]').val(data.poste);
			$('[name="date_creation"]').val(data.date_creation);
			$('[name="date_modification"]').val(data.date_modification);
            $('[name="login"]').val(data.login);
            $('[name="password"]').val(data.password);
		    $('[name="id_entreprises"]').val(data.id_entreprises);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Modifier personnel'); // Set title to Bootstrap modal title

            $('#photo-preview').show(); // show photo preview modal

            if(data.photo)
            {
                $('#label-photo').text('Changer la Photo'); // label photo upload
                $('#photo-preview div').html('<img src="'+base_url+'upload/'+data.photo+'" class="img-responsive">'); // show photo
                $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.photo+'"/> Supprimer la photo lors de l enregistrement '); // remove photo

            }
            else
            {
                $('#label-photo').text('Ajouter un Photo'); // label photo upload
                $('#photo-preview div').text('(pas de photo)');
            }


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
        url = "<?php echo site_url('personnels/ajax_add')?>";
    } else {
        url = "<?php echo site_url('personnels/ajax_update')?>";
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
            $('#btnSave').text('save'); //change button text
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

function delete_personnel(id)
{
    if(confirm('Voulez-vous vraiment supprimer ces données?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('personnels/ajax_delete')?>/"+id,
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
                <h3 class="modal-title">Personnels Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nom</label>
                            <div class="col-md-9">
                                <input name="nom" placeholder="Nom" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">PRENOM</label>
                            <div class="col-md-9">
                                <input name="prenom" placeholder="Prenom" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                       
						<div class="form-group">
                            <label class="control-label col-md-3">TELEPHONE</label>
                            <div class="col-md-9">
                                <input name="telephone" placeholder="Telephone" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						 <div class="form-group">
                            <label class="control-label col-md-3">E_MAIL</label>
                            <div class="col-md-9">
                                <input name="email" placeholder="E_mail" class="form-control" type="email">
                                <span class="help-block"></span>
                            </div>
                        </div> 
						<div class="form-group">
                            <label class="control-label col-md-3">ADRESSE</label>
                            <div class="col-md-9">
                                <input name="adresse" placeholder="Adresse" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">Poste</label>
                            <div class="col-md-9">
                                <select name="poste" class="form-control">
                                    <option value="">--Sélectionner poste--</option>
                                    <option value="Administrateur">Administrateur</option>
                                    <option value="Chef de projet">Chef de projet</option>
									<option value="Technicien">Technicien</option>
									<option value="Autres">Autres</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">Date de creation</label>
                            <div class="col-md-9">
                                <input name="date_creation" placeholder="Date de la creation" class="form-control" type="datetime-local">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">Date de modification</label>
                            <div class="col-md-9">
                                <input name="date_modification" placeholder="Date de modification" class="form-control" type="datetime-local">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">LOGIN</label>
                            <div class="col-md-9">
                                <input name="login" placeholder="Login" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">PASSWORD</label>
                            <div class="col-md-9">
                                <input name="password" placeholder="Password" class="form-control" type="password">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">ID_ENTREPRISE</label>
                            <div class="col-md-9">
                                <input name="id_entreprises" placeholder="ID_entreprises" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        
                        <div class="form-group" id="photo-preview">
                            <label class="control-label col-md-3">Photo</label>
                            <div class="col-md-9">
                                (pas de photo)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo">Ajouter Photo</label>
                            <div class="col-md-9">
                                <input name="photo" type="file">
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