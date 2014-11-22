<div id="all-contacts" class="table-responsive">
  <button opt='crud1' id='add-1' type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Agregar</button>	
  <table class="table table-striped">
    <tr>
    	<td>
    		Nombre
    	</td>
    	<td>
    		Telefono
    	</td>
    	<td>
    		Editar
    	</td>
    	<td>
    		Eliminar
    	</td>
    </tr>
    <?php 
    	foreach ($contacts as $key => $arrayData) {			
    ?>
    <tr>
    	<td>
    		<?=$arrayData['Contact']['name']?>
    	</td>

    	<td>
    		<?=$arrayData['Contact']['phone'];?>
    	</td>

    	<td>

    		<span data-name="<?=$arrayData['Contact']['name']?>" data-num="<?=$arrayData['Contact']['phone'];?>"  class="glyphicon glyphicon-pencil" opt='crud1'  id="edit-<?=$arrayData['Contact']['id'];?>" ></span>
    	</td>

    	<td>
    		<span data-name="<?=$arrayData['Contact']['name']?>" data-num="<?=$arrayData['Contact']['phone'];?>"   class="glyphicon glyphicon-trash" opt='crud1'  id="delete-<?=$arrayData['Contact']['id'];?>" ></span>
    	</td>
    <tr>
    <?php }
     ?>
  </table>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-md">
    <div class="modal-content">
		<form id="formUpdate" class="navbar-form navbar-left" role="search" >
		  <div class="form-group">
		    <input type="text" class="form-control" id='nomContact' placeholder="Nombre">
		    <input type="text" class="form-control" id='numContact' placeholder="Numero">
		    <input type="hidden" class="form-control" id='idContact'>
		    <input type="hidden" class="form-control" id='optContact'>
		  </div>
		  <button type="submit" class="btn btn-default">Actualizar</button>
		  <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		</form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-md">
    <div class="modal-content">
		<input type="hidden" class="form-control" id='idContact2'>
		<div class="delete-joma">
			Seguro que quieres eliminar el contacto: <span id="contact-del"></span>  !!!!!<span class="glyphicon glyphicon-warning-sign"></span> 
		</div>
		 <button id="del-finally" type="button" class="btn btn-default">Eliminar</button>
		 <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>
  </div>
</div>
<?php
echo $this->Js->writeBuffer(); // Write cached scripts
?>
<script>
	
	var f1 = new LiveValidation('nomContact');
	f1.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
	f1.add( Validate.Length,{minimum: 5, maximum: 10,wrongLengthMessage: "Minimo 5 caracteres, maximo 10 !",tooShortMessage:"Min 5 caracteres",tooLongMessage: "Max 10 caracteres" } );
	f1.add( Validate.Format,{pattern: /^[a-zA-Z0-9_]*$/i, failureMessage: "No admite caracteres especiales" } );

	var f3 = new LiveValidation('numContact');
	f3.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
	f3.add( Validate.Format,{pattern: /^[0-9]*$/i, failureMessage: "Solo numeros" } );
	f3.add( Validate.Length,{is: 10, wrongLengthMessage: "Deben ser 10 caracteres!"} );
</script>