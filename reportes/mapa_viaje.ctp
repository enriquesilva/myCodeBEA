<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCQqTp2_r5AEhKFibr0Lj2JBSiXc9orHVs&sensor=false" type="text/javascript"></script>
<style>
      body{
        text-align:center;
      }/*
      .gmap3{
        margin: 20px auto;
        border: 1px dashed #C0C0C0;
        width: 100%;
        height: 900px;
      }*/
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
<script type="text/javascript">
        
      $(function(){

        var $map = $("#map");
        //Javascript arrays llenados por un arreglo ($detallado) de php que viene del controller
        //puntosControl guarda todos los puntos de control de la ruta
        var puntosControl= <?php echo json_encode($detallado['PC'])?>;
        //dataMarkers guarda las posiciones del camion durante el viaje  
        var dataMarkers= <?php echo json_encode($detallado['Detalles'])?>;
        //Array que contiene objetos con datos de los PCs y las posiciones del camion
        var list=[];
        //Funcion que convierte a Ñ si recive el codigo u00d1
        var htmlCode= function (str){
          str = str.replace(/u00d1/g, "Ñ");          
          return str;
        }
        //Se itera dataMarkers(posiciones del camion durante el viaje) y puntoControls para llenar list con objetos  
        for(var x in dataMarkers){
          var num= parseInt(x)+1;
          var position= "pc"+num;
          var arrLatLong= [dataMarkers[x]['Detalle']['latitud'], dataMarkers[x]['Detalle']['longitud']];
          var hora= dataMarkers[x]['Detalle']['hora'];            
          var objData={hora:hora};
          if(arrLatLong[0]!=false || arrLatLong[1]!=false){
            var objDatos={latLng:arrLatLong, id:position, data:objData, tag:'position'};
            //console.log(objDatos);
            list.push(objDatos);
            //console.log(list);
          }
        } 
        //Se llena el arreglo list con los PCs tambien se llama la funcion radioPCs para dibujar los radios  
        for(var x in puntosControl){            
          var pcontrolNO= "pcontrolNO";
          var pcontrolYES= "pcontrolYES";
          var objDatosPC={};
          var objData={};             
          objData.latLng= [puntosControl[x]['PC']['latitud'], puntosControl[x]['PC']['longitud']];
          objData.hora= puntosControl[x]['PC']['hora'];
          objData.status= puntosControl[x]['PC']['status'];
          objData.descripcion= htmlCode(puntosControl[x]['PC']['descripcion']);
          objData.alias= puntosControl[x]['PC']['alias'];  
          objData.radio= puntosControl[x]['PC']['radio'];
          //objData.faro= puntosControl[x]['PC']['faro'];
          objData.tipo= puntosControl[x]['PC']['tipo'];
          if(objData.tipo==0){objData.tipo="Paso"}else if(objData.tipo==1){objData.tipo="Llega/Sale"}
          console.log(objData);            
          radioPCs(objData.latLng, objData.radio); 
          if(objData.status!=true){              
            objDatosPC={latLng:objData.latLng, tag:pcontrolNO, data:objData};              
            list.push(objDatosPC);
          }else{
            objDatosPC={latLng:objData.latLng, tag:pcontrolYES, data:objData};                         
            list.push(objDatosPC);
          }
        }
        //} 
        
        var arrCenter= list[0].latLng;        
        //Se inicializa el mapa, dibuja todos los markers de PCs y posiciones del camion, posteriormente se cambian sus iconos.
        $('#map').gmap3({
          map:{
            options:{
              center: arrCenter,
              zoom: 14
            }
          },
          
          marker:{
            values:list,
            options:{
              draggable: false,
              zIndex: 3,
              icon: "<?php echo $this->webroot; ?>img/black_bus.png"
              //visible: false
            },            
            events:{
              
              click: function(marker, event, context){
                var map = $(this).gmap3("get"),
                infowindow = $(this).gmap3({
                  get:{
                    name:"infowindow"
                  }
                });
                if(context.data.descripcion){

                  if (infowindow){
                    infowindow.open(map, marker);
                    infowindow.setContent("<div style='height:160px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Hora: </strong>"+ context.data.hora+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"<br><strong>Radio: </strong>"+context.data.radio+"<br><strong>Tipo: </strong>"+context.data.tipo+"</div>");
                  } else {
                    $(this).gmap3({
                      infowindow:{
                        anchor:marker, 
                        options:{content: "<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Hora: </strong>"+ context.data.hora+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"</div>"}
                      }
                    });
                  }                  
                }else{
                  if (infowindow){
                    infowindow.open(map, marker);
                    infowindow.setContent(context.data.hora);
                  } else {
                    $(this).gmap3({
                      infowindow:{
                        anchor:marker, 
                        options:{content: context.data.hora}
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
                    infowindow.setContent("<div style='height:160px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Hora: </strong>"+ context.data.hora+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"<br><strong>Radio: </strong>"+context.data.radio+"<br><strong>Tipo: </strong>"+context.data.tipo+"</div>");
                  } else {
                    $(this).gmap3({
                      infowindow:{
                        anchor:marker, 
                        options:{content: "<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Hora: </strong>"+ context.data.hora+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"</div>"}
                      }
                    });
                  }                  
                }else{
                  if (infowindow){
                    infowindow.open(map, marker);
                    infowindow.setContent(context.data.hora);
                  } else {
                    $(this).gmap3({
                      infowindow:{
                        anchor:marker, 
                        options:{content: context.data.hora}
                      }
                    });
                  }
                }                
              }
            }
          }
        });
        //dibuja los radios de los PCs        
        function radioPCs(coordenadas, radio){
          var radios=parseInt(radio);
          $map.gmap3({
             circle:{
              options:{
                center: coordenadas,
                radius : radios,
                fillColor : "#008BB2",
                strokeColor : "#005BB7"
              }
            }
          });
        }

        //Markers de camionsitos negros con setVisible: false para lograr la animacion dandoles setVisible:true uno por uno
        var blackBuses = $("#map").gmap3({
          get: {
            tag:"position",              
            all: true              
          }
        });
        console.log("Esto contiene el marker"+blackBuses);            
        $.each(blackBuses, function(i, blackBus){
          blackBus.setVisible(false);            
        });

        //Puntos de control rojos que indican que NO pasó por alli el camión         
        var redPCs = $("#map").gmap3({
          get: {
            tag:"pcontrolNO",              
            all: true              
          }
        });
        console.log("Esto contiene el marker"+redPCs);            
        $.each(redPCs, function(i, redPC){
          redPC.setIcon("<?php echo $this->webroot; ?>img/puntoControlR.png");            
        });  

        //Puntos de control rojos que indican que SÍ pasó por alli el camión 
        var greenPCs = $("#map").gmap3({
          get: {
            tag:"pcontrolYES",              
            all: true              
          }
        });
        console.log("Esto contiene el marker"+greenPCs);            
        $.each(greenPCs, function(i, greenPC){
          greenPC.setIcon("<?php echo $this->webroot; ?>img/puntoControlG.png");
        });          
       

        var num=0;        
        var numMarkers=dataMarkers.length;
        //Cada 100 milisegundos llama a la funcion visibleIcons la cual logra la animacion de los camionsitos
        var timer=setInterval(function(){
            visibleIcons();
          }, 100);          
        
        //Cambia a visible los markers de los camionsitos negros  
        function visibleIcons(){
          if(num==numMarkers){
              //changeIconsRed();
          }else{
            num=num+1;
            var pc="pc"+num;        
            var marker = $("#map").gmap3({
              get: {
                id: pc
              }
            });             
            marker.setVisible(true);
            //console.log("Animacion num"+num);
          }  
        }

      });        
        
    </script>
  
    <h2 id="analizerTitle">Recorrido del viaje</h2>
    <div id="h3container">
      <h3 class="h3detalle">Autobús: <span><?php echo $Cabecera['Autobus'];?></span></h3>
      <h3 class="h3detalle">Fecha: <span><?php echo $Cabecera['Fecha'];?></span></h3>
      <h3 class="h3detalle">Viaje: <span><?php echo $Cabecera['Vuelta'];?></span></h3>
    </div>   
    <div id="map" class="gmap3">
      <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d7098.94326104394!2d78.0430654485247!3d27.172909818538997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1385710909804" width="600" height="450" frameborder="0" style="border:0"></iframe>
    </div>
    
  <!--  
    <pre>    
    <?php //print_r($detallado)?>
    <pre>
  -->  
    


