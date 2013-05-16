<?php

namespace Promo\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Promo\Bundle\Entity\EntityCategoria;
use Promo\Bundle\Form\FormCategoria;
use Promo\Bundle\Entity\EntityImatge;

class AdminController extends Controller
{
    public function categoriaAction()
    {
    	$request = $this->getRequest();
    	 
    	$categoria = new EntityCategoria();
    	
    	/*$imatge = new EntityImatge();
    	$categoria->setImatge($imatge);*/
    	
    	$form = $this->createForm(new FormCategoria(), $categoria);
    	
    	if ($this->getRequest()->getMethod() === 'POST') {
    		$form->bindRequest($this->getRequest());
    		
    		if ($form->isValid()) {
    			
    			$em = $this->getDoctrine()->getEntityManager();
    		
    			if ($categoria->getImatge()->upload($categoria->getNom()) == true) {
	    			$em->persist($categoria->getImatge());
	    			$em->persist($categoria);
	    			
	    			$em->flush();
    		
	    			return $this->redirect($this->generateUrl('PromoBundle_catalogo'));
    			}
    			$this->get('session')->setFlash('sms-notice','Error durante la carga de la imagen');
    		}
    	}
    	
    	
    	return $this->render('PromoBundle:Admin:categoria.html.twig',
    			array('form' => $form->createView(), 'pare' => null, 'admin' => true));
    	
    	/*
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$currentCategoria = null;
    	$categories = null;
    	if ($request->query->has('categoria')) {
   			// Una categoria seleccionada  
   			$currentCategoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('categoria'));
   			
   			if ($currentCategoria->getProductes() != null) {
   				// No té categories filles, mostrar productes
   				
   				
   			} else {
   				$categories = $currentCategoria->getFills();
   			}
    	}
    	
    	if ($categories == null) {
    		// Seleccionar categories arrel, pare=null
    		$strQuery = "SELECT c FROM Promo\Bundle\Entity\EntityCategoria c ";
    		$strQuery .= " WHERE c.pare IS NULL ";
    		$strQuery .= " ORDER BY c.nom";
    		
    		$query = $em->createQuery($strQuery);
    		
    		$categories = $query->getResult();
    	}
    	
    	return $this->render('PromoBundle:Productes:cataleg.html.twig',		array('categories' => $categories)); */
    }
    
}
