<?php
session_start();
if($_SESSION['genero'] == 'M'){$nombre = 'PANEL ESPARTACUS';}
else{$nombre = 'PANEL ARTEMISA';}
?>
<style>
/*@-webkit-keyframes texto {
   0% { opacity: 0;  }
    50% { opacity: 0.50;  }
   100% { opacity: 0;  }
  
    }
 */
#texto_rotativo {
	/*
   -webkit-animation-name: texto;
   -webkit-animation-duration: 20s;
   -webkit-animation-iteration-count: infinite;
   -moz-animation-name: texto;
   -moz-animation-duration: 20s;
   -moz-animation-iteration-count: infinite;
   -o-animation-name: texto;
   -o-animation-duration: 20s;
   -o-animation-iteration-count: infinite;
   animation-name: texto;
   animation-duration: 20s;
   animation-iteration-count: infinite;
   */
   width: 70%;
   margin-left:20%;
   margin-top: 2%;
   text-align:left;
   font-size:130%;
   color:#8899AA;
   /*color:#34495e;
   border-radius:7px;
   border:2px #AABBCC solid;*/
   float:left;
}
#recomendaciones {
	width:99%;
	font-family: 'Cabin', Arial, sans-serif;
	margin-top:2%;
	font-size:90%;
	color:#006;
	float:left;
}
</style>
<div id="texto_rotativo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $nombre; ?></div>
<!--<div id="texto_rotativo">PANEL PEGASUS</div> ESTE EL DE USUARIO DE TORNEOS-->
<div id="recomendaciones">
<b>&bull; </b>Algunos de sus datos personales serán visibles para el/los administrador/es de ligas en las que estás inscrito, sólo para fines de gestión de la liga.<br /><br />
<b>&bull; </b>Sus datos personales No serán vendidos a ningún tercero.<br /><br />
<b>&bull; </b>Asegúrese de conocer al club u organización para las inscripciones que se requiere pago.<br /><br />
<b>&bull; </b>El administrador de la liga en la que te inscribes es el total responsable económico de ésta y el responsable para solucionar cualquier conflicto bien online o presencial.<br /><br />
<b>&bull; </b>Una vez realizado un pago online a través de paypal, deberá esperar unos segundos a retornar automáticamente a esta web para procesar el pago, si no ocurre así es porque el administrador no tiene la cuenta correctamente configurada o ha ocurrido un error.<br /><br />
<b>&bull; </b>Si su pago online No ha sido procesado correctamente deberá contactar con el administrador a través del menú de jugador -> Contacto Administrador, enviarle el id de transacción paypal para que marque su pago manualmente, si se produce demora en la gestión contacte con nosotros.<br /><br />
<b>&bull; </b>Los pagos de inscripciones efectuados a través de paypal no podrán ser eliminadas por el administrador, se solicitará a tu equipo que confirme vía e-mail que ha recibido la devolución del dinero.<br /><br />
<b>&bull; </b>Puede eliminar su inscripción en ligas si no ha realizado ningún pago, si ya lo ha realizado póngase en contacto con el administrador de la liga lo antes posible.<br /><br />
<b>&bull; </b>Una vez generado el calendario de la liga ya no es posible eliminar tu inscripción ni la devolución de la inscripción.<br />

<br />
</div>