<?php

namespace Promo\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;

class ProductesController extends Controller
{
    public function catalogoAction()
    {
    	$request = $this->getRequest();
    	
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
    	
    	return $this->render('PromoBundle:Productes:cataleg.html.twig',		array('categories' => $categories)); 
    }
    
}
