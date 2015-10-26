<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use UserBundle\Entity\User;
use UserBundle\Form\EditUserType;
use UserBundle\Form\SignupUserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Creates a new User entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            /* @var $encoder \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface */
            $encoder = $this->get('security.encoder_factory')->getEncoder($entity);
            $entity->setPasswd($encoder->encodePassword($entity->getPasswd(), $entity->getSalt()));

            $entity->setStatus(true);

            $em->persist($entity);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', sprintf('You are signed up successfully dear %s %s! Please login here.',
                    $entity->getName(),$entity->getFamily())
                )
            ;


            return $this->redirect($this->generateUrl('auth_login'));
        }

        return $this->render('UserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new SignupUserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Signup', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);

        return $this->render('UserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->getUser()) {
            throw $this->createNotFoundException('You are logged out!');
        }

        $id = $this->getUser()->getId();
        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        return $this->render('UserBundle:User:show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->getUser()) {
            throw $this->createNotFoundException('You are logged out!');
        }

        $id = $this->getUser()->getId();
        /* @var $entity User */
        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('UserBundle:User:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new EditUserType(), $entity, array(
            'action' => $this->generateUrl('user_update'),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Edits an existing User entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->getUser()) {
            throw $this->createNotFoundException('You are logged out!');
        }

        $id = $this->getUser()->getId();

        /* @var $entity User */
        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('user_show'));
        }

        return $this->render('UserBundle:User:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        ));
    }
}
