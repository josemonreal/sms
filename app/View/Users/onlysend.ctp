<div class="container center">

	<div class="boxMsj">
		<form action="/users/onlysend" method="post">
		Para:<br><br>
		<input type="text" name="num" placeholder="ejemplo 3921010000" id="idnum" maxlength="10">
		<br><br>
		Mensaje:<br><br>
		<textarea name="msj"  maxlength="140" style="width:300px" id="idmsj"/></textarea>
		<input type="hidden" name="ide" value="FyouM"/>
		<br><br>
		<input type="submit" value="Enviar"/>
	</div>

</div>

<script>
	var f5 = new LiveValidation('idmsj');
	f5.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
	f5.add( Validate.Length,{ maximum: 140,tooLongMessage: "Solo se permiten 140 caracteres te has excedido" } );
	f5.add( Validate.Format,{pattern: /^[a-z A-Z0-9_,:.!?¡¿=+-]*$/i, failureMessage: "No caracteres especiales solo(_,:.!?¡¿=+-)" } );	

	var f4 = new LiveValidation('idnum');
	f4.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
	f4.add( Validate.Format,{pattern: /^[0-9]*$/i, failureMessage: "Solo numeros" } );
	f4.add( Validate.Length,{is: 10, wrongLengthMessage: "Deben ser 10 caracteres!"} );
</script>