<?php

namespace Promo\Bundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Promo\Bundle\Form\FormContact;
use Promo\Bundle\Entity\EntityContact;
use Symfony\Component\HttpFoundation\Response;

class StaticController extends BaseController
{
    public function indexAction()
    {
        return $this->render('PromoBundle:Static:index.html.twig', array('admin' => $this->isCurrentAdmin()));
    }
    public function empresaAction()
    {
    	return $this->render('PromoBundle:Static:empresa.html.twig', array('admin' => $this->isCurrentAdmin()));
    }
    public function consultoriaAction()
    {
    	return $this->render('PromoBundle:Static:consultoria.html.twig', array('admin' => $this->isCurrentAdmin()));
    }
    
    public function contactoAction() {
    	$request = $this->getRequest();
    
    	$contact = new EntityContact();
    
   		$form = $this->createForm(new FormContact(), $contact);

    	  if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    
    		if ($form->isValid()) {
    			$message = \Swift_Message::newInstance()
    				->setSubject('[Contacto Promoquality] - '. $contact->getAssumpte())
    				->setFrom($contact->getEmail())
    				->setBcc(array('alexmazinho@gmail.com'))
    				->setTo(array('info@promoquality.com'));

    			$logosrc = $message->embed(\Swift_Image::fromPath('images/logo_promoquality.png'));
    			
    			$body = $this->renderView('PromoBundle:Static:contactEmail.html.twig',
    					array('contact' => $contact, 'logo' => $logosrc));
    			
    			$message->setBody($body, 'text/html');
    
    			$this->get('mailer')->send($message);
    			$this->get('session')->setFlash('sms-notice','Petición enviada correctamente. Gracias');
    
    			// Redirect - This is important to prevent users re-posting
    			// 	the form if they refresh the page
    			return $this->redirect($this->generateUrl('PromoBundle_contacto'));
    		}
    	}
    
    	return $this->render('PromoBundle:Static:contact.html.twig',
    			array('form' => $form->createView(), 'admin' => $this->isCurrentAdmin()));
    }
}
