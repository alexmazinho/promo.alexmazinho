<?php

namespace Promo\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;

class StaticController extends Controller
{
    public function indexAction()
    {
        return $this->render('PromoBundle:Static:index.html.twig');
    }
    public function empresaAction()
    {
    	return $this->render('PromoBundle:Static:empresa.html.twig');
    }
    public function consultoriaAction()
    {
    	return $this->render('PromoBundle:Static:consultoria.html.twig');
    }
    
}
