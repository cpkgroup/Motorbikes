<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;

use AppBundle\Entity\Motorbikes;
use AppBundle\Form\MotorbikesType;

/**
 * Motorbikes controller.
 *
 */
class MotorbikesController extends Controller
{

    /**
     * Lists all Motorbikes entities.
     *
     */
    public function indexAction($page=1 , $sort= 'id' , $sort_type= 'asc')
    {
        $em = $this->getDoctrine()->getManager();
        $motorbikes_conf = $this->getParameter('motorbikes');
        $items_per_page = $motorbikes_conf['items_per_page'];

/* 		$dql = "SELECT p FROM AppBundle:Motorbikes p ";
		$query = $em->createQuery($dql)
							   ->setFirstResult(0)
							   ->setMaxResults(5); */
        $page=(int)$page;
        $page=$page>0?$page:1;
        $result = $em->createQueryBuilder()
			->select('p')
			->from('AppBundle:Motorbikes','p')
            ->orderBy('p.'.$sort,strtoupper($sort_type))
            ->getQuery()
            ->setFirstResult(($page-1)*$items_per_page)
            ->setMaxResults($items_per_page);

        $entities = new Paginator($result,false);
        // $entities = $em->getRepository('AppBundle:Motorbikes')->findAll();

        $count_all = count($entities);
        // $count_all = 8;

        $pagination = new \AppBundle\Library\Pagination($this , $page , $count_all , $items_per_page);
        $pagination_view = $pagination->render('motorbikes_list' , array('sort'=> $sort , 'sort_type'=>$sort_type ));
        return $this->render('AppBundle:Motorbikes:index.html.twig', array(
            'entities' => $entities,
            'default_sort' => $sort,
            'default_sort_type' => $sort_type,
            'default_page' => $page,
            'upload_dir' => $motorbikes_conf['upload_dir'],
            'pagination' => $pagination_view,
        ));
    }
    /**
     * Creates a new Motorbikes entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Motorbikes();
        $entity->setUploadRootDir($this->getUploadRootDir());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('motorbikes_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:Motorbikes:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Motorbikes entity.
     *
     * @param Motorbikes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Motorbikes $entity)
    {
        $form = $this->createForm(new MotorbikesType(), $entity, array(
            'action' => $this->generateUrl('motorbikes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create' , 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new Motorbikes entity.
     *
     */
    public function newAction()
    {
        $entity = new Motorbikes();
        $entity->setUploadRootDir($this->getUploadRootDir());
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:Motorbikes:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Motorbikes entity.
     *
     */
    public function showAction($id)
    {
        $motorbikes_conf = $this->getParameter('motorbikes');
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Motorbikes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Motorbikes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Motorbikes:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'upload_dir' => $motorbikes_conf['upload_dir'],
        ));
    }

    /**
     * Displays a form to edit an existing Motorbikes entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Motorbikes')->find($id);
        $entity->setUploadRootDir($this->getUploadRootDir());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Motorbikes entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Motorbikes:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Motorbikes entity.
    *
    * @param Motorbikes $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Motorbikes $entity)
    {
        $form = $this->createForm(new MotorbikesType(), $entity, array(
            'action' => $this->generateUrl('motorbikes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr'=>array('class'=>'btn btn-success')));

        return $form;
    }
    /**
     * Edits an existing Motorbikes entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Motorbikes')->find($id);
        $entity->setUploadRootDir($this->getUploadRootDir());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Motorbikes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('motorbikes_show', array('id' => $id)));
        }

        return $this->render('AppBundle:Motorbikes:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Motorbikes entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Motorbikes')->find($id);
            $entity->setUploadRootDir($this->getUploadRootDir());

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Motorbikes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('motorbikes'));
    }

    /**
     * Creates a form to delete a Motorbikes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('motorbikes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr'=>array('class'=>'btn btn-danger')))
            ->getForm()
        ;
    }

    private function getUploadRootDir(){
        $motorbikes_conf = $this->getParameter('motorbikes');
        return $this->get('kernel')->getRootDir().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.
        $motorbikes_conf['web_dir'].DIRECTORY_SEPARATOR.$motorbikes_conf['upload_dir'];
    }
}
