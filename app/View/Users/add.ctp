<div id="add" class="users form">
<?php echo $this->Form->create('User'); ?>
<?php
  App::uses('recaptchalib', 'Vendor');	
  require_once('recaptchalib.php');
  $publickey = "6Leik-cSAAAAAA_9O95i1O11ALu2_Qv_pRMXTXbn"; // you got this from the signup page
  
?>
    <fieldset>
        <legend><?php echo __('Registro de usuarios'); ?></legend>
        <?php echo $this->Form->input('username',array('label'=>'Usuario','id'=>'idUser'));
        echo $this->Form->input('password',array('label'=>'Contraseña','id'=>'idPass'));
        echo $this->Form->input('phone',array('label'=>'Telefono','id'=>'idCo','type'=>'text'));
        echo $this->Form->input('sponsor',array('label'=>'ID de quien te invita ','placeholder'=>'No es requerido','id'=>'idSpon','type'=>'text','style'=>'width:109px;'));
        /*echo $this->Form->input('role', array(
            'options' => array('admin' => 'Admin', 'author' => 'Author')
        ));*/
		echo recaptcha_get_html($publickey);
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Validar telefono y registrar')); ?>
</div>
<br>
<script>
var f1 = new LiveValidation('idUser');
f1.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
f1.add( Validate.Length,{minimum: 5, maximum: 10,wrongLengthMessage: "Minimo 5 caracteres, maximo 10 !",tooShortMessage:"Min 5 caracteres",tooLongMessage: "Max 10 caracteres" } );
f1.add( Validate.Format,{pattern: /^[a-zA-Z0-9_]*$/i, failureMessage: "No admite caracteres especiales" } );
//Validate.Format( 'live validation', { pattern: /validation/i, failureMessage: "Failed!" } );
var f2 = new LiveValidation('idPass');
f2.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
f2.add( Validate.Length,{minimum: 5, maximum: 10,wrongLengthMessage: "Minimo 5 caracteres, maximo 10 !",tooShortMessage:"Min 5 caracteres",tooLongMessage: "Max 10 caracteres" } );
f2.add( Validate.Format,{pattern: /^[a-zA-Z0-9_]*$/i, failureMessage: "No admite caracteres especiales" } );

var f3 = new LiveValidation('idCo');
f3.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );
f3.add( Validate.Format,{pattern: /^[0-9]*$/i, failureMessage: "Solo numeros" } );
f3.add( Validate.Length,{is: 10, wrongLengthMessage: "Deben ser 10 caracteres!"} );

var f4 = new LiveValidation('idSpon');
/*f4.add( Validate.Presence,{failureMessage: "No puede estar vacio!" } );*/
f4.add( Validate.Format,{pattern: /^[0-9]*$/i, failureMessage: "Solo numeros" } );
/*f4.add( Validate.Length,{is: 10, wrongLengthMessage: "Deben ser 10 caracteres!"} );*/

</script>
