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
</style>
  <script type="text/javascript">
    $(function(){
      /*funcion que dibujará los puntos de control contenidos en el JSON. Los datos que se extraerán del JSON son:
      latitud, longitud, descripcion, alias, radio, faro de los puntos de control de la ruta.
      */
      function drawMarkers(jsonPCs, elementToEdit, centerMap, zoom){        
        //Array php con los puntos de control guardado como JSON en puntosControl
        //var puntosControl= <?php echo json_encode($resultado)?>;
        var puntosControl= json;        
        //En "list" se almacenan los puntos de control para colocarlos como markers
        var list=[];
        //Funcion que convierte a Ñ si recive el codigo u00d1
        var htmlCode= function (str){
            str = str.replace(/u00d1/g, "Ñ");          
            return str;
          }
        //Se recorre el JSON puntosControl para guardar los datos necesarios en el array list    
        for(var x in puntosControl){
          var objDatosPC={};
          var objData={};
          objData.descripcion= htmlCode(puntosControl[x]['Puntocontrol']['descripcion']);
          objData.alias= puntosControl[x]['Puntocontrol']['alias'];
          objData.radio= parseInt(puntosControl[x]['Puntocontrol']['radio']);        
          objData.faro= puntosControl[x]['Puntocontrol']['faro'];
          objData.latitud= puntosControl[x]['Puntocontrol']['latitud'];
          objData.longitud=puntosControl[x]['Puntocontrol']['longitud'];
          objData.latLng= [puntosControl[x]['Puntocontrol']['latitud'], puntosControl[x]['Puntocontrol']['longitud']];        
          console.log(objData);
          objDatosPC={latLng:objData.latLng, data:objData, id:objData.alias};
          showRadio2(objData.latitud, objData.longitud, objData.radio, objData.alias);
          console.log(objData.latitud, objData.longitud, objData.radio);
          list.push(objDatosPC);                   
        }
        //Se guardan los datos contenidos en el formulario
        //var descripcion=$("#PuntocontrolDescripcion").val();
        var alias=$("#PuntocontrolAlias").val();
        //var faro=$("#PuntocontrolFaro").val();
        //var tipo=$("#PuntocontrolTipo").val();
        //---->var latitud=parseFloat($("#PuntocontrolLatitud").val());      
        //---->var longitud=parseFloat($("#PuntocontrolLongitud").val());      
        //var radio=parseInt($("#PuntocontrolRadio").val());
        //Valores del punto de control a editar con id=PC
        //---->var value={latLng:[latitud, longitud], id:"PC"};
        //Se agrega al array list   
        list.push(elementToEdit);
        console.log(list);
        
        //var newPosition=false;
        //---->var arrCenter= [latitud, longitud];        
       
        //Se inicializa el mapa. Se dibujan todos los puntos de control de la ruta y el nuevo punto de control.
        $('#map').gmap3({
          map:{
            options:{
              center: centerMap,
              zoom: zoom
            }
          },
          marker:{
            values: list,
            options:{
            draggable: false,
            icon: "<?php echo $this->webroot; ?>img/puntoControlN.png"
            },
            events: {              
              click: function(marker, event, context){
              //markerSelected(context.data.descripcion);
                var map = $(this).gmap3("get"),
                  infowindow = $(this).gmap3({
                    get:{
                      name:"infowindow"
                    }
                  });
                  if(context.data.descripcion){
                    if (infowindow){
                      infowindow.open(map, marker);
                      infowindow.setContent("<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"</div>");
                    } else {
                      $(this).gmap3({
                        infowindow:{
                          anchor:marker, 
                          options:{content: "<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"</div>"}
                        }
                      });
                      }                  
                  }
              },
              mouseover: function(marker, event, context){
                var map = $(this).gmap3("get"),
                infowindow = $(this).gmap3({
                  get:{
                   name:"infowindow"
                  }
                });
                if(context.data.descripcion){
                  if (infowindow){
                    infowindow.open(map, marker);
                    infowindow.setContent("<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"</div>");
                  } else {
                      $(this).gmap3({
                        infowindow:{
                          anchor:marker, 
                          options:{content: "<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"</div>"}
                        }
                      });
                    }                  
                }     

              },
              dragstart:function(marker, event, context){
                $("#PuntocontrolLatitud").val(marker.getPosition().lat());
                $("#PuntocontrolLongitud").val(marker.getPosition().lng());
                console.log("latitud:"+ marker.getPosition().lat()+" longitud:"+ marker.getPosition().lat());
                hideRadio();
                //newPosition=true;
              },
              dragend: function(marker, event, context){
                $("#PuntocontrolLatitud").val(marker.getPosition().lat());
                $("#PuntocontrolLongitud").val(marker.getPosition().lng());
                console.log("latitud:"+ marker.getPosition().lat()+" longitud:"+ marker.getPosition().lat()); 
                showRadio();               
              }
            }
          }
        });
      }
      
      //Mostrar el radio del marker a editar
      showRadio();      
      //Marker (punto de control) a editar, color verde y draggable true
      $("#map").gmap3({
        get: {
          name:"marker",
          id:"PC",
          callback: function(marker){
            marker.setIcon("<?php echo $this->webroot; ?>img/puntoControlG.png");
            marker.setDraggable(true);
          }
        }
      });
    
      //Borra el marker negro (punto de control) que se va a editar para poner el marker verde draggable  
      $("#map").gmap3({
        clear:{id:alias}
      });
      $("#map").gmap3({
        clear:{tag:alias}
      });
		  //Elimna radio para posteriormente crear uno nuevo
		  function hideRadio(){
        $('#map').gmap3({clear: {tag:["PC"]}});
        console.log("Circle hidden");
      }
      //Dibuja el radio del marker (punto de control) que se va a editar
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
      //Dibuja el radio de los puntos de control en puntosControl
      function showRadio2(lat, lon, rad, alias){
        //var radio=parseInt(rad);
        $('#map').gmap3({             
          circle:{
            options:{
              center: [lat, lon],
              radius : rad,
              fillColor : "#008BB2",
              strokeColor : "#005BB7"
            }, tag: alias
          }
        });
      } 
      //Borra y redibuja el radio del nuevo marker cada vez que hay un cambio en el formulario
      function changeRadio(){
        hideRadio();
        showRadio();
      }
      //Reubica la posicion del marker cada vez que se hace un cambio en el formulario
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
      //Cada vez que hay un cambio en el formulario se llama a la funcion createMarker que reubica el marker y redibuja el radio del mismo
      $("#PuntocontrolRadio, #PuntocontrolLatitud, #PuntocontrolLongitud").on("keyup", function(){
        //changeRadio();
        createMarker();
      });
      //Cada vez que hay un cambio en el formulario se llama a la funcion createMarker que reubica el marker y redibuja el radio del mismo
      $("#PuntocontrolRadio, #PuntocontrolLatitud, #PuntocontrolLongitud").on("change", function(){
        //changeRadio();
        createMarker();
      });;

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