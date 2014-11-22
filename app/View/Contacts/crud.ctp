 <?php echo $msg; ?>
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