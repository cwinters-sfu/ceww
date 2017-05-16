<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\DateYear;
use AppBundle\Form\DateYearType;

/**
 * DateYear controller.
 *
 * @Route("/date_year")
 */
class DateYearController extends Controller
{
    /**
     * Lists all DateYear entities.
     *
     * @Route("/", name="date_year_index")
     * @Method("GET")
     * @Template()
	 * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT e FROM AppBundle:DateYear e ORDER BY e.id';
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $dateYears = $paginator->paginate($query, $request->query->getint('page', 1), 25);

        return array(
            'dateYears' => $dateYears,
        );
    }

    /**
     * Finds and displays a DateYear entity.
     *
     * @Route("/{id}", name="date_year_show")
     * @Method("GET")
     * @Template()
	 * @param DateYear $dateYear
     */
    public function showAction(DateYear $dateYear)
    {

        return array(
            'dateYear' => $dateYear,
        );
    }

}
