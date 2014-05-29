<!-- File: /app/View/PuntoControls/add.ctp -->
<?php 
	#preguntar por coordenadas de punto anterior
	/*
	if($this->Session->check('LastPoint')){ 	
		$prevLong = $this->Session->read('LastPoint.Longitud');
		$prevLat = $this->Session->read('LastPoint.Latitud'); 	
	}else {
    $prevLong=0; $prevLat=0;
  }
  */
?>
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

      #data{
        
      }
      .section{
        margin: 10px;
        padding: 10px;
      }
      #markerId{
        display: none;
      }
      #guardar{
        height: 44px;
        border: 0px;
        width: 100px;
      }

      #gmap3-menu{
      background-color: #FFFFFF;
      width:170px;
      padding:0px;
      border:1px;
      cursor:pointer;
      border-left:1px solid #cccccc;
      border-top:1px solid #cccccc;
      border-right:1px solid #676767;
      border-bottom:1px solid #676767;
      }
      #gmap3-menu .item{
      font-family: arial,helvetica,sans-serif;
      font-size: 12px;
      text-align:left;
      line-height: 30px;
      border-left:0px;
      border-top:0px;
      border-right:0px;
      padding-left:30px;
      background-repeat: no-repeat;
      background-position: 4px center;
      }
      #gmap3-menu .item.itemA{
        background-image: url(images/icon_greenA.png);
      }
      #gmap3-menu .item.itemB{
        background-image: url(images/icon_greenB.png);
      }
      #gmap3-menu .item.zoomIn{
        background-image: url(images/zoomin.png);
      }
      #gmap3-menu .item.zoomOut{
        background-image: url(images/zoomout.png);
      }
      #gmap3-menu .item.centerHere{
        background-image: url(images/zoomout.png);
      }
      #gmap3-menu .item.hover{
        background-color: #d6e9f8;
      }
      #gmap3-menu .item.separator{
        border-bottom:1px solid #cccccc;
      }
</style>

<script type="text/javascript">
    $(function(){
      
      var descripcion=$("#PuntocontrolDescripcion").val();
      var alias=$("#PuntocontrolAlias").val();
      var faro=$("#PuntocontrolFaro").val();
      var tipo=$("#PuntocontrolTipo").val();
      //var latitud=parseFloat($("#PuntocontrolLatitud").val());
      var latitud= <?php echo $prevLat ?>;
      //var latitud=20.78863;
      var longitud= <?php echo $prevLong ?>;
      var zoom;
      if(longitud==-99.15 && latitud==19.32){
        zoom=4;
      }else{
        zoom=15;
      }
      console.log("Numero de zoom "+zoom);
      $("#PuntocontrolLatitud").val(latitud);
      $("#PuntocontrolLongitud").val(longitud);
      
      //var longitud=parseFloat($("#PuntocontrolLongitud").val());
      //var longitud=-103.472675;
      var radio=parseInt($("#PuntocontrolRadio").val());
      var value={latLng:[latitud, longitud], id:"PC"};
      var value2=[];
      value2.push(value);
      console.log("LatLng inicial=> "+ value2);
      var newPosition=false;
      var arrCenter= [latitud, longitud];        
     

      $('#map').gmap3({
        map:{
          options:{
            center: arrCenter,
            zoom: zoom
          }
        },
        marker:{
          values: value2,
          options:{
          draggable: true,
          icon: "<?php echo $this->webroot; ?>img/puntoControlN.png"
          },
          events: {              
            click: function(marker, event, context){
              //markerSelected(context.data.descripcion);
            },
            dragstart:function(marker, event, context){
              $("#PuntocontrolLatitud").val(marker.getPosition().lat());
              $("#PuntocontrolLongitud").val(marker.getPosition().lng());
              console.log("latitud:"+ marker.getPosition().lat()+" longitud:"+ marker.getPosition().lat());
              hideRadio();
              newPosition=true;
            },
            dragend: function(marker, event, context){
              $("#PuntocontrolLatitud").val(marker.getPosition().lat());
              $("#PuntocontrolLongitud").val(marker.getPosition().lng());
              console.log("latitud:"+ marker.getPosition().lat()+" longitud:"+ marker.getPosition().lat()); 
              showRadio();               
            }
          }
        },
        circle:{
          options:{
            center: [latitud, longitud],
            radius : radio,
            fillColor : "#008BB2",
            strokeColor : "#005BB7"
          }, tag: "PC"
        }
      });
    
      function hideRadio(){
        $('#map').gmap3({clear: {tag:["PC"]}});
        console.log("Circle hidden");
      }

      function showRadio(){ 
        var latitud= parseFloat($("#PuntocontrolLatitud").val());          
        var longitud= parseFloat($("#PuntocontrolLongitud").val());          
        var radio=parseInt($("#PuntocontrolRadio").val());

        $('#map').gmap3({             
          circle:{
            options:{
              center: [latitud, longitud],
              radius : radio,
              fillColor : "#008BB2",
              strokeColor : "#005BB7"
            }, tag: "PC"
            }
        });
      }

      function changeRadio(){
        hideRadio();
        showRadio();
      }

      function createMarker(){
        changeRadio();
        var latitud= parseFloat($("#PuntocontrolLatitud").val());          
        var longitud= parseFloat($("#PuntocontrolLongitud").val()); 
        var value={latLng:[latitud, longitud], id:"PC"}
        console.log("Latitud: "+latitud+" Longitud: "+longitud)
        console.log(value);
        //$('#map').gmap3({clear: {id:["PC"]}});
        //createMarker(value);
        //var marker = $('#map').gmap3({get:{id:"PC", full:true}});
        //marker.setPosition();
        $("#map").gmap3({
          get: {
            name:"marker",
            id:"PC",
            callback: function(marker){
              marker.setPosition(new google.maps.LatLng(latitud,longitud));
            }
          }
        });
      }
      
      $("#PuntocontrolRadio, #PuntocontrolLatitud, #PuntocontrolLongitud").on("keyup", function(){
        //changeRadio();
        createMarker();
      });

      $("#PuntocontrolRadio, #PuntocontrolLatitud, #PuntocontrolLongitud").on("change", function(){
        //changeRadio();
        createMarker();
      });;

    });
</script>

<h2>Agregar un punto de control</h2>
<?php if($this->Session->check('LastPoint.prRuta')) {?><div class="backContainer"><?php echo $this->Form->postLink("Volver al listado",array('action' => 'index'),array('data'=>array('puntocontrol'=>array('ruta_id'=>$this->Session->read('LastPoint.prRuta'))),'escape'=>false,'confirm' => '¿Volver al listado? Cambios no guardados se descartarán'));?></div><?php } ?>
<div id="tool">	
	<?php
	echo $this->Form->create('Puntocontrol');
	echo $this->Form->input('ruta_id', array('type' => 'select', 'options' => $rutas, 'label' => 'Número de Ruta', 'empty' => '- Elige una ruta -'));
	echo $this->Form->input('descripcion',array('label' => 'Descipción'));
	echo $this->Form->input('alias',array('label' => 'Alias'));
	echo $this->Form->input('faro',array('label' => 'Faro'));
	echo $this->Form->input('tipo',array('label' => 'Tipo','type' => 'select', 'options' => array('Paso','Llega/Sale')));
	/*
	echo $this->Form->input('longrados',array('label' => 'Longitud (Grados)'));
	echo $this->Form->input('lonminutos',array('label' => 'Longitud (Minutos)'));
	echo $this->Form->input('latgrados',array('label' => 'Latitud (Grados)'));
	echo $this->Form->input('latminutos',array('label' => 'Latitud (Minutos)'));
	echo $this->Form->input('direccioneo', array('type' => 'select','options' => array( 'E' => 'Este', 'W' => 'Oeste'), 'empty' => '- Elige una opción -'));
	echo $this->Form->input('direccionns', array('type' => 'select','options' => array( 'N' => 'Norte', 'S' => 'Sur'), 'empty' => '- Elige una opción -'));
	*/
	echo $this->Form->input('longitud',array('label' => 'Longitud'));
	echo $this->Form->input('latitud',array('label' => 'Latitud'));

	echo $this->Form->input('radio',array('label'=>'Radio'));
	echo $this->Form->end(array('label'=>'Guardar punto de control','id'=>'btn_save'));
	?>
</div>
<div id="map" class="gmap3">
	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d7098.94326104394!2d78.0430654485247!3d27.172909818538997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1385710909804" width="600" height="450" frameborder="0" style="border:0"></iframe>
</div>
