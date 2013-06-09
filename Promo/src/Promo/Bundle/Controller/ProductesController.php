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
   				return $this->render('PromoBundle:Productes:productes.html.twig',	
   						array('productes' => $currentCategoria->getProductes(), 'categoria' => $currentCategoria, 'admin' => true));
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
    
    public function productoAction($producto)
    {

    	$request = $this->getRequest();
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$producteid = explode("-", $producto);
    	 
   		$currentProducto = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')
   							->find($producteid[0]);
    	
    	return $this->render('PromoBundle:Productes:producte.html.twig',	
    			array('producte' => $currentProducto,  'admin' => true, 
    					'socialdescription' => $currentProducto->getNom(), 'hashtag' => $currentProducto->getHashTag())); 
   		    
    }
    
    public function casosexitoAction() {
    	
    	$request = $this->getRequest();
    	
    	/* Consultar productes d'exit*/
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$strQuery = "SELECT p FROM Promo\Bundle\Entity\EntityProducte p ";
    	$strQuery .= " WHERE p.casexit = true ";
    	$strQuery .= " ORDER BY p.nom";
    		
    	$query = $em->createQuery($strQuery);
    		
    	$productes = $query->getResult();
    	    	
    	return $this->render('PromoBundle:Productes:casosexit.html.twig',
    			array('productes' => $productes, 'admin' => true, 
    					'socialdescription' => 'Casos de éxito', 'hashtag' => 'PromoCasosExito'));
    		
    }
}
