      /*funcion que dibujará los puntos de control contenidos en el JSON. Los datos que se extraerán del JSON son:
      latitud, longitud, descripcion, alias, radio, faro de los puntos de control de la ruta.
      */
      //Parametros que recibe la funcion...
      //jsonPCs= JSON con todos los PCs de la ruta, elementToEdit= Objeto con los datos del nuevo PC (marker verde), centerMap= centro del mapa y posicion inicial del nuevo PC (marker verde), zoom= valor del zoom del mapa, showRadio= true para mostrar radio inicial del nuevo PC, aplica para edit.ctp 
      function drawMarkers(jsonPCs, elementToEdit, centerMap, zoom, showRadio){        
        //Array php con los puntos de control guardado como JSON en puntosControl
        //var puntosControl= <?php echo json_encode($resultado)?>;
        var puntosControl= jsonPCs;        
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
          objData.tipo= puntosControl[x]['Puntocontrol']['tipo'];
          if(objData.tipo==0){objData.tipo="Paso"}else if(objData.tipo==1){objData.tipo="Llega/Sale"}
          objData.latitud= puntosControl[x]['Puntocontrol']['latitud'];
          objData.longitud=puntosControl[x]['Puntocontrol']['longitud'];
          objData.latLng= [puntosControl[x]['Puntocontrol']['latitud'], puntosControl[x]['Puntocontrol']['longitud']];        
          console.log(objData);
          objDatosPC={latLng:objData.latLng, data:objData, id:objData.alias};
          showRadio2(objData.latitud, objData.longitud, objData.radio, objData.alias);
          console.log(objData.latitud, objData.longitud, objData.radio);
          list.push(objDatosPC);                   
        }
        if(elementToEdit!=null){
          list.push(elementToEdit);
        }else{
          console.log("No hay element to edit");
        }       
        console.log(list);                
       
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
            icon: "/fenix/img/puntoControlN.png"
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
                      infowindow.setContent("<div style='height:160px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"<br><strong>Radio: </strong>"+context.data.radio+"<br><strong>Faro: </strong>"+context.data.faro+"<br><strong>Tipo: </strong>"+context.data.tipo+"</div>");
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
                    infowindow.setContent("<div style='height:160px; width:200px;'><div style='text-align:center;'><strong>"+context.data.descripcion+"</strong></div><br><strong>Alias: </strong>"+ context.data.alias+"<br><strong>Latitud: </strong>"+context.data.latLng[0]+"<br><strong>Longitud: </strong>"+context.data.latLng[1]+"<br><strong>Radio: </strong>"+context.data.radio+"<br><strong>Faro: </strong>"+context.data.faro+"<br><strong>Tipo: </strong>"+context.data.tipo+"</div>");
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
        if(elementToEdit!=null){
          //Marker (punto de control) a editar, color verde y draggable true
          $("#map").gmap3({
            get: {
              name:"marker",
              id:"PC",
              callback: function(marker){
                marker.setIcon("/fenix/img/puntoControlG.png");
                marker.setDraggable(true);
                //marker.setVisible(false);
              }
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
          console.log("Circle shown");
        }
        if(showRadio){
          showRadio();
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
          createMarker();
        });
        //Cada vez que hay un cambio en el formulario se llama a la funcion createMarker que reubica el marker y redibuja el radio del mismo
        $("#PuntocontrolRadio, #PuntocontrolLatitud, #PuntocontrolLongitud").on("change", function(){          
          createMarker();
        });; 
      }