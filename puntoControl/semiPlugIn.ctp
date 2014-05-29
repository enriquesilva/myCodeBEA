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
      var latitud=parseFloat($("#PuntocontrolLatitud").val());
      var longitud=parseFloat($("#PuntocontrolLongitud").val());
      var centerMap= [latitud, longitud];
      var value={latLng:[latitud, longitud], id:"PC"};
      var zoom=14;
      var alias=$("#PuntocontrolAlias").val();
      var showRadio= true;

      drawMarkers(puntosControl, value, centerMap, zoom, showRadio);
    
      //Borra el marker negro (punto de control) que se va a editar para poner el marker verde draggable  
      $("#map").gmap3({
        clear:{id:alias}
      });
      $("#map").gmap3({
        clear:{tag:alias}
      });	 

    });
  </script>

<h2>Editar Punto de Control</h2>
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
<!--	
<pre>
<?php    
    //print_r($resultado);
?>
</pre>
-->