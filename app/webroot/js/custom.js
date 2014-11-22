function get_num_sms(){

    $.ajax({

        url: '/users/currentMsj',
        cache: false,
        type: 'POST',
        dataType: 'HTML',
        success: function (data) {
            $('#currentMsg').html(data);
          
        }
    });
}
function get_invitate(){

    $.ajax({

        url: '/users/currentInvitate',
        cache: false,
        type: 'POST',
        dataType: 'HTML',
        success: function (data) {
            $('#totalInv').html(data);
          
        }
    });
}
$(function() {
  
  get_num_sms();
  get_invitate();
  console.log("Begin script");


  function get_num_sms(){

    $.ajax({

        url: '/users/currentMsj',
        cache: false,
        type: 'POST',
        dataType: 'HTML',
        success: function (data) {
            $('#currentMsg').html(data);
          
        }
    });
  }

  $('#all-contacts').on('click','[opt="crud1"]',function(e){
  //$('[opt="crud1"]').click(function(){
  	console.log("click crud");

  	id = $(this).attr('id');



  	splitData =id.split('-'); 

  	act = splitData[0];
  	newID = splitData[1];

    console.log(newID+':'+act);

    

    if(act == 'edit'){

    	nam = $(this).attr('data-name');
    	num = $(this).attr('data-num');

    	$("#nomContact").val(nam);
    	$("#numContact").val(num);
    	$("#idContact").val(newID);
    	$("#optContact").val('u');

    	$('#modalEdit').modal('show');
    }
    else if(act == 'delete'){

    	nam = $(this).attr('data-name');
    	$("#contact-del").text(nam);
    	$("#idContact2").val(newID);
    	$('#modalDelete').modal('show');
    	

    }else if(act == 'add'){

    	$("#optContact").val('a');

    	$('#modalEdit').modal('show');

    }
  	/*$.ajax({

	    url: '/contacts/crud',
	    cache: false,
	    type: 'POST',
	    dataType: 'HTML',
	    data: ({action:act,ide:newID}),
	    success: function (data) {
	        $('#all-contacts').html(data);
	    }
	})
  	*/
  });

   /*$('cancelModal').click(function(){

	   	$('#modalEdit').modal('hide');
	   	$('#modalCancel').modal('hide');
   });
	*/
   $("#formUpdate").submit(function( event ) {

  		//$( "span" ).text( "Not valid!" ).show().fadeOut( 1000 );
  		var areAllValid = LiveValidation.massValidate( [ f1, f3 ] );

  		if(!areAllValid){
  			alert("Hay un error en los datos ingresados favor de verificar");
  			return false;
  			event.preventDefault();
  		}


    	a = $("#numContact").val();
  		b = $("#nomContact").val();
    	c = $("#idContact").val();
    	e = $("#optContact").val();

    	$.ajax({

		    url: '/contacts/crud',
		    cache: false,
		    type: 'POST',
		    dataType: 'HTML',
		    data: ({num:a,nom:b,ide:c,act:e}),
		    success: function (data) {
		        $('#all-contacts').html(data);
		        $('#modalEdit').modal('hide');
		    }
		})


  		event.preventDefault();
	});

   $('#modalDelete').on('click','#del-finally',function(e){

   		console.log("ok in delete");
   		d = $("#idContact2").val();
   		$.ajax({

		    url: '/contacts/crud',
		    cache: false,
		    type: 'POST',
		    dataType: 'HTML',
		    data: ({ide:d,act:'d'}),
		    success: function (data) {
		        $('#all-contacts').html(data);
		        $('#modalDelete').modal('hide');
		    }
		})


   });
  /*$.ajax({
    url: '/contacts/crud',
    cache: false,
    type: 'GET',
    dataType: 'HTML',
    data: ({action:act,ide:id}),
    success: function (data) {
        $('#context').html(data);
    }
  });*/

});