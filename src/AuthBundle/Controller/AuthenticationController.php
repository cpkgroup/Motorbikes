<?php
namespace AuthBundle\Controller;

use Symfony\Component\Security\Core\SecurityContextInterface;

class AuthenticationController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    public function indexAction(\Symfony\Component\HttpFoundation\Request $request, $name = '')
    {

/*        $user = $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY');
        var_dump($user);
        if($user){

            echo 111;
        }*/
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR))
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        else if (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR))
        {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }
        else
            $error = '';

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return $this->render(
            'AuthBundle:Authentication:index.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
        
//        $form = $this->createForm(new \AuthBundle\Form\LoginType());
    }
}