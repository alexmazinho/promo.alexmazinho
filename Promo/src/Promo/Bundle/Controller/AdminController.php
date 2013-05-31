<?php

namespace Promo\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Promo\Bundle\Entity\EntityProducte;
use Promo\Bundle\Form\FormProducte;
use Promo\Bundle\Entity\EntityCategoria;
use Promo\Bundle\Form\FormCategoria;
use Promo\Bundle\Entity\EntityImatge;

class AdminController extends Controller
{
    public function categoriaAction()
    {
    	$request = $this->getRequest();

    	$admin = true;  /* pendent */
    	
    	$categoria = new EntityCategoria();
    	$pare = null;
    	
    	if ($request->getMethod() == 'GET') {
    		if ($request->query->has('categoria') and $request->query->get('categoria') != 0) {
    			// Edició 
    			$categoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('categoria'));
    		} 
    		
    		if ($request->query->has('pare') and $request->query->get('pare') != 0) {
    			// Nova 
   				$pare = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('pare'));
   				$categoria->setPare($pare);
    		}
    	} else  {
    		$c = $request->request->get('categoria');

			if ($c['id'] != "") $categoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($c['id']);
		} 
    	
    	$form = $this->createForm(new FormCategoria(), $categoria); 
    	
    	if ($request->getMethod() === 'POST') {
    		$form->bindRequest($request);
    		
    		if ($form->isValid()) {

    			$uploadedfile = $form['imatge']->getData();
    			
    			$portada = null;
    			$uploadReturn = true;
    			$em = $this->getDoctrine()->getEntityManager();
				if ($uploadedfile != null) {
	    			$portada = new EntityImatge($uploadedfile);		
					$portada->setTitol("Categoría " . $categoria->getNom());
					$em->persist($portada);
					$categoria->setImatge($portada);
					$uploadReturn = $portada->upload($categoria->getNom());
				}
				
    			if ($uploadReturn == true) {
    				
	    			if ($categoria->getId() == null) $em->persist($categoria);  // Nova
	    			
	    			$em->flush();
    		
	    			return $this->redirect($this->generateUrl('PromoBundle_catalogo', 
	    					array('categoria' => ($categoria->getPare() != null)?$categoria->getPare()->getRuta():"")));
    			} else $this->get('session')->setFlash('sms-notice','Error durante la carga de la imagen');
    		} else $this->get('session')->setFlash('sms-notice','Error en la validación de los datos');
    	}
    	
    	return $this->render('PromoBundle:Admin:categoria.html.twig',
    			array('form' => $form->createView(), 'portada' => $categoria->getImatge(), 'pare' => ($pare == null)?null:$pare, 'admin' => $admin));
    }
    
    public function removecategoriaAction()
    {
    	$request = $this->getRequest();
    	
    	$admin = true;  /* pendent */
    	
    	if ($request->getMethod() == 'GET') {
    		if ($request->query->has('categoria') and $request->query->get('categoria') != 0) {
    			// Edició
    			$categoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('categoria'));
    		}
    	}
    	
    	if ($categoria != null and count($categoria->getFills()) == 0) {
    		$em = $this->getDoctrine()->getEntityManager();
    		$em->remove($categoria); 
    		$em->flush();
    		
    		return $this->redirect($this->generateUrl('PromoBundle_catalogo',
    				array('categoria' => ($categoria->getPare() != null)?$categoria->getPare()->getRuta():"")));
    	}
    	$this->get('session')->setFlash('sms-notice','Esta categoría contiene objetos internos y no se puede eliminar');
    	return $this->redirect($this->generateUrl('PromoBundle_catalogo'));
    	
    }
    
    public function productoAction()
    {
    	$request = $this->getRequest();
    
    	$admin = true;  /* pendent */
    	
    	$producte = new EntityProducte();
    	 
    	$categoria = null;
    	if ($request->getMethod() == 'GET') {
    		if ($request->query->has('categoria') and $request->query->get('categoria') != 0) {
    			$categoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('categoria'));
    			$producte->setCategoria($categoria);
    		}
    	}
    	 
    	$form = $this->createForm(new FormProducte(), $producte);
    	 
    	if ($request->getMethod() === 'POST') {
    		$form->bindRequest($request);
    
    		if ($form->isValid()) {
    			$em = $this->getDoctrine()->getEntityManager();
    
    			if ($producte->getImatgePortada()->upload($producte->getNom()) == true) {
    				$em->persist($producte->getImatgePortada());
    				$em->persist($producte);
    
    				$em->flush();
    
    				return $this->redirect($this->generateUrl('PromoBundle_catalogo'));
    			}
    			$this->get('session')->setFlash('sms-notice','Error durante la carga de la imagen');
    		}
    	}
    	 
    	 
    	return $this->render('PromoBundle:Admin:producte.html.twig',
    			array('form' => $form->createView(), 'producte' => $producte, 'admin' => $admin));
    }
    
    public function galeriaAction()
    {
    	return new JsonResponse(array('name' => "Hola"), 200);
    	
    	//return new Response("Hola");
    }
}
