<!-- File: /app/View/PuntoControls/index.ctp -->
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCQqTp2_r5AEhKFibr0Lj2JBSiXc9orHVs&sensor=false" type="text/javascript"></script>
<style>      
    .gmap3{
        border: 1px dashed #C0C0C0;
        /*width: 1200px;*/
        /*height: 600px;*/
        /*float: left;*/
        position: relative;
        padding-bottom: 45%;
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

        //$("html").niceScroll();
        var puntosControl= <?php echo json_encode($resultado)?>;
        var list=[];

        var htmlCode= function (str){
          str = str.replace(/u00d1/g, "Ñ");          
          return str;
        }

        for(var x in puntosControl){
            var objDatosPC={};                       
            var arrLatLongPC= [puntosControl[x]['Puntocontrol']['latitud'], puntosControl[x]['Puntocontrol']['longitud']];
            var descripcion= puntosControl[x]['Puntocontrol']['descripcion']; 
            var alias= puntosControl[x]['Puntocontrol']['alias'];  
            var radio= puntosControl[x]['Puntocontrol']['radio'];
            var faro= puntosControl[x]['Puntocontrol']['faro'];             
            //convierte a Ñ si recive el codigo u00d1  
            var descripcion2= htmlCode(descripcion);              
            var objData={descripcion:descripcion2, alias:alias, radio:radio, latLng:arrLatLongPC};
            console.log(objData);
            objDatosPC={latLng:arrLatLongPC, data:objData};
            radioPCs(objDatosPC.latLng, objDatosPC.data.radio);
            list.push(objDatosPC);             
        }

        //var arrCenter= list[0].latLng;
        var centerMap= function(){
          var middle= Math.round(puntosControl.length/2);
          var arrLatLong= [puntosControl[middle]['Puntocontrol']['latitud'], puntosControl[middle]['Puntocontrol']['longitud']];
          return arrLatLong;
        }
        console.log("Mapa centrado en: "+centerMap());

        $('#map').gmap3({
            map:{
              options:{
                center: centerMap(),
                zoom: 12
              }
            },
            marker:{
              values: list,
              options:{
              draggable: false,
              icon: "<?php echo $this->webroot; ?>img/puntoControlN.png"
              },
              events: {              
                click: function(marker, event, context){//tomar la funcion completa y probarla fuera
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

                }
              }
            }
        });

        function radioPCs(coordenadas, radio){
          var radios=parseInt(radio);
          $("#map").gmap3({
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

        $("tr").on("click", function(){
            var descripcion=$(this).find(".descripcion").text();
            var alias=$(this).find(".alias").text();
            var latitud=parseFloat($(this).find(".latitud").text());
            var longitud=parseFloat($(this).find(".longitud").text());
            var radio=$(this).find(".radio").text();
            console.log(alias);
            console.log(latitud);
            console.log(longitud);
            console.log(radio);
            $('#map').gmap3({clear: {name:"infowindow"}});
            $("#map").gmap3({
                infowindow:{
                    latLng:[latitud,longitud],
                    options:{
                        content: "<div style='height:110px; width:200px;'><div style='text-align:center;'><strong>"+descripcion+"</strong></div><br><strong>Alias: </strong>"+alias+"<br><strong>Latitud: </strong>"+latitud+"<br><strong>Longitud: </strong>"+longitud+"</div>"
                    }                    
                }
            });
            $("#map").animatescroll({scrollSpeed:1500,easing:'easeOutSine'});
        });

    });
</script>

<?php $menuRol = $this->Session->read('Auth.User.rol_id'); ?>

<script type="text/javascript">
	$(document).ready(function() { $("#tablaPC").tablesorter(); } ); 
</script>

<h2>Catálogo de puntos de control</h2>
<?php
	echo $this->Form->create();
	echo $this->Form->input('ruta_id',array('label'=>'Ruta','type'=>'select','empty'=>' - Elige una ruta - ','options'=>$rutas));
	echo $this->Form->end('Buscar puntos de control asignados a ruta');	
?>

<?php
	if (!empty($resultado)) {
?>
<?php if ($menuRol == 5 || $menuRol == 6) { echo $this->Html->link( 'Agregar punto de control',array('controller' => 'puntocontrols', 'action' => 'add',$resultado['0']['Puntocontrol']['ruta_id']));} ?>
<table id="tablaPC" class="tablesorter">
    <thead>
    <tr>
        <th>ID<?php //echo $this->Paginator->sort('id', 'ID'); ?></th>
        <!-- <th>Ruta<?php //echo $this->Paginator->sort('ruta_id', 'Ruta'); ?></th> -->
        <th>Descripción</th>
        <th>Alias<?php //echo $this->Paginator->sort('alias', 'Alias'); ?></th>
        <th>Radio<?php //echo $this->Paginator->sort('radio','Radio'); ?></th>
        <th>Faro<?php //echo $this->Paginator->sort('faro', 'Faro'); ?></th>        
        <th>Tipo<?php //echo $this->Paginator->sort('tipo', 'Tipo'); ?></th>
        <!--
        <th>Longitud (Grados)<?php //echo $this->Paginator->sort('longrados', 'Longitud (Grados)'); ?></th>
        <th>Longitud (Minutos)<?php //echo $this->Paginator->sort('lonminutos', 'Longitud (Minutos)'); ?></th>
        <th>Latitud (Grados)<?php //echo $this->Paginator->sort('latgrados', 'Latitud (Grados)'); ?></th>
        <th>Latitud (Minutos)<?php //echo $this->Paginator->sort('latminutos', 'Latitud (Minutos)'); ?></th>
        <th>Dirección E-O</th>
        <th>Dirección N-S</th>
        -->
        <th>Longitud</th>
        <th>Latitud</th>
        <?php if ($menuRol == 5 || $menuRol == 6) { ?>
            <th>Acción</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($resultado as $result): ?>
    <tr id="<?php echo $result['Puntocontrol']['id']; ?>">
        <td><?php echo $result['Puntocontrol']['id']; ?></td>
        <!-- <td>  <?php //echo $result['Ruta']['descripcion']; ?>  </td> -->
        <td class="descripcion">
            <?php echo $this->Html->link($result['Puntocontrol']['descripcion'],array('controller' => 'puntocontrols', 'action' => 'view', $result['Puntocontrol']['id'])); ?>
        </td>
        <td class="alias">
            <?php echo $result['Puntocontrol']['alias']; ?>
        </td>
        <td class="radio"><?php echo $result['Puntocontrol']['radio']?></td>
        <td>
            <?php echo $result['Puntocontrol']['faro']; ?>
        </td>        
        <td><?php echo $result['Puntocontrol']['tipo'] ? "Llega/Sale" : "Paso"; ?></td>
        <!--
        <td><?php #echo $result['Puntocontrol']['longrados']; ?></td>
        <td><?php #echo $result['Puntocontrol']['lonminutos']; ?></td>
        <td><?php #echo $result['Puntocontrol']['latgrados']; ?></td>
        <td><?php #echo $result['Puntocontrol']['latminutos']; ?></td>
        <td><?php #if ($result['Puntocontrol']['direccioneo']=='E'){echo "Este";} else {echo "Oeste";} ?></td>
        <td><?php #if ($result['Puntocontrol']['direccionns']=='N'){echo "Norte";} else {echo "Sur";} ?></td>
        -->
        <td class="longitud"><?php echo $result['Puntocontrol']['longitud']; ?></td>
        <td class="latitud"><?php echo $result['Puntocontrol']['latitud']; ?></td>
        <?php if ($menuRol == 5 || $menuRol == 6) { ?>
			<td>
				<?php echo $this->Html->link('Editar',array('controller' => 'puntocontrols', 'action' => 'edit', $result['Puntocontrol']['id'])); ?>
				<?php
					echo $this->Form->postLink(
						'Eliminar',
						array('action' => 'delete', $result['Puntocontrol']['id']),
						array('confirm' => '¿Está seguro de eliminar este puntocontrol?')
					);
				?>
			</td>
        <?php } ?>
    </tr>
    <?php endforeach; ?>
    <?php unset($result); ?>
    </tbody>
</table>

<!-- PAGINADOR -->
<?php 
	/*
	echo $this->Paginator->first(' |<  ');
      echo $this->Paginator->prev(' < ',array(),null,
        array('class' => 'prev disabled')); 
      echo $this->Paginator->numbers();
      echo $this->Paginator->next(' > ',array(),null,
        array('class' => 'next disabled')); 
      echo $this->Paginator->last(' >| ');
      echo $this->Paginator->counter(
    'Página {:page} de {:pages}, mostrando {:current} registros de un total de {:count}, del #{:start} al #{:end}'
        );
     */
?>
<div id="map" class="gmap3">
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d7098.94326104394!2d78.0430654485247!3d27.172909818538997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2s!4v1385710909804" width="600" height="450" frameborder="0" style="border:0"></iframe>
</div>
<?php
	} //end resultado
?>


