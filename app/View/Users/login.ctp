
      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-9">
          <div class="row">
            <div class="col-lg-12">
               <img width="900" class="img-responsive img-rounded" src="/images/SMS-marketing.jpg"/>
            </div>
          </div>
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Entrar</button>
          </p>
          <div class="jumbotron">
            <h1>Mensajes completamente Gratis!!!</h1>
            <p>Envia mensajes gratis a las principales compañias de celulares en Mexico, solo registrate con tu numero telefonico o con tu cuenta de facebook. 
            </p>
      
          </div>
        </div><!--/span-->

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
            <h2>Entrar <span class="glyphicon glyphicon-envelope pull-right"></span> </h2>
                <form id="UserLoginForm" action="/" method="post" class="form-horizontal" role="form">
                  <div class="form-group">
                    <label for="UserUsername" class="col-sm-4 control-label txt-lft">Usuario</label>
                    <div class="col-sm-8">
                      <input type="text" name="data[User][username]" class="form-control" id="UserUsername" placeholder="Usuario">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="UserPassword" class="col-sm-4 control-label txt-lft">Contraseña</label>
                    <div class="col-sm-8">
                      <input type="password" name="data[User][password]" class="form-control" id="UserPassword" placeholder="Password">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                        <div class="submit">
                            <input class="btn btn-default txt-lft" type="submit" value="Login">
                        </div>
                    </div>
                  </div>
                </form>
        </div><!--/span-->
      </div><!--/row-->

      <hr>
<script>
      
     // This is called with the results from from FB.getLoginStatus().
    function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      document.location.href="/users/loginSocial";
      //testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '828828913798539',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      
      console.log(response);
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }


</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->
<?if($_GET['test']==1):?>
<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>

<div id="status">
</div>
<?endif;?>



