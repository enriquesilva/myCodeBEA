<%@ page language="C#" masterpagefile="~/MasterPage.master" autoeventwireup="true" inherits="Default2, App_Web_i5t1j5qr" title="Página sin título" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" Runat="Server">
   <style type="text/css">
        .style2
        {
            text-align:center;
            width:100%;
        }
    #flashPanel{
        /*display: none;*/
    }
    #panel{
        display: none;
        width: 100%;
        height: 700px;
        background-color: #4b6c9e;
    }   
    .element{
        margin: 15px;
        color: white;
    }
    .btn{
        margin: 15px;
        width: 160px;
        border: 0;
        border-radius: 3px;
        font-size: 15px;
        font-weight: bold;
    }
    .btnNav{
      width: 80px;
      border: 0;
      border-radius: 3px;
      font-weight: bold;
    }
    fieldset{
        border: 0;
    }
    #mapSide{
      display: inline-block; 
      float: left;
      height: 600px;
      width: 80%;
    }
    #controlsSide{
      width: 20%;
      float: right;
      display: inline-block;
    }
    .ui-progressbar {
      position: relative;
    }
    .progress-label {
      position: absolute;
      left: 50%;
      top: 4px;
      font-weight: bold;
      text-shadow: 1px 1px 0 #fff;
    }
    
    </style>
    <link rel="stylesheet" href="Historial/jqueryui/jquery-ui.min.css">
    <script type="text/javascript" src="Historial/jquery/jquery-1.11.1.min.js"></script>
    <script src="Historial/jqueryui/jquery-ui.min.js"></script>
    <script src="Historial/blockui.js"></script>  
    <script type="text/javascript">
    $(function(){

      var fecha="";
      var horaInicial="";
      var horaFinal="";
      var dataAutos=[];
      var boolFecha=false;
      var boolHoraini=false;
      var boolHorafin=false;
      var boolRuta=false;
      var boolUnidad=false;

      //slider velocidad animacion
      var select = $( "#velocidad" );
      var slider = $( "<div id='slider'></div>" ).insertAfter( select ).slider({
        min: 1,
        max: 10,
        range: "min",
        value: select[ 0 ].selectedIndex + 1,
        slide: function( event, ui ) {
          select[ 0 ].selectedIndex = ui.value - 1;
        }
      });
      $( "#velocidad" ).change(function() {
        slider.slider( "value", this.selectedIndex + 1 );
        //velocidad = parseInt($("#velocidad").val())*100;
      });

      $(".btn, .btnNav").click(function(event){
        event.preventDefault(); // cancel default behavior        
      });
      $( "#fecha" ).datepicker();

      $( "#btnReproducir" ).prop( "disabled", true );
      $( "#btnPausar" ).prop( "disabled", true );
      $( "#btnDetener" ).prop( "disabled", true );

      //Se hace un request al web service RutasXML para llenar el select de "Rutas"
      $.get( "http://www.1.monitoreatubus.com/busbeaxml/RutasXML.aspx?db=servytransportejal", function( xml ) {        
          
          $(xml).find('Ruta').each(function(){
            //x++;
            var nombre = $(this).find('Nombre').text();
            var id = $(this).find('Id').text();            
            //unidades.push(nombre);
            $("#numRuta").append("<option value='"+id+"'>"+nombre+"</option>");  

          });
          //setmap();  
      });

      $("#numRuta").change(function(){ //Acciones al modificar el select de ruta
        boolRuta=true;
        var ruta =$('#numRuta').val(); //Obtenemos el valor de ruta (parametro para el web service VehiculosXML)
        $("#numUnidad").children().remove();
        console.log(ruta);
        //Se hace un request al web service VehiculosXML para llenar el select de "Unidad" pasando como parametro la ruta
        $.get( "http://www.1.monitoreatubus.com/busbeaxml/VehiculosXML.aspx?ruta="+ruta+"&db=servytransportejal", function( xml ) {        
          
          $(xml).find('Vehiculo').each(function(){
            //x++;
            var nombre = $(this).find('Nombre').text();
            var id = $(this).find('Id').text();
            var imei = $(this).find('IMEI').text();
            console.log(nombre);
            //unidades.push(nombre);
            $("#numUnidad").append("<option value='"+id+"'>"+nombre+"</option>");  

          });
          //setmap();  
        });
      });
      

      $("#fecha").change(function(){ //Acciones al modificar el input de fecha
        fecha = $("#fecha").val(); //(parametro para el web service HistorialXML)
        console.log(fecha);
        boolFecha=true; //al cambiar la variable a true nos aseguramos que el input se llenó
      });

      $("#horaInicial").change(function(){//Acciones al modificar el select de horaInicial
        horaInicial = parseInt($("#horaInicial").val()); //(parametro para el web service HistorialXML)
        console.log(horaInicial);
        boolHoraini=true; //Nos aseguramos que el input se llenó
      });

      $("#horaFinal").change(function(){//Acciones al modificar el select de horaFinal
        horaFinal = parseInt($("#horaFinal").val()); //(parametro para el web service HistorialXML)
        console.log(horaFinal);
        boolHorafin=true; //Nos aseguramos que el input se llenó            
      });      

      $("#btnCargar").click(function(){
        var hora1= isNaN($("#horaInicial").val());//si la horaInicial NO es numero regresa true.
        var hora2= isNaN($("#horaFinal").val());//si la horaFinal NO es numero regresa true.
        if(hora1 || hora2){
          $.blockUI({//usamos la libreria blockUI para mostrar los mensajes de validacion tipo modal. 
            css: { 
              border: 'none', 
              padding: '15px', 
              backgroundColor: '#000', 
              '-webkit-border-radius': '5px', 
              '-moz-border-radius': '5px', 
              opacity: .5, 
              color: '#fff' 
            },
            message:"<p>Ingrese una hora valida.</p>" 
          });
          setTimeout($.unblockUI, 2000); //El mensaje modal dura 2 segundos
        }
        else if(!boolFecha || !boolRuta || !boolHorafin || !boolHoraini){//si algun campo NO es verdadero, se muestra el mensaje de "llenar todos los campos"
          $.blockUI({ 
            css: { 
              border: 'none', 
              padding: '15px', 
              backgroundColor: '#000', 
              '-webkit-border-radius': '5px', 
              '-moz-border-radius': '5px', 
              opacity: .5, 
              color: '#fff' 
            },
            message:"<p>Debe llenar todos los campos.</p>" 
          });
          setTimeout($.unblockUI, 2000); //El mensaje modal dura 2 segundos
        }
        else if(horaFinal<horaInicial){ //La hora final no puede ser menor que la hora inicial, de lo contrario se muestra la alerta.         
          $.blockUI({ 
            css: { 
              border: 'none', 
              padding: '15px', 
              backgroundColor: '#000', 
              '-webkit-border-radius': '5px', 
              '-moz-border-radius': '5px', 
              opacity: .5, 
              color: '#fff' 
            },
            message:"<p>La hora final es menor que la hora inicial.</p>" 
          }); 
          setTimeout($.unblockUI, 2000); //El mensaje modal dura 2 segundos
        }else{//si ninguna de las condiciones anteriores se dio, ejecutamos el codigo de Cargar los datos historicos
          dataAutos=[];
          val=0;//valor de la progressbar
          var ruta =$('#numRuta').val(); //obtenemos el valor de la ruta (parametro para el web service HistorialXML)
          var unidad =$('#numUnidad').val(); //obtenemos el valor de la unidad (parametro para el web service HistorialXML)
          console.log(ruta);
          document.getElementById('mapa').contentWindow.clearBuses(); //Se borran todos los busMarkers

          $("#btnCargar").prop("disabled", true ); //Se habilita el boton "cargar"        
          $("#btnPausar").prop("disabled", true ); //Se habilita el boton "pausar"
          $("#btnDetener").prop("disabled", true ); //Se habilita el boton "detener"

          //var unidades=[];
          $.get( "http://www.1.monitoreatubus.com/busbeaxml/HistorialXML.aspx?db=servytransportejal&ruta="+ruta+"&unidad="+unidad+"&fechaini="+fecha+"%20"+horaInicial+":00&fechafin="+fecha+"%20"+horaFinal+":00", function( xml ) {        
            
            $(xml).find('Historial').each(function(){
              //x++;
              var posicionId = $(this).find('Nombre').text();
              var lat = parseFloat($(this).find('Latitud').text())/1000000;
              var lon = parseFloat($(this).find('Longitud').text())/1000000;
              var fecha = $(this).find('Fecha').text();
              var velocidad = $(this).find('Velocidad').text();
              var tipo = $(this).find('Tipo').text();
              var coordenadas= [lat, lon];
              var data={Fecha:fecha, Velocidad:velocidad, Coordenadas: coordenadas}
              //setTimeout(function(){
                //document.getElementById('mapa').contentWindow.posicionBuses(coordenadas, data);
              //},5000);            
              dataAutos.push(data);
              
            });
            console.log(dataAutos);
            if(dataAutos.length===0){
              $.blockUI({ 
                css: { 
                  border: 'none', 
                  padding: '15px', 
                  backgroundColor: '#000', 
                  '-webkit-border-radius': '5px', 
                  '-moz-border-radius': '5px', 
                  opacity: .5, 
                  color: '#fff' 
                },
                message:"<p>No existe información en este período.</p>" 
              });
              setTimeout($.unblockUI, 2500); //El mensaje modal dura 2.5 segundos
              $("#btnCargar").prop("disabled", false );
            }else{
              document.getElementById('mapa').contentWindow.centerMap(dataAutos[0]['Coordenadas']);//Al terminar de obtener los datos historicos centramos el mapa
              $("#btnReproducir").prop("disabled", false );//Habilitamos el boton de "reproducir" cuando ya esten cargados todos los datos historicos.
              //animateAutos();
              //progressBar();
            }
              
          });
        }
        
      });

      $("#btnReproducir").click(function(){//Habilitamos y desabilitamos botones. Se llaman las funciones de animacion y progressbar.
        $("#btnReproducir").prop( "disabled", true ); //Se deshabilita el boton "reproducir"       
        $("#btnPausar").prop("disabled", false ); //Se habilita el boton "pausar"
        $("#btnDetener").prop("disabled", false ); //Se habilita el boton "detener"
        $("#progressbar").show(); //mostramos la progressbar
        animateAutos(); //llamamos al metodo para animar los busMarkers
        progressBar(); //llamamos a la funcion para animar la progressbar
      });

      $("#btnPausar").click(function(){//Acciones del boton "pausar"
        $("#btnPausar").prop( "disabled", true ); //Se deshabilita el boton "pausar" 
        $("#btnReproducir").prop( "disabled", false ); //Se habilita el boton "reproducir" 
        clearTimeout(animationTimer); //Detenemos el timer de la animacion de los busMarkers
        clearTimeout(progressBarTimer); //Detenemos el timer de la animacion de la progressBar
      });  

      $("#btnDetener").click(function(){ //Acciones del boton "detener"
        $("#btnCargar").prop( "disabled", false ); //Se habilita
        $("#btnReproducir").prop( "disabled", true ); //Se deshabilita
        $("#btnPausar").prop( "disabled", true ); //Se deshabilita
        $("#btnDetener").prop( "disabled", true ); //Se deshabilita
        $("#progressbar").hide(); //Escondemos la progressbar
        dataAutos=[]; //Vaciamos el arreglo dataAutos que contiene todos los datos Historicos de los buses.
        console.log(dataAutos);
        document.getElementById('mapa').contentWindow.clearBuses();//Borramos todos los busMarkers. Esta funcion esta dentro del iframe (historial.html)
        val=0;//reseteamos el valor de la progressbar a 0
        dataBusIndex=0; //reseteamos el valor del index del array dataAutos
        clearTimeout(animationTimer); //Detenemos el timer de la animacion de los busMarkers
        clearTimeout(progressBarTimer); //Detenemos el timer de la animacion de la progressBar
      });
      
      var dataBusIndex=0; //valor del index del array dataAutos, se usa en la funcion animateAutos
      var velocidad=1;
      var tiempo= 100;
      var animationTimer=0;
      var progressBarTimer=0;
      var val=0;
      

      $("#btnFwd").click(function(){
        var numBuses= dataAutos.length;
        document.getElementById('mapa').contentWindow.navegacion(true, numBuses);
      });

      $("#btnBck").click(function(){
        var numBuses= dataAutos.length;
        document.getElementById('mapa').contentWindow.navegacion(false, numBuses);
      });

      //Funcion para dibujar los markers en el mapa. Se ejecuta cada cierto intervalo de acuerdo a la velocidad seleccionada. 
      function animateAutos(){
        var velocidad=parseInt($("#velocidad").val())*100;
        console.log(dataBusIndex);        
        if(dataBusIndex < dataAutos.length){//Condicion para iterar el arreglo dataAutos, donde esta los datos del Historial de buses.
          var coordenadas=dataAutos[dataBusIndex].Coordenadas; //obtenemos las coordenadas del actual indice del arreglo dataAutos
          var data= {Fecha: dataAutos[dataBusIndex].Fecha, Velocidad: dataAutos[dataBusIndex].Velocidad};          
          document.getElementById('mapa').contentWindow.posicionBuses(coordenadas, dataBusIndex, data); //Este metodo esta en el iframe (historial.html)           
        
          animationTimer=setTimeout(animateAutos, velocidad);//se llama asi misma cada intervalo de acuerdo al valor de velocidad
          dataBusIndex++;// variable para controlar el número de iteraciones (autollamadas al mismo metodo con el setimeout)
        }else{
          dataBusIndex=0; //reseteamosel indice del arreglo dataAutos
          $("#btnReproducir").prop( "disabled", true ); //Se deshabilita "Reproducir"
          $("#btnPausar").prop( "disabled", true ); //Se deshabilita "Pausar"
        }
                
      }

      //ProgressBar code
      function progressBar(){  
        var progressbar = $( "#progressbar" ),
        progressLabel = $( ".progress-label" );
     
        progressbar.progressbar({
          value: val,
          change: function() {
            progressLabel.text( progressbar.progressbar( "value" ) + "%" );
          },
          complete: function() {
            progressLabel.text( "Completado!" );
          }
        });
        //De acuerdo al tamaño del arreglo y de la velocidad de la animacion, calculamos la duración de la progressbar
        function progress() {
          var velocidad=parseInt($("#velocidad").val())*100;
          tiempo=velocidad*dataAutos.length;        
          console.log("Tiempo: "+tiempo);
          var tiempoIntervalos=tiempo/50;
          val = progressbar.progressbar( "value" ) || 0;   
          progressbar.progressbar( "value", val + 2 );   
          if ( val < 99 ) {
            progressBarTimer=setTimeout( progress, tiempoIntervalos );
          }
        }   
        progress();
      }

    });
    
    //Funcion no usada por el momento
    $( window ).load(function() {
        console.log("Pagina cargada")
        var flashvars= $('embed').attr('flashvars');
        var params= flashvars.split('&');
        console.log(params);
        db= params[0].substring(3);
        console.log(db);
        ruta= params[1].substring(5);
        console.log(ruta);
        dominio= params[2].substring(4);                
        console.log(dominio);
        //document.getElementById('mapa').contentWindow.startMap();
    });
    
    </script>
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
<div id="flashPanel">
<center>
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="933" height="592" id="historialBea">
        <param name="movie" value="historialBea.swf" />
        <param name="quality" value="high" />
        <param name="bgcolor" value="#ffffff" />
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="allowFullScreen" value="true" />
        <!--[if !IE]>-->
        <object type="application/x-shockwave-flash" data="Historial/historial1.swf" width="933" height="592">
            <param name="quality" value="high" />
            <param name="bgcolor" value="#ffffff" />
            <param name="allowScriptAccess" value="sameDomain" />
            <param name="allowFullScreen" value="true" />
            <param name="FlashVars" value="db=<%=variable1() %>&dns=<%=variable2() %>"  />
        <!--<![endif]-->
        <!--[if gte IE 6]>-->
            <p> 
                Either scripts and active content are not permitted to run or Adobe Flash Player version
                10.0.0 or greater is not installed.
            </p>
        <!--<![endif]-->
            <a href="http://www.adobe.com/go/getflashplayer">
                <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash Player" />
            </a>
        <!--[if !IE]>-->
        </object>
        <!--<![endif]-->
    </object>
</center>
</div>
<div id="panel">
  <div id="mapSide">
    <iframe id="mapa" style="width: 100%; height: 600px; margin: auto; border: none; float: left;" src="Historial/historial.html">
    </iframe>
    <div id="progressbar"><div class="progress-label">Cargando...</div></div>
  </div>
  <div id="controlsSide">   
    <form>
      <fieldset>
        <div class="element">          
               <label>Ruta: </label><br>
               <select id ="numRuta">
                  <option></option>                           
               </select><br>
        </div>
        <div class="element">
          <label>Unidad: </label><br>
          <select name="carlist" id="numUnidad">
                             
          </select><br>
        </div>
        <div class="element">
          <label>Fecha: </label><br><input id="fecha" type="text" required><br>
        </div>
        <div class="element horas">
          <label>Hora Inicial: </label><br><input id="horaInicial" type="text" size="4" required><br>
        </div>
        <div class="element horas">
          <label>Hora Final: </label><br><input id="horaFinal" type="text" size="4" required><br>
        </div>
        <div class="element">
          <label>Velocidad Animación: </label><br>
          <select id ="velocidad">                       
            <option value="10">1</option>
            <option value="9">2</option>
            <option value="8">3</option>
            <option value="7">4</option>
            <option value="6" selected>5</option>
            <option value="5">6</option>
            <option value="4">7</option>
            <option value="3">8</option>
            <option value="2">9</option>
            <option value="1">10</option>           
          </select>
        </div>   
        <button id="btnCargar" class="btn">Cargar</button><br>
        <button id="btnReproducir" class="btn">Reproducir</button><br>
        <button id="btnPausar" class="btn">Pausar</button><br>
        <button id="btnDetener" class="btn">Detener</button><br>
        <div class="element">
          <label>Navegación: </label><br>
          <button id="btnBck" class="btnNav"><<</button>
          <button id="btnFwd" class="btnNav">>></button>
        </div>                
      </fieldset> 
    </form> 
  </div>      
</div>
</asp:Content>

