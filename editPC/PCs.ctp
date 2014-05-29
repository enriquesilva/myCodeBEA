<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
<style>
      body{
        text-align:center;
      }
      .gmap3{
        margin: 20px auto;
        border: 1px dashed #C0C0C0;
        width: 100%;
        height: 900px;
      }     
    </style>
<script type="text/javascript">
        
      $(function(){

        var $map = $("#test");
        //Javascript arrays llendos por un arreglo ($detallado) de php que viene del controller
        var puntosControl= <?php echo json_encode($detallado['PC'])?>;  
        var dataMarkers= <?php echo json_encode($detallado['Detalles'])?>;
        //Array que contiene objetos con datos de cada posicion y  PCS  
        var list=[];     
        
        //Se itera dataMarkers y puntoControls para llenar list con objetos        
        
        var htmlCode= function (str){
          str = str.replace(/u00d1/g, "Ñ");          
          return str;
        }

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
          
          for(var x in puntosControl){            
            var pcontrolNO= "pcontrolNO";
            var pcontrolYES= "pcontrolYES";
            var objDatosPC={};            
            var arrLatLongPC= [puntosControl[x]['PC']['latitud'], puntosControl[x]['PC']['longitud']];
            var horaPC= puntosControl[x]['PC']['hora'];
            var statusPC= puntosControl[x]['PC']['status'];
            var descripcion= puntosControl[x]['PC']['descripcion'];
            var alias= puntosControl[x]['PC']['alias'];  
            var radio= puntosControl[x]['PC']['radio'];             
            //convierte a Ñ si recive el codigo u00d1  
            var descripcion2= htmlCode(descripcion);              
            var objData={hora:horaPC, status:statusPC, descripcion:descripcion2, alias:alias, radio:radio, latLng:arrLatLongPC};
              console.log(objData);
            var horaPC= puntosControl[x]['PC']['status'];  
            if(statusPC!=true){              
              objDatosPC={latLng:arrLatLongPC, tag:pcontrolNO, data:objData};
              radioPCs(objDatosPC.latLng, objDatosPC.data.radio);
              list.push(objDatosPC);
            }else{
              objDatosPC={latLng:arrLatLongPC, tag:pcontrolYES, data:objData};
              radioPCs(objDatosPC.latLng, objDatosPC.data.radio);             
              list.push(objDatosPC);
            }
          }
        //}     

        /*
        var centerMap= function(){
          var middle= Math.round(dataMarkers.length/2);
          var arrLatLong= [dataMarkers[middle]['Detalle']['latitud'], dataMarkers[middle]['Detalle']['longitud']];
          return arrLatLong;
        }*/
        var arrCenter= list[0].latLng;        
        
        $('#test').gmap3({
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
              icon: "<?php echo $this->webroot; ?>img/red_bus3.png"
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
                    infowindow.setContent("<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Hora: </strong>"+ context.data.hora+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"</div>");
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
                    infowindow.setContent("<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Hora: </strong>"+ context.data.hora+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"</div>");
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
                
              }/*,
              mouseout: function(){
                var infowindow = $(this).gmap3({get:{name:"infowindow"}});
                if (infowindow){
                  infowindow.close();
                }
              }*/
            }
          }

        });

                
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
        
        setTimeout(function(){
          
          var markers = $("#test").gmap3({
            get: {
              tag:"pcontrolNO",              
              all: true              
            }
          });
          console.log("Esto contiene el marker"+markers);            
          $.each(markers, function(i, marker){
            marker.setIcon("<?php echo $this->webroot; ?>img/puntoControlR.png");            
          });          
        }, 5);

        setTimeout(function(){
          var markers = $("#test").gmap3({
            get: {
              tag:"pcontrolYES",              
              all: true              
            }
          });            
          $.each(markers, function(i, marker){
            marker.setIcon("<?php echo $this->webroot; ?>img/puntoControlG.png");
          });          
        }, 5);

        var num=0;        
        var numMarkers=dataMarkers.length;
        
        var timer=setInterval(function(){
            changeIconsGreen();
          }, 100);          
        

        function changeIconsGreen(){
          if(num==numMarkers){
              //changeIconsRed();
          }else{
            num=num+1;
            var pc="pc"+num;        
            var marker = $("#test").gmap3({
              get: {
                id: pc
              }
            });             
            marker.setIcon("<?php echo $this->webroot; ?>img/black_bus.png");
            //console.log("Animacion num"+num);
          }  
        }

        function changeIconsRed(){
          var n;
          for(n=1; n<=numMarkers; n++){            
            var pc="pc"+n;        
            var marker = $("#test").gmap3({
              get: {
                id: pc
              }
            });             
            marker.setIcon("<?php echo $this->webroot; ?>img/red_bus3.png");
            //console.log("Camion "+n+ " cambiado");
          }
          num=0;          
        }        

      });        
        
    </script>
  
    <h2 id="analizerTitle">Recorrido del viaje</h2>
    <div id="h3container">
      <h3 class="h3detalle">Autobús: <span><?php echo $Cabecera['Autobus'];?></span></h3>
      <h3 class="h3detalle">Fecha: <span><?php echo $Cabecera['Fecha'];?></span></h3>
      <h3 class="h3detalle">Viaje: <span><?php echo $Cabecera['Vuelta'];?></span></h3>
    </div>   
    <div id="test" class="gmap3"></div>
    
    <!--
    <pre>    
    <?php// print_r($detallado)?>
    <pre>
    -->
    


