<?php

namespace Promo\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;

class ProductesController extends Controller
{
    public function catalogoAction($categoria)
    {
    	$request = $this->getRequest();
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$currentCategoria = null;
    	
    	$categoriaid = explode("-", $categoria);
    	
    	$categories = null;
    	if ($categoriaid[0] != 0) {
   			// Una categoria seleccionada  
   			$currentCategoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($categoriaid[0]);
   			if (count($currentCategoria->getProductes()) > 0) {
   				// No té categories filles, mostrar productes forward
   				//$router->generate('blog_show', array('slug' => 'my-blog-post'), true);
   				echo count($currentCategoria->getProductes());
   			} else {
   				$categories = $currentCategoria->getFills();
   			}
    	} else {
    		// Seleccionar categories arrel, pare=null
    		$strQuery = "SELECT c FROM Promo\Bundle\Entity\EntityCategoria c ";
    		$strQuery .= " WHERE c.pare IS NULL ";
    		$strQuery .= " ORDER BY c.nom";
    		
    		$query = $em->createQuery($strQuery);
    		
    		$categories = $query->getResult();
    	}
    	
    	return $this->render('PromoBundle:Productes:cataleg.html.twig',	array('categories' => $categories, 'pare' => $currentCategoria, 'admin' => true)); 
    }
    
}
