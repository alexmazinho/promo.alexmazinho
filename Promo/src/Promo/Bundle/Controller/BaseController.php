<?php

namespace Promo\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Promo\Bundle\Entity\EntityUsuari;

class BaseController extends Controller
{
	protected function isCurrentAdmin() {
		if (!$this->get('session')->has('usuari') or !$this->get('session')->has('pwd')) return false;
		
		$em = $this->getDoctrine()->getEntityManager();
		$admin = $em->getRepository('PromoBundle:EntityUsuari')->findOneByMail($this->get('session')->get('usuari'));
		
		if (!$admin || $admin->getPwd() != $this->get('session')->get('pwd')) return false;
		return true;
	}
    
}
