<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCQqTp2_r5AEhKFibr0Lj2JBSiXc9orHVs&sensor=false" type="text/javascript"></script>
  <style>
      body{
        text-align:center;
      }
      .btn{
        display: inline-block;
        width: 80px;
        height: 30px;
        background-color: green;
        vertical-align: top;
        color: white;
        font-weight: bold;
        text-align: center;
      }
      #container{
        /*overflow: auto;*/
        margin: 20px auto;
        /*width: 1500px;*/
      }
      #tool{
        border: 1px dashed #C0C0C0;
        width: 350px;
        height: 600px;
        float: left;
        margin-left: 10px;
        padding: 5px;
        text-align:left;
      }
      .gmap3{
        border: 1px dashed #C0C0C0;
        width: 1500px;
        height: 600px;
        float: left;
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

        //var $map = $("#map");
        var puntosControl= <?php echo json_encode($detallado['PC'])?>;
        var list=[];
        var $map = $("#map"), menu = new Gmap3Menu($map), current;

        var htmlCode= function (str){
          str = str.replace(/u00d1/g, "Ñ");          
          return str;
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
              //console.log(objData);
            var horaPC= puntosControl[x]['PC']['status'];
            if(arrLatLongPC[0]!=false || arrLatLongPC[1]!=false){
              if(statusPC!=true){              
                objDatosPC={latLng:arrLatLongPC, tag:pcontrolNO, data:objData, id:"PC"+x};
                radioPCs(objDatosPC.latLng, objDatosPC.data.radio, objDatosPC.id);
                //console.log(objDatosPC);
                //console.log("tag del radio numero: "+x);
                list.push(objDatosPC);
              }else{
                objDatosPC={latLng:arrLatLongPC, tag:pcontrolYES, data:objData, id:"PC"+x};
                radioPCs(objDatosPC.latLng, objDatosPC.data.radio, objDatosPC.id);
                //console.log(objDatosPC);
                //console.log("tag del radio numero: "+x);             
                list.push(objDatosPC);
              }              
            }  
            
        }

        menu.add("Centrar aquí", "centerHere", 
          function(){
              $map.gmap3("get").setCenter(current.latLng);
              menu.close();
          });
        /*
        menu.add("Zoom in", "zoomIn", 
          function(){
            var map = $map.gmap3("get");
            map.setZoom(map.getZoom() + 1);
            menu.close();
          });        
        
        menu.add("Zoom out", "zoomOut",
          function(){
            var map = $map.gmap3("get");
            map.setZoom(map.getZoom() - 1);
            menu.close();
          });*/

        var arrCenter= list[0].latLng;
        var newPosition=false;

        $('#map').gmap3({
          map:{
            options:{
              center: arrCenter,
              zoom: 12
            },
            events: {              
              rightclick:function(map, event){
                current = event;
                menu.open(current);
              }/*,
              zoom_changed: function(){
                menu.close();
              }*/
            }
          },
          marker:{
            values: list,
            options:{
              draggable: true,
              icon: "<?php echo $this->webroot; ?>img/puntoControlN.png"
            },
            events: {
              /*
              rightclick:function(map, event){
                current = event;
                menu.open(current);
              },*/
              click: function(marker, event, context){
                if(newPosition==false){
                  markerSelected(context.id);
                }else{
                  dragMarker(context.id);
                }
                //markerSelected(context.data.descripcion);
              },
              dragstart:function(marker, event, context){
                dragMarker(context.id);
                $("#latitud .value").val(marker.getPosition().lat());
                $("#longitud .value").val(marker.getPosition().lng());
                console.log("latitud:"+ marker.getPosition().lat()+" longitud:"+ marker.getPosition().lat());
                hideRadio();
                newPosition=true;

              },
              dragend: function(marker, event, context){
                dragMarker(context.id);
                $("#latitud .value").val(marker.getPosition().lat());
                $("#longitud .value").val(marker.getPosition().lng());
                console.log("latitud:"+ marker.getPosition().lat()+" longitud:"+ marker.getPosition().lat()); 
                showRadio();               
              }
            }
          }
        });        
        
        function hideRadio(){
          $('#map').gmap3({clear: {tag:[$("#markerId .value").val()]}});
          console.log("Circle hidden");
        }
        function showRadio(){
          $('#map').gmap3({clear: {tag:[$("#markerId .value").val()]}});
          //console.log("Circle hidden");
          var latitud= parseFloat($("#latitud .value").val());
          //console.log("latitud "+latitud);
          var longitud= parseFloat($("#longitud .value").val());
          //console.log("longitud "+longitud);
          var radio= parseInt($("#radio .value").val());         
          //console.log("radio "+radio);
          $('#map').gmap3({             
            circle:{
              options:{
                center: [latitud, longitud],
                radius : radio,
                fillColor : "#008BB2",
                strokeColor : "#005BB7"
              }, tag: $("#markerId .value").val()
            }
          });
        }

        $("#aplicar").on("click" ,function(){
          //var marker = $("#map").gmap3({get: "gmap3_24" });
          console.log("Al entrar en guardar newPosition= "+newPosition);                    
          var latitud= parseFloat($("#latitud .value").val());
          console.log("latitud "+latitud);
          var longitud= parseFloat($("#longitud .value").val());
          console.log("longitud "+longitud);
          var radio= parseInt($("#radio .value").val());         
          console.log("radio "+radio);

          var descripcion= $("#descripcion .value").val();
          var alias= $("#alias .value").val();
          var radio= parseInt($("#radio .value").val());
          var arrLatLongPC=[latitud, longitud];
          var objData={descripcion:descripcion, alias:alias, radio:radio, latLng:arrLatLongPC};
          objDatosPC={latLng:arrLatLongPC, data:objData, id:$("#markerId .value").val()};
          //var marker = $('#map').gmap3({get: $("#markerId .value").val() });
          //marker.setIcon(marker.getIcon() ? "" : "http://maps.google.com/mapfiles/marker_green.png");
          $('#map').gmap3({clear: {tag:[$("#markerId .value").val()]}});
          $('#map').gmap3({clear: {id:[$("#markerId .value").val()]}});          
          $('#map').gmap3({
            marker:{
              values:[
                objDatosPC           
              ],
              options:{
                draggable: true,
                icon: "<?php echo $this->webroot; ?>img/puntoControlN.png"
            },
            events: {
              click: function(marker, event, context){
                if(newPosition==false){
                  markerSelected(context.id);
                }else{
                  dragMarker(context.id);
                }
                //markerSelected(context.data.descripcion);
              },
              dragstart:function(marker, event, context){
                dragMarker(context.id);
                $("#latitud .value").val(marker.getPosition().lat());
                $("#longitud .value").val(marker.getPosition().lng());
                console.log("latitud:"+ marker.getPosition().lat()+" longitud:"+ marker.getPosition().lat());
                hideRadio();
                newPosition=true;

              },
              dragend: function(marker, event, context){
                dragMarker(context.id);
                $("#latitud .value").val(marker.getPosition().lat());
                $("#longitud .value").val(marker.getPosition().lng());
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
              }, tag: $("#markerId .value").val()
            }
          });
          newPosition=false;
          console.log("Al salir en guardar newPosition= "+ newPosition);
        }); 
      
        function radioPCs(coordenadas, radio, radioNumber){
            var radios=parseInt(radio);
            console.log("Checanfo radios tag "+radioNumber)
            $('#map').gmap3({
               circle:{
                options:{
                  center: coordenadas,
                  radius : radios,
                  fillColor : "#008BB2",
                  strokeColor : "#005BB7"
                }, tag: radioNumber
              }
            });            
        }

        function dragMarker(myid){
          var marker = $('#map').gmap3({get:{id:myid, full:true}});
          $("#descripcion .value").val(marker.data.descripcion);
          $("#alias .value").val(marker.data.alias);
          $("#markerId .value").val(myid);
          $("#radio .value").val(marker.data.radio);        
        }
              
        function markerSelected(myid){
          var marker = $('#map').gmap3({get:{id:myid, full:true}});          
          //console.log(marker);
          $("#descripcion .value").val(marker.data.descripcion);
          $("#alias .value").val(marker.data.alias);
          $("#markerId .value").val(myid);
          $("#latitud .value").val(marker.data.latLng[0]);
          $("#longitud .value").val(marker.data.latLng[1]);
          $("#radio .value").val(marker.data.radio);          
          //$("#data").show();
          //$("#title").hide(); 
        }

        $(".btn").css('cursor','pointer'); 
    });   
      
    </script>
  
    <div id="container">
      <div id="ruta">
        <label>Ruta: </label>
          <select>
            <option>13</option>
            <option>380</option>
            <option>629</option>
            <option>625</option>
          </select>             
      </div>      
      <div id="tool">
        <form method="POST">
        <div id="title"><h2>Selecciona un punto de control</h2></div>
          <div id="data">            
            <div id="descripcion" class="section">
              <label>Descripción: </label>
              <input type='text'class='value' name='data[Puntocontrol][descripcion]'>            
            </div>
            <div id="alias" class="section">
              <label>Alias: </label>
              <input type='text'class='value' name='data[Puntocontrol][alias]'>            
            </div>            
            <div id="markerId" class="section">
              <label>ID: </label>
              <input type='text'class='value'>            
            </div>            
            <div id="latitud" class="section">
              <label>Latitud: </label>
              <input type='text'class='value' name='data[Puntocontrol][latitud]'>
            </div>
            <div id="longitud" class="section">
              <label>Longitud: </label>
              <input type='text'class='value' name='data[Puntocontrol][longitud]'>
            </div>
            <div id="radio" class="section">
              <label>Radio: </label>
             <input type='number'class='value' name='data[Puntocontrol][radio]'>
            </div>
            <div id="aplicar" class="btn">Aplicar</div>
            <div id="agregar" class="btn">Agregar</div>
            <button id="guardar" class="btn">Guardar Cambios</button>
          </div>
        </form>  
      </div>
      <div id="map" class="gmap3"></div>
    </div> 
    <!--
    <pre>    
    <?php //print_r($detallado)?>
    <pre>
    -->
    
       


