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
		
		$isadmin = $this->isCurrentAdmin();
		if ($isadmin == true) return $this->redirect($this->generateUrl('PromoBundle_homepage'));
		
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
			if (!$admin) $this->get('session')->setFlash('sms-notice', 'Usuario incorrecto');
			else {
				if ($admin->getPwd() != sha1($formdata['pwd'])) $this->get('session')->setFlash('sms-notice', '!Contraseña incorrecta!');
				else {
					$this->get('session')->set('usuari', $formdata['usuari']);
					$this->get('session')->set('pwd', sha1($formdata['pwd']));
					
					$this->get('session')->setFlash('sms-notice', 'Inicio de sesion correcto');
					
					$em = $this->getDoctrine()->getEntityManager();
					if ($admin->getRecoverytoken() != null) {
						// Esborrar token de recuperació de password, si entra amb login normal
						$admin->setRecoverytoken(null);
						$admin->setRecoveryexpiration(null);
					}
					$admin->setLastaccess( new \DateTime('now'));
					$em->flush();
					
					$response = $this->forward('PromoBundle:Productes:catalogo', array('categoria' => '0'));
					
					return $response;
				}
			}
		}
		
		return $this->render('PromoBundle:Admin:login.html.twig', array('form' => $form->createView(), 'admin' => $isadmin));
	}
	
	public function logoutAction()
	{
		$this->get('session')->clear();
	
		$this->get('session')->setFlash('sms-notice', 'Sessión finalizada');
		
		$response = $this->redirect($this->generateUrl('PromoBundle_login'));
		
		return $response;
	}
	
	public function usuariAction()  {
		
		$request = $this->getRequest();

		$em = $this->getDoctrine()->getEntityManager();
		
		$isadmin = $this->isCurrentAdmin();
		
		if ($this->isCurrentAdmin() == true) {
			// Canvi password normal 
			$admin = $em->getRepository('PromoBundle:EntityUsuari')->findOneByMail($this->get('session')->get('usuari'));
		} else {
			if ($request->getMethod() === 'GET') {
				// GET
				if (!$request->query->has('token') or !$request->query->has('mail')) // Sense token no admet GET  
								return $this->redirect($this->generateUrl('PromoBundle_login'));
			
				$adminmail = $request->query->get('mail'); 
				$token = $request->query->get('token');
					
				$this->get('session')->set('token', $token);
			} else {
				// POST
				if (!$this->get('session')->has('token')) // Sense token no admet POST
							return $this->redirect($this->generateUrl('PromoBundle_login'));
				
				$userdata = $request->request->get('usuari');
				$adminmail = $userdata['mail'];
				$token = $this->get('session')->get('token');
			}
		
			$admin = $em->getRepository('PromoBundle:EntityUsuari')->findOneByMail($adminmail);
			$now = new \DateTime('now');
			
			if (!$admin or $admin->getRecoverytoken() != sha1($token) or $now > $admin->getRecoveryexpiration() ) {
				$this->get('session')->setFlash('sms-notice', 'Enlace de recuperación de contraseña inválido o caducado');
				return $this->redirect($this->generateUrl('PromoBundle_login'));
			}
			$this->get('session')->setFlash('sms-notice', 'Proceso de recuperación de contraseña');
		}
		
		$form = $this->createForm(new FormUsuari(), $admin);
		
		if ($request->getMethod() === 'POST') {
			// Formulari upd password
			$form->bindRequest($request);
		
			if ($form->isValid()) {
				$password = $admin->getPwd();
				$admin->setPwd(sha1($password));
				$admin->setRecoverytoken(null);
				$admin->setRecoveryexpiration(null);
				
				if ($isadmin == false) {
					// Recuperació de contrasenya. Activar sessió
					$this->get('session')->set('usuari', $admin->getMail());
					$this->get('session')->set('pwd', $admin->getPwd());
					$isadmin = true;
				}
				
				$em->flush();
				
				$this->get('session')->setFlash('sms-notice', 'Contraseña modificada correctamente');
			}
		}
				
		return $this->render('PromoBundle:Admin:usuari.html.twig', 
				array('form' => $form->createView(), 'admin' => $isadmin));
	}
	
	public function pwdrecoverAction()  {
		$request = $this->getRequest();
		
		$usuari = "";
		if ($request->query->has('usuari')) {
			$em = $this->getDoctrine()->getEntityManager();
			
			$usuari = $request->query->get('usuari');
			
			$admin = $em->getRepository('PromoBundle:EntityUsuari')->findOneByMail($usuari);

			if ($admin) {
				$token = base64_encode(openssl_random_pseudo_bytes(30));
				
				$expiration = new \DateTime('now');
				$expiration->add(new \DateInterval('PT4H'));  // 4 hores
				
				$admin->setRecoverytoken(sha1($token));
				$admin->setRecoveryexpiration($expiration);
				
				$em->flush();
				
				$message = \Swift_Message::newInstance()
					->setSubject('[Mensaje Promoquality] - Solicitud de recuperación de contraseña')
					->setFrom(array('ondissenyweb@gmail.com'))
					->setTo(array('alexmazinho@gmail.com', $admin->getMail()));

				$logosrc = $message->embed(\Swift_Image::fromPath('images/logo_promoquality.png'));
				
				$body = $this->renderView('PromoBundle:Admin:pwdrecoverEmail.html.twig',
						array('mail' => $admin->getMail(), 'token' => $token, 'logo' => $logosrc));
				
				$message->setBody($body, 'text/html');
				
				$this->get('mailer')->send($message);
				
				return new response ("Se ha enviado un enlace al correo del usuario con las intrucciones para restaurar la contraseña");
			}
		}
		
		return new response ("No se ha encontrado el usuario " . $usuari);
	}
	
    public function categoriaAction()
    {
    	$request = $this->getRequest();

    	$isadmin = $this->isCurrentAdmin();
    	if ($isadmin == false) return $this->redirect($this->generateUrl('PromoBundle_catalogo'));
    	 
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
	    					array('categoria' => ($categoria->getPare() != null)?$categoria->getPare()->getRuta():"", 'admin' => $isadmin)));
    			} else $this->get('session')->setFlash('sms-notice','Error durante la carga de la imagen');
    		} else $this->get('session')->setFlash('sms-notice','Error en la validación de los datos');
    	}
    	
    	return $this->render('PromoBundle:Admin:categoria.html.twig',
    			array('form' => $form->createView(), 'categoria' => $categoria, 'pare' => ($pare == null)?null:$pare, 'admin' => $isadmin));
    }
    
    public function productoAction()
    {
    	$request = $this->getRequest();
    
    	$isadmin = $this->isCurrentAdmin();
    	if ($isadmin == false) return $this->redirect($this->generateUrl('PromoBundle_catalogo'));
    	    	 
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
    		
    				return $this->redirect($this->generateUrl('PromoBundle_catalogo',	array('categoria' => $producte->getCategoria()->getRuta(), 'admin' => $isadmin)));
    			} else $this->get('session')->setFlash('sms-notice','Error durante la carga de las imágenes');
    		} else $this->get('session')->setFlash('sms-notice','Error en la validación de los datos');
    		
    	}
    
    	$formView = $form->createView();
    	$formView->getChild('imatges')->set('full_name', 'producte[imatges][]'); // Canviar nom del input file/multiple per a que Symfony reconegui varis fitxers (vector)
    	
    	return $this->render('PromoBundle:Admin:producte.html.twig',
    			array('form' => $formView, 'producte' => $producte, 'admin' => $isadmin));
    }
    
    public function portadaAction()
    {
    	$request = $this->getRequest();
    	
    	$isadmin = $this->isCurrentAdmin();
    	 
    	$oldId = $request->query->get('oldId');
    	$newId = $request->query->get('newId');
    	$producteId = $request->query->get('producteId');
    	 
    	$producte = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')->find($producteId);
    	$oldImatge = $this->getDoctrine()->getRepository('PromoBundle:EntityImatge')->find($oldId);
    	$newImatge = $this->getDoctrine()->getRepository('PromoBundle:EntityImatge')->find($newId);
    	
    	if ($isadmin == true and $producte != null and $oldImatge != null and $newImatge != null) {
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
    	
    	$isadmin = $this->isCurrentAdmin();
    	
    	$imatgeId = $request->query->get('imagen');
    	$producteId = $request->query->get('producto');
    	
    	$producte = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')->find($producteId);
    	$imatge = $this->getDoctrine()->getRepository('PromoBundle:EntityImatge')->find($imatgeId);

    	if ($isadmin == true and $producte != null and $imatge != null) {
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
    	
    	$isadmin = $this->isCurrentAdmin();
    	
    	if ($request->getMethod() == 'GET') {
    		if ($request->query->has('categoria') and $request->query->get('categoria') != 0) {
    			// Edició
    			$categoria = $this->getDoctrine()->getRepository('PromoBundle:EntityCategoria')->find($request->query->get('categoria'));
    		}
    	}
    	
    	if ($isadmin == true and $categoria != null and count($categoria->getFills()) == 0 and count($categoria->getProductes()) == 0) {
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
    	 
    	$isadmin = $this->isCurrentAdmin();
    	 
    	if ($request->getMethod() == 'GET') {
    		if ($request->query->has('producto') and $request->query->get('producto') != 0) {
    			// Edició
    			$producte = $this->getDoctrine()->getRepository('PromoBundle:EntityProducte')->find($request->query->get('producto'));
    		}
    	}
    	 
    	if ($isadmin == true and $producte != null) {
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
