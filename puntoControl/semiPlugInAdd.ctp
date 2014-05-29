<!-- File: /app/View/PuntoControls/edit.ctp -->
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCQqTp2_r5AEhKFibr0Lj2JBSiXc9orHVs&sensor=false" type="text/javascript"></script>
<style>
      /*body{
        text-align:center;
      }*/
      
      #container{
        /*overflow: auto;*/
        margin: 20px auto;
        /*width: 1500px;*/
      }
      #tool{
        border: 1px dashed #C0C0C0;
        width: 350px;
        /*height: 600px;*/
        float: left;
        margin-left: 10px;
        padding: 5px;
        text-align:left;
      }
      .gmap3{
        border: 1px dashed #C0C0C0;
        /*width: 1200px;*/
        /*height: 600px;*/
        /*float: left;*/
        position: relative;
        padding-bottom: 40%;
        height: 0;
        overflow: hidden;
      }
      .gmap3 iframe{
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
      }
      .section{
        margin: 10px;
        padding: 10px;
      }
      #markerId{
        display: none;
      }
</style>

  <script type="text/javascript">
    $(function(){      
      //Array php con los puntos de control guardado como JSON en puntosControl
      var puntosControl= <?php echo json_encode($resultado)?>;      
      //Posicion del marker (verde) que se va agregar y centro del mapa
      var centerMap=[];      
      var zoom;
      //Muestra el radio inicial del marker (verde), en este caso el campo esta vacío, por lo tanto es 0.      
      var showRadio= true;

      //variable para centrar el mapa en medio de la ruta, toma la posicion media del arreglo (para obtener el PC medio de la ruta)
      var middle= Math.round(puntosControl.length/2)-1;
      console.log(middle);
      //Sí hay elementos en puntosControl centra mapa en medio de la ruta, se suma 0.01 para no empalmar el marker.
      if(puntosControl.length>0){        
        centerMap= [parseFloat(puntosControl[middle]['Puntocontrol']['latitud'])+0.01, parseFloat(puntosControl[middle]['Puntocontrol']['longitud'])+0.01];
        zoom=14;
        console.log("Array lat long");
        console.log(centerMap);        
      }else{//Sí no hay elementos en puntosControl, se usan las coordenadas por default de Mexico DF y un zoom de 4.
        var latitud=20.78863;
        var longitud=-99.15;
        centerMap=[latitud, longitud];
        zoom=4;
        console.log("Array lat long");
        console.log(centerMap);    
      }
      //Se llenan los campos de latitud y longitud inicial del nuevo marker (verde)
      $("#PuntocontrolLatitud").val(centerMap[0]);
      $("#PuntocontrolLongitud").val(centerMap[1]);
      //Valores de la posicion inicial del nuevo marker verde (punto de control)
      var value={latLng:centerMap, id:"PC"}; 
      //Se mandan parametros a la funcion que dibuja los markers y radios.
      //puntosControl= JSON con todos los PCs de la ruta, value= Datos del nuevo PC (marker verde), centerMap= centro del mapa y posicion inicial del nuevo PC (marker verde), zoom= valor del zoom del mapa, showRadio= true para mostrar radio inicial del nuevo PC, aplica para edit.ctp 
      drawMarkers(puntosControl, value, centerMap, zoom, showRadio);

    });
  </script>

<h2>Agregar Punto de Control</h2>
<?php if($this->Session->check('LastPoint.prRuta')) {?><div class="backContainer"><?php echo $this->Form->postLink("Volver al listado",array('action' => 'index'),array('data'=>array('puntocontrol'=>array('ruta_id'=>$this->Session->read('LastPoint.prRuta'))),'escape'=>false,'confirm' => '¿Volver al listado? Cambios no guardados se descartarán'));?></div><?php } ?>
<div id="tool">
	<?php
	echo $this->Form->create('Puntocontrol');
	echo $this->Form->input('ruta_id', array('type' => 'select', 'options' => $rutas, 'label' => 'Número de Ruta', 'empty' => '- Elige una ruta -'));
	echo $this->Form->input('descripcion',array('label' => 'Descipción'));
	echo $this->Form->input('alias',array('label' => 'Alias'));
	echo $this->Form->input('faro',array('label' => 'Faro'));
	echo $this->Form->input('tipo',array('label' => 'Tipo','type' => 'select', 'options' => array('Paso','Llega/Sale')));
	echo $this->Form->input('latitud',array('label' => 'Latitud'));
	echo $this->Form->input('longitud',array('label' => 'Longitud'));	
	echo $this->Form->input('radio',array('label'=>'Radio'));
	?>
	
	<?php
	echo $this->Form->end('Guardar Cambios');
	?>
</div>
<div id="map" class="gmap3">
	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d7098.94326104394!2d78.0430654485247!3d27.172909818538997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1385710909804" width="600" height="450" frameborder="0" style="border:0"></iframe>
</div>

<pre>
<?php    
    print_r($resultado);
?>
</pre>
