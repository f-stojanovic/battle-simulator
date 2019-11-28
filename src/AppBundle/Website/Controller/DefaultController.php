<?php

namespace AppBundle\Website\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $games = $this->get('games')->getGamesList();

        // Paginate the results of the query
        $list = $this->get('knp_paginator')->paginate(
            $games,
            $request->query->get('page', 1),
            5
        );

        return $this->render('@FrontTemplates/pages/dashboard.html.twig', array(
            'games' => $list
        ));
    }
}
