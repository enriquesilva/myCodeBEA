<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo "AVA: ".$title_for_layout; ?></title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('dragtable-default');
		echo $this->Html->css('http://fonts.googleapis.com/css?family=Open+Sans');		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->Html->css('menu');
		echo $this->fetch('script');
		echo $this->Html->script('jquery');
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('jquery-ui.min');
		echo $this->Html->script('jquery.metadata');
		echo $this->Html->script('jquery.tablesorter.min');
		echo $this->Html->script('jquery.dragtable');
		echo $this->Html->script('d3');
		echo $this->Html->script('nv.d3.min');
		echo $this->Html->script('gmap3');
		echo $this->Html->css('nv.d3');
	?>
	<meta name="viewport" content="width=device-width">
	<style>
		#mostrar{
			width: 80px;
			height: 40px;
			background: #1E66B4;
			text-align: center;
			color: white;
			cursor: pointer;
			display: flex;
		}
		#label{
			margin: auto;
			height: 30px;
			cursor: pointer;
		}
		#contenedor {
		  	display: flex;
		  	height: 900px; /* Or whatever */
		}
		#mainDiv {
		 	width: 100%;  /* Or whatever */
		   height: 650px; /* Or whatever */
		   margin: auto;  /* Magic! */
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

	</style>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>

				<a href="/fenix" id="ava"><?php echo __('AVA');?></a>
				<?php if($this->Session->check('Auth.User')) {	?>
				<span class="username">
					<?php echo __('Usuario:') . $this->Session->read('Auth.User.username'); ?>
					<a href="/fenix/users/logout"> / <?php echo __('Cerrar sesión');?></a>
				</span>
				<?php } ?>
			</h1>	
			<?php if($this->Session->check('Auth.User')) {	?>
				<?php require('menu.ctp'); ?>
			<?php } ?>		
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		<?php 
		echo $this->Html->link($this->Html->image('MX_40.png', array('alt' =>'Español (México)','title'=>'Español (México)',/*'width'=>'24px','height'=>'24px'*/)),array('controller'=>'users','action'=>'changeLanguage','mx'),array('escape'=>false));
		echo $this->Html->link($this->Html->image('US_40.png', array('alt' =>'English (United States)','title'=>'English (United States)',/*'width'=>'24px','height'=>'24px'*/)),array('controller'=>'users','action'=>'changeLanguage','us'),array('escape'=>false));
		?>
			<figure>
			<?php echo $this->Html->image('logobea_hor.png', array('id'=>'logoFooter','alt' => __('Sistema BEA'),'title'=>__('Sistema BEA - IDEAR Electrónica'),'width'=>'394px','height'=>'150px')); ?>
			<figcaption>&copy; <?php echo __('Sistema BEA - IDEAR Electrónica '). date('Y');?></figcaption>
			</figure>
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
	<?php echo $this->Js->writeBuffer(); ?>
	
	<script type="text/javascript">
      $(function(){
         var nodos= [];
         var posiciones={};
         var x=0;
         
			//window.dominio =$('#RutageoDominio').val();
			$('#mostrar').click(function(){
		   	startMap();		    
			});
			$( "#RutageoGrupo, #RutageoRutas" ).change(function(){
				window.grupo =$('#RutageoGrupo').val();
				window.ruta =$('#RutageoRutas').val();
			}); 
         window.startMap= function (){
         var getDB= window.grupo;
         var getRuta= window.ruta;
         //poly = new google.maps.Polyline({ map: map, strokeWeight: 5, strokeColor: "#008BB2", strokeOpacity: 1.0});
         console.log("DB: "+getDB);
         console.log("Ruta: "+getRuta);  
         //Request con AJAX para obtener los nodos de la ruta y poder pintar la ruta con polylines
         $.get( "http://www.1.monitoreatubus.com/busbeaXML/tramoNodosXML.aspx?ruta="+getRuta+"&db="+getDB, function( xml ) {        
              
            $(xml).find('Tramo').each(function(){
                //x++;
               var origenLat = parseFloat($(this).find('OrigenLat').text())/1000000;
               var origenLon = parseFloat($(this).find('OrigenLon').text())/1000000;
               var origenArr= [origenLat, origenLon];
               //console.log(x);
               //path.push(origenArr);
               nodos.push(origenArr);
               var destinoLat = parseFloat($(this).find('DestinoLat').text())/1000000;
               var destinoLon = parseFloat($(this).find('DestinoLon').text())/1000000;
               var destinoArr= [destinoLat, destinoLon];
               //path.push(destinoArr); 
               nodos.push(destinoArr);
               var tramo=[origenArr, destinoArr];
               
               dibujaTramo(tramo);     

            });
            setmap();
            //poly.setPath(nodos);  
         });
         //funcion que hace request (AJAX) de las posiciones cada 10 segundos
         //Se llama asi misma la funcion y dentro de ella existe el setTimeOut que cicla el llamado asi mismo cada 10 segundos
         !function requestPosiciones(){
            clearBuses();
            var date= new Date();          
            $.get( "http://www.1.monitoreatubus.com/busbeaXML/PosicionesRutaXML.aspx?ruta="+getRuta+"&db="+getDB+"&fecha="+date, function( xml ) { 
                
               $(xml).find('Posicion').each(function(index, value){            
                  
               var usuarioId = $(this).find('UsuarioId').text();
               var lat = parseFloat($(this).find('Latitud').text())/1000000;
               var lon = parseFloat($(this).find('Longitud').text())/1000000;
               var velocidad = $(this).find('Velocidad').text();
               var nombre = $(this).find('Nombre').text();
               var imei = $(this).find('IMEI').text();
               var fecha = $(this).find('Fecha').text();
               var tipo = $(this).find('Tipo').text();
               var direccion = $(this).find('Direccion').text();
               var coordenadas= [lat, lon];
               var data={Nombre:nombre, Fecha:fecha, Velocidad:velocidad}                    
               //console.log(index);
               //checaPosicion();
               posicionBuses(coordenadas, data);     

               });
            });
            console.log("http://www.1.monitoreatubus.com/busbeaXML/PosicionesRutaXML.aspx?ruta="+getRuta+"&db="+getDB+"&fecha="+date);        
            console.log("Hora del request de Posiciones: "+date);

            setTimeout(requestPosiciones, 10000);
         }();
      	}

          function checaPosicion(){

          }

          function clearBuses(){
            $('#map').gmap3({
              clear: {
                name:["marker","overlay"]
              }
            });
            console.log("Markers borrados");
          }      

          //Se carga el mapadesoues de que se haya leido el xml de tramos
          function setmap(){
          //setTimeout(function(){
            //console.log(path);        
            $('#map').gmap3({
              map:{
                options:{
                  center:nodos[0] , 
                  zoom:12,
                  maxZoom:19 
                  //mapTypeId: google.maps.MapTypeId.TERRAIN
                }
              }
            });             
            
          //},300);
          }
          
          //funcion que es llamada cada vez que se lee un tramo del xml. Pinta multiples polylines para tener como resultado la ruta completa.
          function dibujaTramo(tramo){        
            $('#map').gmap3({
              polyline:{
                options:{
                  strokeColor: "#FF0000",
                  strokeOpacity: 0.7,
                  strokeWeight: 5,
                  path:tramo
                }
              }
            });
            var poly= $('#map').gmap3({
              get: {
                name:["polyline"]
              }
            });
            console.log(poly);        
          }
          //Funcion que es llamada cada 10segundos que se hace el request con las nuevas posiciones. Recibe las coordenadas y  datos de cada unidad.
          function posicionBuses(coordenadas, data){        
            $('#map').gmap3({
              marker:{
                values:[
                  {latLng: coordenadas, data:"<div style='width:180px'><strong>Vehículo: </strong>"+data.Nombre+"<br>"+"<strong>Fecha: </strong>"+data.Fecha+"<br>"+"<strong>Velocidad: </strong>"+data.Velocidad+" Km/h</div>"}          
                ],
                options:{
                  draggable: false,
                  icon: new google.maps.MarkerImage('img/green_marker_bus_50.png', null, null, null, new google.maps.Size(30,50))
                },
                events:{
                  click: function(marker, event, context){
                    var map = $(this).gmap3("get"),
                      infowindow = $(this).gmap3({get:{name:"infowindow"}});
                    if (infowindow){
                      infowindow.open(map, marker);
                      infowindow.setContent(context.data);
                    } else {
                      $(this).gmap3({
                        infowindow:{
                          anchor:marker, 
                          options:{content: context.data}
                        }
                      });
                    }
                  },
                  mouseover: function(marker, event, context){
                    var map = $(this).gmap3("get"),
                      infowindow = $(this).gmap3({get:{name:"infowindow"}});
                    if (infowindow){
                      infowindow.open(map, marker);
                      infowindow.setContent(context.data);
                    } else {
                      $(this).gmap3({
                        infowindow:{
                          anchor:marker, 
                          options:{content: context.data}
                        }
                      });
                    }
                    marker.setZIndex(999);
                  },
                  mouseout: function(marker, event, context){
                    var infowindow = $(this).gmap3({get:{name:"infowindow"}});
                    if (infowindow){
                      infowindow.close();
                    }
                    marker.setZIndex(1);
                  }
                }
              },
              overlay:{
                latLng: coordenadas,
                options:{
                  content:  '<div style="color:#000000;' +
                            'width:40px; line-height:20px; ' +
                            'height: 20px; text-align:center"><strong>'+data.Nombre+'</strong></div>',
                  offset:{
                    y:-40,
                    x:-20
                  }
                }
              }
            });        
          }         
               
        });
   </script>
</body>
</html>
