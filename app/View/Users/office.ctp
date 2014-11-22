<style>

</style>
<?php 

if($status==0) {

?>
	<div class="sendMsj">
		<div><h2>Bienvenido:<?=$name?> | <a href="/users/logout">Salir</a>   </div>
		<br>
		Introduce el codigo que te llego a tu cel por medio de un sms 
		<br><br>
		<form action="/users/val_code" method="post">
		Codigo:<br><br><input type="text" name="code"  maxlength="8">
		<br><br>
		<input type="submit" value="Comprobar"/>
		</form>
	</div>	
<?php
} elseif($status > 0) {
	
	?>
	 <style>
	 .sendMsj  input[type="text"]
	 {
	 	width: 300px !important;
	 }
	 </style>
	<!--<div class="row alert alert-info">
		<div class="col-lg-2 col-xs-12">Bienvenid@:<strong><?=$name?></strong> | </div>
		<div class="col-lg-2 col-xs-12"><div id="newContact" class="menu-simple">Agregar Contacto | </div></div>
		<div class="col-lg-2 col-xs-12"> <a href="/users/logout">Salir</a>  </div>
	</div>
	<hr>-->
	<div class="row newContact">
		<div class="col-lg-12">
			<div class="action">Nuevo Contacto</div>
			<form accept-charset="utf-8" method="post" onsubmit="event.returnValue = false; return false;" id="add-contact" action="/users/office" class="form-inline" role="form">
				<div style="display:none;">
					<input type="hidden" value="POST" name="_method">
				</div>        
				<div class="col-lg-1 ">
					<label for="ContactName" class="control-label">Nombre</label>
				</div>
				<div class="col-lg-3 ">
					<input class=" form-control"  type="text" id="ContactName" maxlength="10" name="data[Contact][name]">
				</div>
				<div class="col-lg-1">
					<label for="ContactPhone" class="control-label">Telefono</label>
				</div>
				<div class="col-lg-3">
					<input type="text" class="form-control" id="ContactPhone" required="required"  maxlength="10" name="data[Contact][phone]">
				</div>
				<div class="col-lg-3 ">
					<input type="hidden" id="ContactUserId" value="<?=$userID?>" name="data[Contact][user_id]">  
	    		<?=$this->Js->submit('Guardar', array(
	                'update'=>'#containerRequest',
	                'url' => "/contacts/savecontact",
	                'type' => 'post',
	                'async' => true,
	               'htmlAttributes' => array('class' => 'form-control btn btn-success pull-right')
	                /*'id' => "sbmt-".$n,*/
	              ));
	        ?>
	    		</div>
			</form> 
		</div>
		<br>
	</div>
	<hr>
	<div class="row cen">
		<div class="sendMsj">

		<div id="containerRequest"></div>
		<div class="action">Enviar Mensaje</div>
		<?php 

			echo $this->Form->select(
    			'Contactos',
    			array($contacs)
				);
		?>
		
		<form action="/users/send" method="post">
		Para:<br><br>
		<input type="text" name="num" placeholder="ejemplo 3921010000" id="idnum" maxlength="10">
		<br><br>
		Mensaje:<br><br>
		<textarea name="msj"  maxlength="140" style="width:300px" id="idmsj"/></textarea>
		<input type="hidden" name="ide" value="FyouM"/>
		<br><br>
		<input type="submit" class="btn btn-success" value="Enviar"/>
		</form>
		</div>
	</div>	
<?php } 
echo $this->Js->writeBuffer(); 
?>

<script>
var f5 = new LiveValidation('idmsj');
f5.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
f5.add( Validate.Length,{ maximum: 140,tooLongMessage: "Solo se permiten 140 caracteres te has excedido" } );
f5.add( Validate.Format,{pattern: /^[a-z A-Z0-9_,:.!?¡¿=+-]*$/i, failureMessage: "No caracteres especiales solo(_,:.!?¡¿=+-)" } );

var ContactName = new LiveValidation('ContactName');
ContactName.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
ContactName.add( Validate.Length,{ maximum: 140,tooLongMessage: "Solo se permiten 140 caracteres te has excedido" } );
ContactName.add( Validate.Format,{pattern: /^[a-z A-Z0-9_,:.!?¡¿=+-]*$/i, failureMessage: "No caracteres especiales solo(_,:.!?¡¿=+-)" } );
var f4 = new LiveValidation('idnum');
f4.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
f4.add( Validate.Format,{pattern: /^[0-9]*$/i, failureMessage: "Solo numeros" } );
f4.add( Validate.Length,{is: 10, wrongLengthMessage: "Deben ser 10 caracteres!"} );

var ContactPhone = new LiveValidation('ContactPhone');
ContactPhone.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
ContactPhone.add( Validate.Format,{pattern: /^[0-9]*$/i, failureMessage: "Solo numeros" } );
ContactPhone.add( Validate.Length,{is: 10, wrongLengthMessage: "Deben ser 10 caracteres!"} );
/*var f6 = new LiveValidation('ContactName');
f6.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
f6.add( Validate.Length,{ maximum: 10,tooLongMessage: "Solo se permiten 10 caracteres te has excedido" } );
f6.add( Validate.Format,{pattern: /^[a-z A-Z0-9]*$/i, failureMessage: "No caracteres especiales solo letras y numeros" } );
var f7 = new LiveValidation('ContactPhone');
f7.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
f7.add( Validate.Format,{pattern: /^[0-9]*$/i, failureMessage: "Solo numeros" } );
f7.add( Validate.Length,{is: 10, wrongLengthMessage: "Deben ser 10 caracteres!"} );*/
$(function() {
	show = true;
	$("#newContact").click(function(){
		if(show == true){
			$(".newContact").slideDown();
			show = false;
		}else{
			$(".newContact").slideUp();
			show = true;
		}
	});

	$("#Contactos").change(function(){ 
		//alert("hola");
		var opcion_select = $("#Contactos option:selected").val();
		//alert(opcion_select);
		$("#idnum").val(opcion_select);
	})



});
</script>