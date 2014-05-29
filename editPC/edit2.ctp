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
      var latitud=parseFloat($("#PuntocontrolLatitud").val());
      //var latitud=20.78863;
      var longitud=parseFloat($("#PuntocontrolLongitud").val());
      //var longitud=-103.472675;
      var radio=parseInt($("#PuntocontrolRadio").val());
      var value={latLng:[latitud, longitud], id:"PC"};
      var value2=[];
      value2.push(value);
      console.log(value2);
      var newPosition=false;
      var arrCenter= [latitud, longitud];        
     

      $('#map').gmap3({
        map:{
          options:{
            center: arrCenter,
            zoom: 15
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

<h2>Editar Punto de Control</h2>
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
	<?php //print_r($rutas)?>	
<pre>
-->
