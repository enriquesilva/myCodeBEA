<?php

class PuntocontrolsController extends AppController {
	
	public $name = 'puntocontrols';
	public $helpers = array('Html', 'Form','Paginator','Session');
	public $ModelName = 'Puntocontrol';

	public $components = array('Paginator' => 'Puntocontrol');

	public $paginate = array(
        'limit' => 999,
        #'conditions' => array('Puntocontrol.ruta_id' => $this->data['puntocontrol']['ruta_id']),
        'order' => array(
            'Puntocontrol.id' => 'asc'
        ),
        'conditions'=>array('Puntocontrol.id <>'=>0)
    );
	
	
	/* FUNCION PRUEBA ENRIQUE */
	public function mapaViaje(){
			$this->layout = 'reporte';
			$this->loadModel('Puntocontrol');
			$resultado = $this->Puntocontrol->detalladoViaje('15','2014-04-24','5');
			  if(empty($resultado)){
				  $this->Session->setFlash(__('Servicio de Análisis Automático no disponible por el momento. Intente más tarde.'));
			  }
			  else if (isset($resultado['ERROR'])){
				  if (isset($resultado['Status']['0']['Status'])){
				  $this->Session->setFlash(__($resultado['Status']['0']['Status']['mensaje']));
				  }
			  }else{
				  $this->Session->setFlash('El Análisis se realizó exitosamente.','default',array('class'=>'notice success'));
				  $this->set('detallado',$resultado);
			  }
	}

	public function index() {
        $this->layout = 'puntocontrols';
        #$this->loadModel('Puntocontrol');
        #$this->Paginator->settings = $this->paginate;
        #$this->set('puntocontrols', $this->Paginator->paginate('Puntocontrol'));
        
        if (!empty($this->data)) {
		  $paginate = array('limit' => 999,'conditions' => array('Puntocontrol.ruta_id' => $this->data['puntocontrol']['ruta_id'],'Puntocontrol.id <>'=>0),'order' => array('Puntocontrol.id' => 'asc'	));
		  
		  $this->loadModel('Puntocontrol');
		  $this->Paginator->settings = $paginate;
		  #$resultado = $this->Puntocontrol->find('all',array('conditions' => array('Puntocontrol.ruta_id' => $this->data['puntocontrol']['ruta_id'])));
		  $resultado = $this->Paginator->paginate('Puntocontrol');
		  if(empty($resultado)){
			  $this->Session->setFlash(__('No hay puntos de control asignados a esa ruta.'));
		  }
		  else{
			  $this->Session->setFlash('Mostrando puntos de control asignados a ruta.','default',array('class'=>'notice success'));
			  $this->set('resultado',$resultado);
		  }
		}
        $this->loadModel('Ruta');
        $this->set('rutas',$this->Ruta->find('list',array('fields' => array('Ruta.id','Ruta.descripcion'),'conditions'=>array('Ruta.id <>'=>0), 'order' => array('Ruta.descripcion ASC'),'recursive' => 1)));
        if($this->Session->check('LastPoint.prRuta')){ $this->Session->delete('LastPoint.prRuta');}
    }
    
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Punto de control no válido'));
        }
        $this->loadModel('Puntocontrol');
        $puntocontrol = $this->Puntocontrol->findById($id);
        if (!$puntocontrol) {
            throw new NotFoundException(__('Punto de control no válido'));
        }
        $this->set('puntocontrol', $puntocontrol);
    }
    
    //condición para mostrar sólo usuarios activos está comentada
    public function add() {
		$this->layout = 'puntocontrols';
        $this->loadModel('Ruta');
        $this->set('rutas', $this->Ruta->find('list', array('fields' => array('Ruta.id', 'Ruta.descripcion'),'order' => array('Ruta.descripcion ASC'),'conditions'=>array('Ruta.id <>'=>0),'recursive' => 0)));
		
		$prevLong=-99.15; $prevLat = 19.32;
		if($this->Session->check('LastPoint')){ 	$prevLong = $this->Session->read('LastPoint.Longitud'); $prevLat = $this->Session->read('LastPoint.Latitud'); 	}
		$this->set('prevLong',$prevLong); 	$this->set('prevLat',$prevLat);
		
        $this->loadModel('Puntocontrol');
        if ($this->request->is('post')) {
            $this->Puntocontrol->create();
            if ($this->Puntocontrol->save($this->request->data)) {
                $this->Session->setFlash('Punto de control guardado exitosamente','default',array('class'=>'success'));
				$this->Session->write('LastPoint.Longitud',$this->data['Puntocontrol']['longitud']);
				$this->Session->write('LastPoint.Latitud',$this->data['Puntocontrol']['latitud']);
				$this->Session->write('LastPoint.prRuta',$this->data['Puntocontrol']['ruta_id']);
				return $this->redirect(array('action'=>'add'));
				}else{ $this->Session->setFlash(__('No se pudo agregar el punto de control.')); }	
            }
        }

    public function edit($id = null) {
		$this->layout = 'puntocontrols';
	    if (!$id) {
	        throw new NotFoundException(__('Punto de control no válido'));
	    }

	    $this->loadModel('Puntocontrol');
	    $puntocontrol = $this->Puntocontrol->findById($id);
	    #die(pr($puntocontrol));
	    $r = $puntocontrol['Puntocontrol']['ruta_id'];
	    $this->loadModel('Ruta');
        $this->set('rutas', $this->Ruta->find('list', array(
        	'fields' => array('Ruta.id', 'Ruta.descripcion'),
        	'order' => array('Ruta.descripcion ASC'),
        	'conditions'=>array('Ruta.id <>'=>0,'Ruta.id'=>$r),
        	'recursive' => 0
		)));

	    if (!$puntocontrol) { throw new NotFoundException(__('Punto de control no válido')); }


	    if ($this->request->is(array('post', 'put'))) {
	        $this->Puntocontrol->id = $id;
	        if ($this->Puntocontrol->save($this->request->data)) {
	            $this->Session->setFlash('Punto de control guardado exitosamente','default',array('class'=>'success'));
	            return $this->redirect(array('action' => 'index'));
	        }
	        $this->Session->setFlash(__('No se pudo editar el punto de control.'));
	    }
	   
	    if (!$this->request->data) {
	        $this->request->data = $puntocontrol;
	    }

	}

	public function delete($id) {
	    if ($this->request->is('get')) {
	        throw new MethodNotAllowedException();
	    }

		$this->loadModel('Puntocontrol');
		
		$this->loadModel('Tramo');
		$std = $this->Tramo->find('count',array('conditions'=>array('Tramo.idcontrolo'=>$id)));
		$aux = $this->Tramo->find('count',array('conditions'=>array('Tramo.idcontrold'=>$id)));
		if($std == 0 && $aux == 0 ){
			if ($this->Puntocontrol->delete($id)) {
				$this->Session->setFlash('Punto de Control eliminado exitosamente','default',array('class'=>'success'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('No se pudo eliminar el punto de control.'));
			}
		else{
			if ($std != 0 ) {
				$this->Session->setFlash(__('No se pudo eliminar el punto de control porque tiene tramos ligados a él.'));
				return $this->redirect(array('action' => 'index'));
			}
			if ($aux != 0 ) {
				$this->Session->setFlash(__('No se pudo eliminar el punto de control porque tiene tramos ligados a él.'));
				return $this->redirect(array('action' => 'index'));
			}
		}	
	}
}

?>
