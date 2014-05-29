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

        //Array php con los puntos de control guardado como JSON en puntosControl
        var puntosControl= <?php echo json_encode($resultado)?>;
        var value= null;
        var middle= Math.round(puntosControl.length/2)-1;
        var centerMap= [puntosControl[middle]['Puntocontrol']['latitud'], puntosControl[middle]['Puntocontrol']['longitud']];
        console.log("Mapa centrado en: "+centerMap);
        var zoom=12;
        var showRadio= true;
        //Se mandan parametros a la funcion que dibuja los markers y radios.
        //puntosControl= JSON con todos los PCs de la ruta, value= Datos del nuevo PC (marker verde), centerMap= centro del mapa y posicion inicial del nuevo PC (marker verde), zoom= valor del zoom del mapa, showRadio= true para mostrar radio inicial del nuevo PC, aplica para edit.ctp     
        drawMarkers(puntosControl, value, centerMap, zoom, showRadio);        
        
        //Al dar click a cualquier fila de la tabla de los PCs te indica cual es el PC seleccionado, mostrando su infowindow
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
            $("#map").animatescroll({scrollSpeed:700,easing:'easeOutSine'});
        });

    });
</script>

<?php $menuRol = $this->Session->read('Auth.User.rol_id'); ?>

<script type="text/javascript">
	$(document).ready(function() { $("#tablaPC").tablesorter(); } ); 
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".submit").html("");
        $("#puntocontrolRutaId").change(function(){
            if($("#puntocontrolRutaId").val() !== ""){
                $("#puntocontrolIndexForm").submit();
            }
        });
    });
</script>
<h2>Catálogo de puntos de control</h2>
<?php
	echo $this->Form->create();
	echo $this->Form->input('ruta_id',array('label'=>'Ruta','type'=>'select','empty'=>' - Elige una ruta - ','options'=>$rutas));
	echo $this->Form->end('Buscar puntos de control asignados a ruta');	
?>
<?php if(isset($prevRuta) && !empty($prevRuta) && ($menuRol == 5 || $menuRol == 6) ){
    echo $this->Html->link( 'Agregar punto de control',array('controller' => 'puntocontrols', 'action' => 'add',$prevRuta));
}?>
<?php
	if (!empty($resultado)) {
?>
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

