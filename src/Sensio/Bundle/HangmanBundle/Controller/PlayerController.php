<?php

namespace Sensio\Bundle\HangmanBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\HangmanBundle\Entity\Player;
use Sensio\Bundle\HangmanBundle\Form\PlayerType;

class PlayerController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     * @Template()
     *
     */
    public function registrationAction(Request $request)
    {
        $form = $this->createForm(new PlayerType());

        if ($request->isMethod('POST')) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $player = $form->getData();

                $factory = $this->geT('security.encoder_factory');
                $encoder = $factory->getEncoder($player);
                $player->setPassword($encoder->encodePassword(
                		$player->getRawPassword(),
                		$player->getSalt()
                	));

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($player);
                $em->flush();

                return $this->redirect($this->generateUrl('hangman_game'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     * 
     * @param Request $request
     * @return multitype:unknown
     */
    public function loginAction(Request $request)
    {
    	$session = $request->getSession();
    	$name = $session->get(SecurityContext::LAST_USERNAME);
    	$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);

    	$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	return array(
    		'last_username' => $name,
    		'error' => $error,);
    }
}