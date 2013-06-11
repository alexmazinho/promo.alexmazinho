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
    			->setSubject('::Contacto Promoquality::'. $form->getData()->getSubject())
    			->setFrom($form->getData()->getEmail())
    			->setTo($this->getContactMails())
    			->setBody($this->renderView('FecdasPartesBundle:Page:contactEmail.txt.twig',
    					array('contact' => $contact)));
    
    			$this->get('mailer')->send($message);
    			$this->get('session')
    			->setFlash('sms-notice','Petició enviada correctament. Gràcies!');
    
    			// Redirect - This is important to prevent users re-posting
    			// 	the form if they refresh the page
    			return $this->redirect($this->generateUrl('PromoBundle_contacto'), array('admin' => $this->isCurrentAdmin()));
    		}
    	}
    
    	return $this->render('PromoBundle:Static:contact.html.twig',
    			array('form' => $form->createView(), 'admin' => $this->isCurrentAdmin()));
    }
}
