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
use Promo\Bundle\Entity\EntityUsuari;
use Promo\Bundle\Form\FormUsuari;

class AdminController extends BaseController
{
	public function loginAction()  {
		$request = $this->getRequest();
		
		$admin = $this->isCurrentAdmin();
		if ($admin == true) return $this->redirect($this->generateUrl('PromoBundle_homepage'));
		
		$formBuilder = $this->createFormBuilder()
					->add('usuari', 'email')
					->add('pwd', 'password');

		$form = $formBuilder->getForm();
		/*INSERT INTO `promo`.`usuaris` (`usuari`, `mail`, `pwd`, 
		 * `recoverytoken`, `recoveryexpiration`, `lastaccess`) VALUES 
		 * (NULL, '******', SHA1('*****'), NULL, NULL, NULL);
		 */
		if ($request->getMethod() == 'POST') {
			$formdata = $request->request->get('form');
			$em = $this->getDoctrine()->getEntityManager();
			$admin = $em->getRepository('PromoBundle:EntityUsuari')->findOneByMail($formdata['usuari']);
			if (!$admin) $this->get('session')->setFlash('sms-notice', '!Usuario incorrecto!');
			else {
				if ($admin->getPwd() != sha1($formdata['pwd'])) $this->get('session')->setFlash('sms-notice', '!Contraseña incorrecta!');
				else {
					$this->get('session')->set('usuari', $formdata['usuari']);
					$this->get('session')->set('pwd', sha1($formdata['pwd']));
					
					$this->get('session')->setFlash('sms-notice', '!Inicio de sesion correcto!');
					
					$admin = true;
					$response = $this->forward('PromoBundle:Productes:catalogo', array('categoria' => '0'));
					
					return $response;
				}
			}
		}
		
		return $this->render('PromoBundle:Admin:login.html.twig', array('form' => $form->createView(), 'admin' => $admin));
	}
	
	public function logoutAction()
	{
		$this->get('session')->clear();
	
		$this->get('session')->setFlash('sms-notice', '!Sessión finalizada!');
	
		$response = $this->forward('PromoBundle:Admin:login');
		
		return $response;
	}
	
	public function usuariAction()  {
		
		$request = $this->getRequest();
		
		$admin = $this->isCurrentAdmin();
		if ($admin == false) return $this->redirect($this->generateUrl('PromoBundle_catalogo'));

		$em = $this->getDoctrine()->getEntityManager();
		$admin = $em->getRepository('PromoBundle:EntityUsuari')->findOneByMail($this->get('session')->get('usuari'));
		
		$form = $this->createForm(new FormUsuari(), $admin);
		 
		if ($request->getMethod() === 'POST') {
			$form->bindRequest($request);
		
			if ($form->isValid()) {
				
			}
		}
				
		return $this->render('PromoBundle:Admin:usuari.html.twig', 
				array('form' => $form->createView(), 'admin' => $admin));
	}
	
    public function categoriaAction()
    {
    	$request = $this->getRequest();

    	$admin = $this->isCurrentAdmin();
    	if ($admin == false) return $this->redirect($this->generateUrl('PromoBundle_catalogo'));
    	 
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
	    					array('categoria' => ($categoria->getPare() != null)?$categoria->getPare()->getRuta():"", 'admin' => $admin)));
    			} else $this->get('session')->setFlash('sms-notice','Error durante la carga de la imagen');
    		} else $this->get('session')->setFlash('sms-notice','Error en la validación de los datos');
    	}
    	
    	return $this->render('PromoBundle:Admin:categoria.html.twig',
    			array('form' => $form->createView(), 'categoria' => $categoria, 'pare' => ($pare == null)?null:$pare, 'admin' => $admin));
    }
    
    public function productoAction()
    {
    	$request = $this->getRequest();
    
    	$admin = $this->isCurrentAdmin();
    	if ($admin == false) return $this->redirect($this->generateUrl('PromoBundle_catalogo'));
    	    	 
    	$producte = new EntityProducte();
    
    	$categoria = null;
    	if ($request->getMethod() == 'GET') {
    		if ($request->query->has('producto') and $request->query->get('producto') != 0) {
    			// Edició
    			$producte = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')->find($request->query->get('producto'));
    		} else {
    			// Nou
	    		if ($request->query->has('categoria') and $request->query->get('categoria') != 0) {
	    			$categoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('categoria'));
	    			$producte->setCategoria($categoria);
	    		}
    		}
    	} else  {
    		// Form submit
    		$p = $request->request->get('producte');
    	
    		if ($p['id'] != "") $producte = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')->find($p['id']);
    	}
    	
    	$form = $this->createForm(new FormProducte(), $producte);
    
    	if ($request->getMethod() === 'POST') {
    		$form->bindRequest($request);
    
			if ($form->isValid()) {
    			$uploadedfiles = $form['imatges']->getData();
    			 
    			$portada = null;
    			$uploadReturn = true;  
    			
    			// Sempre envia un, cal mirar si té nom
    			if (count($uploadedfiles) == 1 and $uploadedfiles[0] == null) {
    				array_shift($uploadedfiles);
    			}
    			
    			$em = $this->getDoctrine()->getEntityManager();
    			if ($uploadedfiles != null and count($uploadedfiles) > 0) {
    				if ($producte->getId() == null) {
    					// Nous productes la primera imatge és la portada
    					$portadaimg = array_shift($uploadedfiles);
    					
    					$portada = new EntityImatge($portadaimg);
    					$portada->setTitol("Producto " . $producte->getNom());
    					$em->persist($portada);
    					$producte->setImatgePortada($portada);
    					$uploadReturn = $portada->upload("p_".$producte->getNom());
    					if ($uploadReturn != true) $producte->setImatgePortada(null);
    				}
    				
    				foreach ($uploadedfiles as $key => $altreimg) {
    					$imatge = new EntityImatge($altreimg);
    					$imatge->setTitol("Producto " . $producte->getNom());
    					$em->persist($imatge);
    					$producte->addImatge($imatge);
    					
    					$upRet = $imatge->upload($key . "_" .$producte->getNom());
    					
    					$uploadReturn = $upRet and $uploadReturn;  // Keep previous value
    					
    					if ($upRet != true) $producte->removeImatge($imatge);    					
    				}
    			}
    		
    			if ($uploadReturn == true) {
    		
    				if ($producte->getId() == null) $em->persist($producte);  // Nou
    		
    				$em->flush();
    		
    				return $this->redirect($this->generateUrl('PromoBundle_catalogo',	array('categoria' => $producte->getCategoria()->getRuta(), 'admin' => $admin)));
    			} else $this->get('session')->setFlash('sms-notice','Error durante la carga de las imágenes');
    		} else $this->get('session')->setFlash('sms-notice','Error en la validación de los datos');
    		
    	}
    
    	$formView = $form->createView();
    	$formView->getChild('imatges')->set('full_name', 'producte[imatges][]'); // Canviar nom del input file/multiple per a que Symfony reconegui varis fitxers (vector)
    	
    	return $this->render('PromoBundle:Admin:producte.html.twig',
    			array('form' => $formView, 'producte' => $producte, 'admin' => $admin));
    }
    
    public function portadaAction()
    {
    	$request = $this->getRequest();
    	
    	$admin = $this->isCurrentAdmin();
    	 
    	$oldId = $request->query->get('oldId');
    	$newId = $request->query->get('newId');
    	$producteId = $request->query->get('producteId');
    	 
    	$producte = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')->find($producteId);
    	$oldImatge = $this->getDoctrine()->getRepository('PromoBundle:EntityImatge')->find($oldId);
    	$newImatge = $this->getDoctrine()->getRepository('PromoBundle:EntityImatge')->find($newId);
    	
    	if ($admin == true and $producte != null and $oldImatge != null and $newImatge != null) {
    		$producte->setImatgePortada($newImatge);
    		$producte->removeImatge($newImatge);
    		$producte->addImatge($oldImatge);
    		
    		$em = $this->getDoctrine()->getEntityManager();
    		
    		$em->flush();
    		
    		$response = new Response("ok");
    	} else {
    		$response = new Response("error", 400);
    	}
    	return $response;
    }
    
    public function  borrarimagenAction()
    {
    	$request = $this->getRequest();
    	
    	$admin = $this->isCurrentAdmin();
    	
    	$imatgeId = $request->query->get('imagen');
    	$producteId = $request->query->get('producto');
    	
    	$producte = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')->find($producteId);
    	$imatge = $this->getDoctrine()->getRepository('PromoBundle:EntityImatge')->find($imatgeId);

    	if ($admin == true and $producte != null and $imatge != null) {
    		$producte->removeImatge($imatge);
    		
    		$em = $this->getDoctrine()->getEntityManager();
    		
    		$em->flush();
    		
    		$response = new Response("ok");
    	} else {
    		$response = new Response("error", 400);
    	}
    	
    	return $response;
    }
    
    public function removecategoriaAction()
    {
    	$request = $this->getRequest();
    	
    	$admin = $this->isCurrentAdmin();
    	
    	if ($request->getMethod() == 'GET') {
    		if ($request->query->has('categoria') and $request->query->get('categoria') != 0) {
    			// Edició
    			$categoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('categoria'));
    		}
    	}
    	
    	if ($admin == true and $categoria != null and count($categoria->getFills()) == 0 and count($categoria->getProductes()) == 0) {
    		$em = $this->getDoctrine()->getEntityManager();
    		$em->remove($categoria); 
    		$em->flush();
    	} else {
    		$this->get('session')->setFlash('sms-notice','Esta categoría contiene productos y no se puede eliminar');
    	}
    	return $this->redirect($this->generateUrl('PromoBundle_catalogo',
    			array('categoria' => ($categoria->getPare() != null)?$categoria->getPare()->getRuta():"")));
    }
    
    public function removeproductoAction()
    {
    	$request = $this->getRequest();
    	 
    	$admin = $this->isCurrentAdmin();
    	 
    	if ($request->getMethod() == 'GET') {
    		if ($request->query->has('producto') and $request->query->get('producto') != 0) {
    			// Edició
    			$producte = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')->find($request->query->get('producto'));
    		}
    	}
    	 
    	if ($admin == true and $producte != null) {
    		$em = $this->getDoctrine()->getEntityManager();
    		$em->remove($producte->getImatgePortada());
    		foreach ($producte->getImatges() as $imatge) {
    			$em->remove($imatge);
    		}
    		$em->remove($producte);
    		$em->flush();
    	} else {
    		$this->get('session')->setFlash('sms-notice','Se ha producido un error borrando el producto');
    	}
    	return $this->redirect($this->generateUrl('PromoBundle_catalogo',
    				array('categoria' => ($producte->getCategoria()->getRuta()))));
    	 
    }
    
}
