<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCQqTp2_r5AEhKFibr0Lj2JBSiXc9orHVs&sensor=false" type="text/javascript"></script>
<style>
      body{
        text-align:center;
      }
      .btn{
        display: inline-block;
        width: 50px;
        height: 30px;
        background-color: green;
      }
      #container{
        overflow: auto;
        margin: 20px auto;
        width: 1200px;
      }
      #tool{
        border: 1px dashed #C0C0C0;
        width: 500px;
        height: 600px;
        float: left;
        margin-left: 10px;
        padding: 5px;
        text-align:left;
      }
      .gmap3{
        border: 1px dashed #C0C0C0;
        width: 600px;
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
</style>

    <script type="text/javascript">
      $(function(){

        //var $map = $("#map");
        var puntosControl= <?php echo json_encode($detallado['PC'])?>;

        var list=[];

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
              console.log(objData);
            var horaPC= puntosControl[x]['PC']['status'];
            if(arrLatLongPC[0]!=false || arrLatLongPC[1]!=false){
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
            
        }

        var arrCenter= list[0].latLng;

        $('#map').gmap3({
          map:{
            options:{
              center: arrCenter,
              zoom: 12
            }
          },
          marker:{
            values: list,
            options:{
              draggable: false
            },
            events: {
              click: function(marker, event, context){
                markerSelected(context.id);
                //markerSelected(context.data.descripcion);
              }
            }
          }
        });
        
        $("#guardar").on("click" ,function(){
          //var marker = $("#map").gmap3({get: "gmap3_24" });          
          var latitud= parseFloat($("#latitud .value").val());
          console.log("latitud "+latitud);
          var longitud= parseFloat($("#longitud .value").val());
          console.log("longitud "+longitud);
          var radio= parseInt($("#radio .value").val());         
          console.log("radio "+radio);
          //var marker = $('#map').gmap3({get: $("#markerId .value").val() });
          //marker.setIcon(marker.getIcon() ? "" : "http://maps.google.com/mapfiles/marker_green.png");
          $('#map').gmap3({
             circle:{
              options:{
                center: [latitud, longitud],
                radius : radio,
                fillColor : "#0000B2",
                strokeColor : "#005BB7"
              }
            }
          });
        });
        
        $("#bremove").click(function(){
          $('#map').gmap3({clear: $("#markerId .value").val() });
          $("#data").hide();
          $("#title").show();
        });
        
      });
      
      function radioPCs(coordenadas, radio){
          var radios=parseInt(radio);
          $('#map').gmap3({
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
          
          var markers = $("#map").gmap3({
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
          var markers = $("#map").gmap3({
            get: {
              tag:"pcontrolYES",              
              all: true              
            }
          });            
          $.each(markers, function(i, marker){
            marker.setIcon("<?php echo $this->webroot; ?>img/puntoControlG.png");
          });          
        }, 5);
      
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
      
      //$("#guardar").click(function(){
          //var marker = $('#map').gmap3({get: {id:$("#markerId .value").text(), full:true}});
          //marker.setIcon(marker.getIcon() ? "" : "http://maps.google.com/mapfiles/marker_green.png");
          /*var latitud= parseFloat($("#latitud .value").text());
          console.log("latitud "+latitud);
          var longitud= parseFloat($("#longitud .value").text());
          console.log("longitud "+longitud);
          var radio= $("#radio .value").text();         
          var radios=parseInt(radio);
          console.log("radio "+radios);*/
          /*  
          var marker = $('#map').gmap3({get: $("#markerId .value").text() });
          marker.setIcon(marker.getIcon() ? "" : "http://maps.google.com/mapfiles/marker_green.png");
          /*$('#map').gmap3({
             circle:{
              options:{
                center: [latitud, longitud],
                radius : radios,
                fillColor : "#0000B2",
                strokeColor : "#005BB7"
              }
            }
          });*/
      //});
      
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
        <form>
        <div id="title"><h2>Selecciona un punto de control</h2></div>
          <div id="data">            
            <div id="descripcion" class="section">
              <label>Descripción: </label>
              <input type='text'class='value'>            
            </div>
            <div id="alias" class="section">
              <label>Alias: </label>
              <input type='text'class='value'>            
            </div>            
            <div id="markerId" class="section">
              <label>ID: </label>
              <input type='text'class='value'>            
            </div>
            <div id="latitud" class="section">
              <label>Latitud: </label>
              <input type='text'class='value'>
            </div>
            <div id="longitud" class="section">
              <label>Longitud: </label>
              <input type='text'class='value'>
            </div>
            <div id="radio" class="section">
              <label>Radio: </label>
             <input type='number'class='value'>
            </div>
            <div id="guardar" class="btn">Guardar</div>
            <div id="bcolor" class="btn">Agregar</div>
            <div id="bremove" class="btn">Borrar</div>
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


