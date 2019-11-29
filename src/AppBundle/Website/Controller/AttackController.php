<?php

namespace AppBundle\Website\Controller;

use AppBundle\Base\Entity\Game;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AttackController extends Controller
{
    /**
     * @Route("/run-attack/{gameId}", name="run_attack")
     * @param Request $request
     * @param $gameId
     * @return Response
     */
    public function runAttackAction(Request $request, $gameId)
    {
        $game = $this->getDoctrine()->getRepository('AppBundle:Game')->find($gameId);
        $loadArmies = $this->container->get('army')->loadArmies($game);

        return $this->render('@FrontTemplates/pages/run-attack.html.twig', array(
            'game' => $game,
            'loadArmies' => $loadArmies,
        ));
    }
}
