<!-- <script type="text/javascript">//$(function () { $(document).tooltip({  track: true, items: ".vuelta_pc", content: function () { return $(this).prop('title'); }  }); });</script>-->
<script type="text/javascript"> 
	$(document).ready(function() { 
		$("#tablaReporte").tablesorter(); 
		$('.dif').hide(); 
	}); 
</script>
<script type="text/javascript">
	$(document).ajaxError(function(e,jqXHR,ajaxSettings,thrownError) {
    if(jqXHR.status == '403' || jqXHR.status == '500'){ window.location = '/fenix/users/login/';  }
    else{ 
    	notification('error','Error Encountered',thrownError+' (HTTP CODE: '+jqXHR.status+')'+ajaxSettings.url);
        console.log(e,'event'); console.log(jqXHR,'xhr response'); console.log(ajaxSettings,'$.ajax settings');
    }
});
</script>
<script type="text/javascript">	jQuery(function($){$('#btnStart').click(function(event){$('#imgLOADER').css('display','block');});});</script>
<!--
<script type="text/javascript">$('#tablaReporte').dragtable();</script> 
<script type="text/javascript"> $('#tablaSumario').dragtable();</script>
<script type="text/javascript">$(document).ready(function(){$('table').each(function(){	$(this).dragtable({	placeholder: 'dragtable-col-placeholder test3',items: 'thead th:not( .notdraggable ):not( :has( .dragtable-drag-handle ) ), .dragtable-drag-handle',appendTarget: $(this).parent(),	scroll: true});	})});</script>
-->
<script type="text/javascript">	
	
	$(function(){ 
		$.fn.toggleClick = function(){
		    var methods = arguments, // store the passed arguments for future reference
		    count = methods.length; // cache the number of methods 

		    //use return this to maintain jQuery chainability
		    return this.each(function(i, item){
		        // for each element you bind to
		        var index = 0; // create a local counter for that element
		        $(item).click(function(){ // bind a click handler to that element
		            return methods[index++ % count].apply(this,arguments); // that when called will apply the 'index'th method to that element
		            // the index % count means that we constrain our iterator between 0 and (count-1)
		        });
		    });
		};
		function calcular(hMenor,hMayor){			
			if(hMenor=="") hMenor=hMayor;
			var horas1=hMenor.split(":"); /*Mediante la función split separamos el string por ":" y lo convertimos en array. */ 
			var horas2=hMayor.split(":");
			
			var h1=new Date();
			var h2=new Date();
				
			h1.setHours(parseInt(horas1[0]));
			h2.setHours(parseInt(horas2[0]));

			h1.setMinutes(parseInt(horas1[1]));
			h2.setMinutes(parseInt(horas2[1]));

			h1.setSeconds(parseInt(horas1[2]));
			h2.setSeconds(parseInt(horas2[2]));

			//Diferencia en milisegundos
			var diferencia= h2.getTime()-h1.getTime();
			//Milisegundos convertidos a minutos divididos entre 60 para obtener las horas. Usamos Math.floor() para quedarnos con los enteros.
			var difHours= Math.floor((diferencia*0.0000166666667)/60);
			//Milisegundos convertidos a minutos. Usamos Math.floor() para quedarnos con los enteros.
			var difMins= Math.floor(diferencia*0.0000166666667);
			console.log(difMins);
			//Aplicamos modulo 1 para quedarnos con los decimales, los cuales multiplicamos por 60 para obtener los segundos
			var difSecs= Math.floor(((diferencia*0.0000166666667)%1)*60);
			console.log(difSecs);

			var nan = isNaN(diferencia);
			if(nan==true){
				return "";
			}else{
				return difMins+":"+difSecs;	
			}

					
		} 
		$('#btnStart').click(function(event){  $('#ReporteRutaId').val(""); }); 
		
		$('.header').addClass("toggle");		
		$('.header').click(function(){
			//$(this).addClass("clicked"); 
			var ix = $(this).index();
			console.log("Index del header: "+ix);
			var hMenor="";
			if($(this).hasClass("toggle")) {			
				setTimeout(function() {							
					$('.dif').hide(); 				
					$("tr").each(function(index){
						console.log("tr: "+index);
						var td= $( this ).find( "td:eq("+ix+")" ).text();
						var dif=calcular(hMenor, td);
						hMenor=td;
						console.log(td+" ("+dif+")");
						if(dif==""){
							$( this ).find( "td:eq("+ix+") > span" ).hide();
						}else{
							$( this ).find( "td:eq("+ix+") > span" ).html("("+dif+")").show();
						}	
					});
				}, 100);
			$(this).removeClass("toggle");
			}else {
		        $('.dif').hide(); 
		        $(this).addClass("toggle");
		    }				
		});		
	});
</script>

<!-- Métodos AJAX para select boxes dinámicos -->
<?php
/* Actualizar autobuses por ruta */
$this->Js->get('#ReporteRutaId')->event('change',
	$this->Js->request(
	    array('controller' => 'reportes', 'action' => 'get_itinerarioIndex'),
	        array(
	            'async' => true, 
	            'update' => '#ReporteItinerarioId',
	            'dataExpression' => true, 
	            'method' => 'post',
	            'data' => $this->Js->serializeForm(array('isForm' => true, 'inline' => true))
	        )
	    )
);
?>

<h2 class="analizerTitle">Reporte de Despacho de Unidades</h2>
<?php
	echo $this->Form->create();
	echo $this->Form->input('ruta_id',array('Label'=>'Ruta','type'=>'select','options'=>$rutas,'empty'=>'- Elige una ruta -','default'=>array('0'=>'(TODAS)')));
	echo $this->Form->input('itinerario_id',array('label'=>'Itinerario','type'=>'select','empty'=>'- Elige un Itinerario -'));
	echo $this->Form->input('fecha', array('label'=>'Fecha:','type'=>'text','id'=>'select_date','before' => '<div class="input text fecha">', 'after' =>"</div>".$this->Html->div('datepicker_img w100p fl pl460p pa', $this->Html->image('calendar.png',array('alt' => 'Fecha','width'=>'28px','height'=>'28px')),array('id' => 'datepicker_img')).$this->Html->div('datepicker fl pl460p pa', ' ' ,array('id' => 'datepicker'))));
	echo $this->Form->end(array('label'=>'Generar reporte','id'=>'btnStart'));	

	echo $this->Html->image('loader_blue_64.gif',array('id'=>'imgLOADER','alt'=>'Cargando','style'=>'display:none;','class'=>'img_load'));
	if (!empty($resultado)) { 
		#die(pr($resultado));
		#pr($resultado['PC']);
		#pr($resultado['Detalles']);
?>
  <h2>Despacho de unidades</h2>

  	<!-- GRAFICA TIEMPO VS PC -->
  	<style> #chartTiempoPC{ display: none;} #chartTiempoPC svg { height: 600px;}</style>
  	<div id="chartTiempoPC">
	  <svg></svg>
	</div>

  <table id="tablaReporte"class="tablesorter">
	  <thead>
	  <tr>
		<th data-header="id">Unidad</th>
		<?php $c = 0; $idpc = array();
		foreach($resultado['PC'] as $p){ 
			if($p['PC']['tipo'] == 1){echo "<th title='".$p['PC']['descripcion']." - FARO: ".$p['PC']['faro']."'>".$p['PC']['alias']." (LLEGA/SALE)</th>"; }
			else{echo "<th title='".$p['PC']['descripcion']." - FARO: ".$p['PC']['faro']."'>".$p['PC']['alias']."</th>"; }
			$idpc[$c] = $p['PC']['id'];
			$c++;
		} ?>
	  </tr>
	  </thead>
	  <tbody>
	  	<?php $c2 = 0;
	  	foreach($resultado['Detalles'] as $d) { 
	  		echo "<tr>"; 
	  			#echo "<td>".$d['autobus_id']."</td>";
	  			echo "<td>". $this->Form->postLink($d['numeconom'],array('action'=>'index'),array('data'=>array('Reporte'=>array('idAutobus'=>$d['autobus_id'],'fecha'=>$fechaDesp)),'target'=>'_blank','title'=>"Reporte individual de la unidad ".$d['numeconom']))."</td>";
	  			for($c2 = 0; $c2 < $c; $c2++){
					if(isset($d[$idpc[$c2]]) && !empty($d[$idpc[$c2]])){ echo "<td>".$d[$idpc[$c2]]." <span class='dif'></span></td>";}
					else{ echo "<td class='tdError'>NO PASÓ <span class='dif'></span></td>";}
	  			}
	  		echo "</tr>";
	  		$c2++;
		}
  		?>
  	</tbody>
  </table>
<?php
}
?>
<script type="text/javascript">
 $(document).ready(function(){ 
 	$("#datepicker_img img").click(
 		function(){ 
 			$("#datepicker").datepicker( { 
 				dateFormat: 'yy-mm-dd', 
 				onSelect: function(dateText, inst){ 
 					$('#select_date').val(dateText); 
 					$("#datepicker").datepicker("destroy"); 
 				} 
 			} 
 			); 
 		}); 
 });
// Traducción al español para textos de datepicker
$(function($){ $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
});
</script>