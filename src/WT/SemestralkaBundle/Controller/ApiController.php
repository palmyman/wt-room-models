<?php

namespace WT\SemestralkaBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * API modelu, vraci rovnou Response s objekty
 * v JSON formatu
 * 
 * @author kadleto2
 * 
 */
class ApiController extends Controller {
	/**
	 * Pouze zobrazí příslušnou stránku. Vše ostatní se děje AJAXem.
	 * @Route("/{id}/modelui", name="modelApi")
	 * @Template("WTSemestralkaBundle:Model:newui.html.twig")
	 * 
	 */
	public function indexAction($id) {
		return array("id" => $id);
	}
	
	/**
	 * Vytvori novy model
	 * @Route("/{id}/modelui/new", name="modelApiNew")
	 */
	public function newAction($id) {
		$room = $this->getDoctrine()->getRepository('WTSemestralkaBundle:Room')->find($id);

        $rows = $room->getRows();
        $cols = $room->getCols();        
		$model = $this->newModel($rows, $cols);
		$this->getRequest()->getSession()->set('model', $model);
		return $this->createResponse("Vytvořen nový model.");
	}

	/**
	* Nacte model
	* @Route("/modelui/load", name="modelApiLoad")
	*/
	public function loadAction() {
		$model =  $this->getRequest()->getSession()->get('model');
		// kdyz model v session neni, vytvori se novy
		if (empty($model) && !is_array($model)) {
			// tohle je spatne, na to aplikace neni stavena ...
			//return $this->forward('CvutFitBiWt2JQueryAjaxBundle:Api:new');
			// mela by se tu vratit chybova odpoved (nebo by se melo UI zeptat,
			// jesli je mozne nacist model ...
			$model = $this->newModel();
			$this->getRequest()->getSession()->set('model', $model);
		}
		return $this->createResponse($model);
	}
	
	/**
	 * Smaze model
	 * @Route("/modelui/delete", name="modelApiDelete")
	 */
	public function deleteModelAction() {
		$model = array();
		$this->getRequest()->getSession()->set('model', $model);
		return $this->createResponse("Model byl smazán.");
	}
	
	/**
	 * Aktualizuje model
	 * @Route("/modelui/save", name="modelApiSave")
	 */
	public function saveModelAction() {
		$a = json_decode($this->getRequest()->getContent(false));
		$session = $this->getRequest()->getSession(); 
		$model = $session->get('model');
		//$model[$a[0]][$a[1]] = $a[2];
		switch ($model[$a[0]][$a[1]]) {
			case 0: $model[$a[0]][$a[1]] = 1; break;
			case 1: $model[$a[0]][$a[1]] = 3; break;
			case 3: $model[$a[0]][$a[1]] = 5; break;
			case 5: $model[$a[0]][$a[1]] = 0; break;
		}
		$session->set('model', $model);
		// je nutne session rucne ulozit a uzavrit -> session_write_close!
		$session->save();
		// prodleva
		$sleep = 0;
		if (isset($a[3]) && $a[3] == -1) {
			$sleep = rand(0,5); 
		} else if (isset($a[3]) && $a[3] >= 1 && $a[3] <= 5) {
			$sleep = $a[3];
		}
		sleep($sleep);
		// a konecne odpoved
		return $this->createResponse(array(
				'data' => array($a[0], $a[1], $model[$a[0]][$a[1]]),
				'message' => "Uloženo [{$a[0]},{$a[1]}] = {$model[$a[0]][$a[1]]}"
		));
	}
	
	/**
	 * Interni pomocna funkce, inicializuje novy model
	 * @return Ambigous <multitype:, number>
	 */
	protected function newModel($rows, $cols) {
		$model = array();
		for ($i = 1; $i <= $rows; $i++) {
			for ($j = 1; $j <= $cols; $j++) {
				$model[$i][$j] = 0;
			}
		}
		return $model;
	}
	
	/**
	 * Interni pomocna funkce na vytvareni json odpovedi
	 * @param unknown $model
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function createResponse($model = array()) {
		return new Response(
				json_encode($model),
				200,
				array(
						'Content-Type', 'application/json'
				)
		);
	}
}