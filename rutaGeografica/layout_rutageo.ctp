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
		  	height: 600px; /* Or whatever */
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
      .labels {
	     	color: black;
	     	background-color: none;
	     	font-family: "Lucida Grande", "Arial", sans-serif;
	     	font-size: 9px;
	     	font-weight: bold;
	     	text-align: center;
	     	width: 35px;     
	     	white-space: nowrap;
   	}
   	#resumenContainer{

   	}
   	.resumen{
   		display: inline-block;
   		width: 23%;
   		vertical-align: top;
   	}
   	form .input .select{
   		display: inline-block;
   	}
   	div.input.select {
			display: inline-block;
		}
		.lista{
			list-style: none;
		}
		
	</style>
	<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCQqTp2_r5AEhKFibr0Lj2JBSiXc9orHVs&sensor=false&libraries=geometry" type="text/javascript"></script>
	<script src="<?php echo $this->webroot; ?>js/markerwithlabel.js" type="text/javascript"></script>
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
		//var paths=[];		
	   function convertGoogleLatLng(array){	   	
			var paths = [];
			for (var i = 0; i < array.length; i++) {			    		    
			    paths.push(new google.maps.LatLng(array[i][0], array[i][1]));		    
			}
			return paths;
	   }  
	   var infowindow2 = null;
	   var pcMarkers=[];
	   var markerPC= null;

    	function setPCMarkers(map) {		  
		   var image = {
		   	url: 'img/puntoControl_25.png',		    
		   	size: new google.maps.Size(20, 32),
		   	origin: new google.maps.Point(0,0),
		   	scaledSize: new google.maps.Size(20, 32)
		   	//anchor: new google.maps.Point(0, 32)
		  	};		  	   		  
  		
  		var radioGeoCerca;
		  	for(x in puntoscontrol){
		  		var position= new google.maps.LatLng(puntoscontrol[x].latitud, puntoscontrol[x].longitud);
		  		var tipo= puntoscontrol[x].tipo;
		  		var tipoString="";
		  		var radio= parseInt(puntoscontrol[x].radio);

		  		var geoCerca = {
			      strokeColor: '#005BB7',
			      strokeOpacity: 0.8,
			      strokeWeight: 2,
			      fillColor: '#008BB2',
			      fillOpacity: 0.35,
			      map: map,
			      center: position,
			      radius: radio
			   };
			   //agreagamos el radio al punto de control
			   radioGeoCerca = new google.maps.Circle(geoCerca);
		  		//Tipo de punto de control 0->Paso, 1->Llega/Sale
		  		if(tipo==='0'){
		  			tipoString="Paso";
		  		} else if(tipo==='1'){
		  			tipoString="Llega/Sale";
		  		}

		  		var infoString = "<div style='width:200px'><strong>Descripcion: </strong>"+puntoscontrol[x].descripcion+"<br><strong>Alias: </strong>"+puntoscontrol[x].alias+"<br><strong>Id: </strong>"+puntoscontrol[x].id+"<br><strong>Tipo: </strong>"+tipoString+"<br><strong>Radio: </strong>"+puntoscontrol[x].radio+"<br><strong>Latitud: </strong>"+puntoscontrol[x].latitud+"<br><strong>Longitud: </strong>"+puntoscontrol[x].longitud+"</div>";
		  		markerPC = new google.maps.Marker({
		      	position: position,
		      	map: map,
		      	icon: image,
		      	//shape: shape,
		      	html: infoString,
		      	zIndex: 4		      	
	     		});

            google.maps.event.addListener(markerPC, "mouseover", function () {
               //alert(this.html);
               infowindow2.setContent(this.html);
               infowindow2.open(map, this);
            });
            google.maps.event.addListener(markerPC, "mouseout", function () {
               //alert(this.html);
               //infowindow.setContent(this.html);
               infowindow2.close(map, this);
            });

            pcMarkers.push(markerPC);	     		 
		  	}
		}

		//Todos los camiones se guardan aqui
		var busMarkers=[];
		var infowindow = null;
		//var markerBus = null;

		function deleteBusMarkers() {
  			clearBusMarkers();
  			markers = [];
		}

		function clearBusMarkers() {
  			for (var i = 0; i < busMarkers.length; i++) {
    			busMarkers[i].setMap(null);
  			}
		}

		function setBusMarkers(map, dataBus, ruta) {
			if(ruta){
				console.log("Si hay ruta");
			}else{
				console.log("No hay Ruta");
			}
			deleteBusMarkers();
		   console.log("Valor objeto dataBuses");
		   console.log(dataBus);
		   
		   var zindex=3;		   		   	  	
		   //var numExcesos=0;
		   //var numFueraRuta=0;
		   var camionFueraRuta=[];
		   var camionExcesoVel=[];
		  	for(x in dataBus){
		  		var image={};
		  		//var clase="";
		  		var velocidadBus= parseInt(dataBus[x].Velocidad);//velocidad de cada bus, segun la info del websevice posicones
		  		if(velocidadBus>=0 && velocidadBus<velocidadLimite1){
		  			//console.log(velocidadBus);
		  			image = {
		   			url: 'img/green_marker_bus_25.png',		    
		   			size: new google.maps.Size(25, 41),
		   			origin: new google.maps.Point(0,0),
		   			//anchor: new google.maps.Point(0, 32)		   					   			
		  			}
		  			setInfoExcesosVel(camionExcesoVel);
		  			//document.getElementById('exceso').innerHtml="Verde";
		  		}else if(velocidadBus>=velocidadLimite1 && velocidadBus<velocidadLimite2){
		  			image = {
		   			url: 'img/yellow_marker_bus_25.png',		    
		   			size: new google.maps.Size(25, 41),
		   			origin: new google.maps.Point(0,0),
		   			//anchor: new google.maps.Point(0, 32)
		  			};
		  			var nombre= dataBus[x].Nombre;
		  			//numExcesos= numExcesos+1;
		  			var data= {nombre:nombre, img:'img/yellow_marker_bus_25.png'};		  			
		  			camionExcesoVel.push(data);
		  			zindex=99;
		  			//document.getElementById('exceso').innerHtml="Amarillo";
		  		}else {//if(velocidadBus>=velocidadLimite2){
		  			image = {
		   			url: 'img/red_marker_bus_25.png',		    
		   			size: new google.maps.Size(25, 41),
		   			origin: new google.maps.Point(0,0),
		   			//anchor: new google.maps.Point(0, 32)
		  			};
		  			var nombre= dataBus[x].Nombre;
		  			//numExcesos= numExcesos+1;		  			
		  			var data= {nombre:nombre, img:'img/red_marker_bus_25.png'};		  			
		  			camionExcesoVel.push(data);
		  			zindex=99;
		  			//document.getElementById('exceso').innerHtml="Rojo";
		  		}

		  		var position= new google.maps.LatLng(dataBus[x].Coordenadas[0], dataBus[x].Coordenadas[1]);//var excesoV1= dataBus[]
		  		var infoString = "<div style='width:180px'><strong>Vehículo: </strong>"+dataBus[x].Nombre+"<br>"+"<strong>Fecha: </strong>"+dataBus[x].Fecha+"<br>"+"<strong>Velocidad: </strong>"+dataBus[x].Velocidad+" Km/h</div>";

		  		//OnEndge me dice si la posicion acrual del camion esta en ruta o fuera de ruta de acuerdo al polyline de la ruta pintada.
		  		if (google.maps.geometry.poly.isLocationOnEdge(position, ruta, 0.0003)) {
            	console.log("Camion: "+dataBus[x].Nombre+" "+position + " En ruta");
         	}else {
            	console.log("Camion: "+dataBus[x].Nombre+" "+position + " Fuera de ruta");
            	image = {
		   			url: 'img/aqua_marker_bus_25.png',		    
		   			size: new google.maps.Size(25, 41),
		   			origin: new google.maps.Point(0,0),
		   			//anchor: new google.maps.Point(0, 32)
		  			};		  			
		  			var nombre= dataBus[x].Nombre;
		  			//numFueraRuta=numFueraRuta+1;
		  			var data= {nombre:nombre, img:'img/aqua_marker_bus_25.png'}; 
		  			camionFueraRuta.push(data);
          	}

		  		var markerBus = new MarkerWithLabel({
		      	position: position,
		      	map: map,
		      	icon: image,
		      	//shape: shape,
		      	zIndex: zindex,
		      	html: infoString,
		      	labelContent: dataBus[x].Nombre,
       			labelAnchor: new google.maps.Point(18, 30),
       			labelClass: "labels", // the CSS class for the label
       			labelInBackground: false		      	
	     		});
	     		
            google.maps.event.addListener(markerBus, "mouseover", function () {
               //alert(this.html);
               infowindow.setContent(this.html);
               infowindow.open(map, this);
            });
            google.maps.event.addListener(markerBus, "mouseout", function () {
               //alert(this.html);
               //infowindow.setContent(this.html);
               infowindow.close(map, this);
            });
	     			     		
	     		busMarkers.push(markerBus);
		  	}
		  	dataBuses=[];
		  	//se envia un arreglo con los camiones fuera de ruta y el numero de camiones fuera de ruta
		  	setInfoFueraRuta(camionFueraRuta);
		  	//se envia un arreglo con los camiones con exceso de velocidad y el numero de camiones fuera de ruta
		  	setInfoExcesosVel(camionExcesoVel);
		}
		
		

		function initialize() {
			if(nodos[0]){
				console.log("nodos existe");
				//convertGoogleLatLng(nodos);
				//console.log(paths);
				var mapOptions = {
			   	zoom: 13,
			   	center: new google.maps.LatLng(puntoscontrol[0].latitud, puntoscontrol[0].longitud),
			   	mapTypeId: google.maps.MapTypeId.ROADMAP
		   	};

			  	var map = new google.maps.Map(document.getElementById('map'),
			      mapOptions);

			  	setPCMarkers(map);
			  	infowindow2 = new google.maps.InfoWindow({
	                content: "loading..."
	         });
			  	
			  	var nodosLatLng = convertGoogleLatLng(nodos);
			  	var ruta = new google.maps.Polyline({
			   	path: nodosLatLng,
			   	geodesic: true,
			   	strokeColor: '#EB575C',
			   	strokeOpacity: 0.7,
			   	strokeWeight: 5
			   });

			  	ruta.setMap(map);

			  	!function requestPosiciones(){
			  		var dataBus= window.getPosiciones();
			  		console.log("Se hizo el request de posiciones");
			  		setBusMarkers(map, dataBus, ruta);
			  		infowindow = new google.maps.InfoWindow({
	                content: "loading..."
	            });
			  		setTimeout(requestPosiciones, 10000);
	         }();

			}else{
				console.log("nodos NO existe");
				alert("Los Nodos de la ruta no se han cargado correctamente, Porfavor recargue la pagina.");
			}
		       

		}

		google.maps.event.addDomListener(window, 'load', initialize);

		$(function(){
			var getDB= "";
         var getRuta= "";
         var getDominio="";
         window.nodos=[];
         window.puntoscontrol=[];
         window.positionPC=[];
         window.dataBuses=[];
         window.velocidadLimite1=null;
         window.velocidadLimite2=null; 

         var camion=null;
         
         window.setInfoExcesosVel= function(camionExcesoVel){         	         	
         	var numExcesos= camionExcesoVel.length;         	         	
         	$("#nExcesos").html(numExcesos);
         	$("#excesos").find(".lista").children().remove();
         	for(var x in camionExcesoVel){         		
	         	var nombreExcesos=camionExcesoVel[x];
	         	$("#excesos").find(".lista").append("<li><img src='"+nombreExcesos.img+"'>"+nombreExcesos.nombre+"</li>");	         	
	         } 
         };

         window.setInfoFueraRuta= function(camionFueraRuta){
         	var numFueraRuta= camionFueraRuta.length;
         	$("#nFueraRuta").html(numFueraRuta);
         	$("#fueraRuta").find(".lista").children().remove();         	
	         for(var x in camionFueraRuta){         		
	         	var nombreFR=camionFueraRuta[x];
	         	$("#fueraRuta").find(".lista").append("<li><img src='"+nombreFR.img+"'>"+nombreFR.nombre+"</li>");	         	
	         }	
         };
         

         $( "#RutageoRutas" ).change(function(){				
				window.ruta =$('#RutageoRutas').val();
			});

         $.getJSON( "http://10.0.0.126/Analizador/WSRutaGeografica.php?idRuta=6", function( data ) {
				console.log(data);
			   puntoscontrol= data.puntoscontrol;
				//obtenemos el grupo (base de datos)
				getDB=data.server[0].schemadatos;
				getRuta=data.server[0].numruta;
				getDominio=data.server[0].dominio;
				velocidadLimite1= parseInt(data.server[0].velocidadlimite1);
				velocidadLimite2= parseInt(data.server[0].velocidadlimite2);
				//velocidadesLimite=[velocidad1, velocidad2];
				console.log("Velocidad Limite1= "+velocidadLimite1);
				console.log("Velocidad Limite2= "+velocidadLimite2);										
				getNodos();
				//getPosiciones();				
				//console.log(puntosControl);
			});

			function getNodos(){
				$.get( "http://"+getDominio+"/busbeaXML/tramoNodosXML.aspx?ruta="+getRuta+"&db="+getDB, function( xml ) { 
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
	               //dibujaTramo(tramo);
	            });
	            //setmap();
	            console.log(nodos);	            
	            //poly.setPath(nodos);  
         	});
			}
			window.getPosiciones= function(){
            //clearBuses();
            var date= new Date(); 
                                                     
            $.get( "http://"+getDominio+"/busbeaXML/PosicionesRutaXML.aspx?ruta="+getRuta+"&db="+getDB+"&fecha="+date, function( xml ) { 
                
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
	               var data={Nombre:nombre, Fecha:fecha, Velocidad:velocidad, Coordenadas: coordenadas}                   
                  //dataBuses=[];
                  dataBuses.push(data);            
	               //checaPosicion();
	               //posicionBuses(coordenadas, data);  
               });
               //return dataBuses;               
            });
				//console.log(dataBuses);
				return window.dataBuses;
            console.log("http://www.1.monitoreatubus.com/busbeaXML/PosicionesRutaXML.aspx?ruta="+getRuta+"&db="+getDB+"&fecha="+date);        
            console.log("Hora del request de Posiciones: "+date);            
            
         };
		});
   </script>
</body>
</html>
