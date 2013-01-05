<?php

namespace WT\SemestralkaBundle\Controller;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use WT\SemestralkaBundle\Entity\Model;
use WT\SemestralkaBundle\Form\ModelType;
use WT\SemestralkaBundle\Entity\Seat;
//use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Model controller.
 *
 * @Route("/model")
 */
class ModelController extends Controller
{
    /**
     * Lists all Model entities.
     *
     * @Route("/", name="model")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WTSemestralkaBundle:Model')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Fill model
     *
     * @Route("/{id}/fill", name="model_fill")
     * @Template()
     */
    public function fillAction($id) {
        /*$session = new Session();        
        var_dump($_POST);*/
           
        $form = $this->createFormBuilder()
            ->add('sizeofgroup')
            ->add('n')
            ->getForm();        

        $em = $this->getDoctrine()->getManager();

        $seats = $em->getRepository('WTSemestralkaBundle:Seat')->findBy(
            array('model' => $id)
        );

            
        $model = $em->getRepository('WTSemestralkaBundle:Model')->find($id);

        /*if(isset($_POST['form'])) {
            $modelCapacity = $session->get('modelcapacity');
            $model = $model->setCapacity($modelCapacity);
        }*/

        $modelCapacity = $model->getCapacity();

        /*if(isset($_POST['form'])) {
            $refactoredSeats = $session->get('refactoredseats');
            echo "beru ze session";
        } else {
            $refactoredSeats = $this->refactorSeats($seats);
            echo "beru z db";
        }*/
        $refactoredSeats = $this->refactorSeats($seats);

        $refactoredSeatsArray = $this->countPrices($refactoredSeats);
            $refactoredSeats = $refactoredSeatsArray['refactoredseats'];
            $avgPrice = $refactoredSeatsArray['avgprice'];

        $refactoredSeatsSteps = NULL;

        if(isset($_POST['form'])) {
            if($_POST['form']['sizeofgroup'] > $modelCapacity)
                return array(
                'refactoredSeats'      => $refactoredSeats,
                'refactoredSeatsSteps' => $refactoredSeatsSteps,
                'model'                => $model,
                'form'                 => $form->createView(),
                'error'                => 'Model does not have enough empty seats!',
            );
            for ($i=1; $i <= $_POST['form']['sizeofgroup']; $i++) {                       
                $target = $this->pickSeat($refactoredSeats, $avgPrice, $i);

                $refactoredSeats = $this->sitDown($refactoredSeats, $target, $i, $_POST['form']['n']);
                $modelCapacity = $model->decCapacity();

                $refactoredSeatsArray = $this->countPrices($refactoredSeats);
                    $refactoredSeats = $refactoredSeatsArray['refactoredseats'];
                    $avgPrice = $refactoredSeatsArray['avgprice'];

                //$refactoredSeatsSteps[] = $refactoredSeats;
            }                        
        }            
        
        /*$session->set('refactoredseats', $refactoredSeats);
        $session->set('modelcapacity', $model->getCapacity()); */       

        //print_r($target);
        

        return array(
            'refactoredSeats'      => $refactoredSeats,
            'refactoredSeatsSteps' => $refactoredSeatsSteps,
            'model'                => $model,
            'form'                 => $form->createView(),
            'error'                => 0,
        );
    }

    /*vybere sedadlo kam si sednout a vrati jeho pozici*/
    public function pickSeat($refactoredSeats, $avgPrice, $order = 0) {
        $countInitialSeats = 0;
        $countIdealInitialSeats = 0;
        foreach ($refactoredSeats as $rowIn) {
            foreach ($rowIn as $seatIn) {
                if($seatIn->getInitial()) {
                    if($seatIn->getPrice() >= $avgPrice) {
                        $initialSeats[] = $seatIn;
                        $countInitialSeats++;
                    }

                    if($seatIn->getInitial() <= $order || $seatIn->getInitial() === '1') {
                        $idealInitialSeats[] = $seatIn;
                        $countIdealInitialSeats++;
                    }
                }                
            }
        }      

        if($countIdealInitialSeats) {
            //echo "nalezam $countIdealInitialSeats / $countInitialSeats idealnich sedadel v kroku $order<br />";
            $targetIndex = rand(0, $countIdealInitialSeats - 1);

            $row = $idealInitialSeats[$targetIndex]->getRow();
            $col = $idealInitialSeats[$targetIndex]->getCol();
        } else {
            $targetIndex = rand(0, $countInitialSeats - 1);

            $row = $initialSeats[$targetIndex]->getRow();
            $col = $initialSeats[$targetIndex]->getCol();
        }

        return array(
            'row' => $row,
            'col' => $col,
        );
    }
    /*obsadi sedadlo a zmeni sousedni sedadla na initial*/
    public function sitDown($refactoredSeats, $target = NULL, $order = 0, $n = 1) {
        
        $targetRow = $target['row'];
        $targetCol = $target['col'];
        $refactoredSeats[$targetRow][$targetCol]->setEmpty(0);
        $refactoredSeats[$targetRow][$targetCol]->setOrder($order);
        $refactoredSeats[$targetRow][$targetCol]->setClass('obsazeno');//fixme
        
        $targetRowSeats = $refactoredSeats[$targetRow];
        if($refactoredSeats[$targetRow][$targetCol]->getEnding() == 0) {
            foreach ($targetRowSeats as $seat) {
                if($seat->getEmpty() && $seat->getAvailable() && $seat->getCol() < $targetCol)
                    $leftSeatCol = $seat->getCol();
                if($seat->getEmpty() && $seat->getAvailable() && $seat->getCol() > $targetCol) {
                    $rightSeatCol = $seat->getCol();
                    break;
                }
            }
        }

        $refactoredSeats[$targetRow][$targetCol]->setInitial(0);

        if(isset($leftSeatCol)) {
            if(!$refactoredSeats[$targetRow][$leftSeatCol]->getInitial()) {
                $refactoredSeats[$targetRow][$leftSeatCol]->setInitial($order + $n);
                $refactoredSeats[$targetRow][$leftSeatCol]->setClass('initial');
            }
        }

        if(isset($rightSeatCol)) {
            if(!$refactoredSeats[$targetRow][$rightSeatCol]->getInitial()) {
                $refactoredSeats[$targetRow][$rightSeatCol]->setInitial($order + $n);
                $refactoredSeats[$targetRow][$rightSeatCol]->setClass('initial');
            }
        }

        return $refactoredSeats;
    }
    /*
    vrati sedadla s jejich cenou
    */
    public function countPrices($refactoredSeats) {
        $sumPrice = 0;
        $countInitialSeats = 0;
        foreach ($refactoredSeats as $row) {            
            foreach ($row as $seat) {
                if($seat->getInitial()) {
                    $countInitialSeats++;
                    $price = 0;
                    foreach ($refactoredSeats as $rowIn) {            
                        foreach ($rowIn as $seatIn) {
                            if($seatIn->getEmpty() == 0) {
                                $price += abs($seat->getRow() - $seatIn->getRow()) + abs($seat->getCol() - $seatIn->getCol());
                            }
                        }
                    }
                    $sumPrice += $price;
                    $seat->setPrice($price);
                }
            }
        }
        if($countInitialSeats)
            $avgPrice = ceil($sumPrice / $countInitialSeats);
        else
            $avgPrice = $sumPrice;

        return array(
            'refactoredseats' => $refactoredSeats,
            'avgprice' => $avgPrice,
        );
    }

    /**
     * Finds and displays a Model entity.
     *
     * @Route("/{id}/show", name="model_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WTSemestralkaBundle:Model')->find($id);

        $seats = $em->getRepository('WTSemestralkaBundle:Seat')->findBy(
            array('model' => $id)
        );
        
        $refactoredSeats = $this->refactorSeats($seats);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Model entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'refactoredSeats'      => $refactoredSeats,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Model entity.
     *
     * @Route("/new", name="model_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Model();
        $form   = $this->createForm(new ModelType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Model entity.
     *
     * @Route("/create", name="model_create")
     * @Method("POST")
     * @Template("WTSemestralkaBundle:Model:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Model();
        $form = $this->createForm(new ModelType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);            
            
            $someNewFilename = "newfile.csv";
            $dir = "upload";

            $form['file']->getData()->move($dir, $someNewFilename);

            $modelCsv = @file_get_contents("$dir/$someNewFilename");
            foreach (explode("\n", $modelCsv) as $rowIndex => $rowValue)  {
                if ($rowIndex != 0) {
                    foreach (explode(',', $rowValue) as $colIndex => $colValue) {
                        if ($colIndex == 0) {
                            $row = $colValue;
                        } else {
                            if($colValue)
                                $entity->incCapacity();
                            $seat = new Seat();
                            $seat->setRow($row);
                            $seat->setCol($colIndex);
                            $seat->setModel($entity);
                            $seat->setEmpty(1);
                            if($colValue == 1)
                                $seat->setAvailable(1);
                            else
                                $seat->setAvailable(0);
                            if($colValue == 3 || $colValue == 7)
                                $seat->setInitial(1);
                            else
                                $seat->setInitial(0);
                            if($colValue == 5 || $colValue == 7)
                                $seat->setEnding(1);
                            else
                                $seat->setEnding(0);

                            $em->persist($seat);
                        }   
                    }                    
                }
            }
            $em->flush();

            return $this->redirect($this->generateUrl('model_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Model entity.
     *
     * @Route("/{id}/edit", name="model_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WTSemestralkaBundle:Model')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Model entity.');
        }

        $editForm = $this->createForm(new ModelType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Model entity.
     *
     * @Route("/{id}/update", name="model_update")
     * @Method("POST")
     * @Template("WTSemestralkaBundle:Model:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WTSemestralkaBundle:Model')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Model entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ModelType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('model_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Model entity.
     *
     * @Route("/{id}/delete", name="model_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WTSemestralkaBundle:Model')->find($id);

            $seats = $em->getRepository('WTSemestralkaBundle:Seat')->findBy(
            array('model' => $id)
            );

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Model entity.');
            }
            if ($seats) {
                foreach ($seats as $seat) {
                    $em->remove($seat);
                }            
            }
            
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('model'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    public function refactorSeats($seats)
    {
        foreach ($seats as $seat) {
            $row = $seat->getRow();
            $col = $seat->getCol();
            if($seat->getAvailable())
                $seat->setClass('available');
            elseif($seat->getInitial() && !$seat->getEnding())
                $seat->setClass('initial');
            elseif($seat->getEnding() && !$seat->getInitial())
                $seat->setClass('ending');
            elseif($seat->getEnding() && $seat->getInitial())
                $seat->setClass('initialAndEnding');



            $refactoredSeats[$row][$col] = $seat;
        }
    
        return $refactoredSeats;
    }
}
