<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'Simple SMS Gratis');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		/*echo $this->Html->css('cake.generic');*/

		echo $this->fetch('meta');


	?>
    <meta name="viewport" content="width=device-width" />
		<link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="/bootstrap/css/bootstrap-theme.css">
		<link rel="stylesheet" href="/bootstrap/css/offcanvas.css">
		<link rel="stylesheet" href="/css/joma.css">
		<script type="text/javascript" src="/js/livevalidate.js"></script>
		
</head>
<body>
	
	 <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!--<a class="navbar-brand" href="/">Mi cuenta</a>-->
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/">INICIO</a></li>
            <li><a href="/contacts">CONTACTOS</a></li>
           <!-- <li><a href="/users/add">MIS DATOS </a></li>
            <li><a href="#contact">CONTACTOS</a></li>
            <li><a href="#contact">REPORTES</a></li>
            <li><a href="#contact">AVANZADO</a></li>-->
          </ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="container">
	<script type="text/javascript" src="/js/jquery.js"></script>

    <?php echo $this->Session->flash(); ?>
    <div class="row alert alert-info">
        <div class="col-lg-2 col-xs-12">Bienvenid@:<strong><?=$name?></strong> | </div>
        <!--<div class="col-lg-2 col-xs-12"><div id="newContact" class="menu-simple">Agregar Contacto | </div></div>-->
        <div class="col-lg-6 col-xs-12"> Invita a tus amigos y pasa tu numero de ID : <strong><?=$userID?></strong> para aumentar tus mensajes por dia !!!</div>
        <div class="col-lg-2 col-xs-12"><span  title="Mensajes restantes" style="font-size:20px"  class="glyphicon glyphicon-envelope"></span><span class="icon-post-head" id="currentMsg">?</span>
        <span title="Amigos invitados registrados" style="font-size:20px"  class="glyphicon glyphicon-user"></span>
        <span class="icon-post-head" id="totalInv">0</span> 
        </div>
        <div class="col-lg-2 col-xs-12"> <a href="/users/logout">Salir</a>  </div>
    </div>
      <hr>
 
		<?php echo $this->fetch('content'); ?>

    </div><!--/.container-->
    <!--*************************************************-->
  <footer >
	  <div id="footer" style="display:block"><?php //echo $this->element('sql_dump'); ?>
        Simple sms gratis
    </div>
  </footer>
	<script src="/bootstrap/js/jquery10.js"></script>
	<script src="/js/jquery1.9.js"></script>
  <script src="/bootstrap/js/bootstrap.js"></script>
	<script src="/js/custom.js"></script>
</body>
</html>
<script>
	$(document).ready(function () {
        $('[data-toggle=offcanvas]').click(function () {
          $('.row-offcanvas').toggleClass('active')
        });
  	});

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44267516-2', 'syspaweb.com');
  ga('send', 'pageview');

</script>
