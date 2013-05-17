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
    	 
    	$categoria = new EntityCategoria();
    	
    	$pare = null;
    	if ($this->getRequest()->getMethod() == 'GET') {
    		if ($request->query->has('pare') and $request->query->get('pare') != 0) {
    			$pare = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('pare'));
    			$categoria->setPare($pare);
    		}
    	}
    	
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
    			array('form' => $form->createView(), 'pare' => ($pare == null)?null:$pare, 'admin' => true));
    }
    
    public function productoAction()
    {
    	$request = $this->getRequest();
    
    	$producte = new EntityProducte();
    	 
    	$categoria = null;
    	if ($this->getRequest()->getMethod() == 'GET') {
    		if ($request->query->has('categoria') and $request->query->get('categoria') != 0) {
    			$categoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('categoria'));
    			$producte->setCategoria($categoria);
    		}
    	}
    	 
    	$form = $this->createForm(new FormProducte(), $producte);
    	 
    	if ($this->getRequest()->getMethod() === 'POST') {
    		$form->bindRequest($this->getRequest());
    
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
    			array('form' => $form->createView(), 'producte' => $producte, 'admin' => true));
    }
}
