<?php

namespace AppBundle\Website\Controller;

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
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function runAttackAction(Request $request, $gameId)
    {
        $game = $this->getDoctrine()->getRepository('AppBundle:Game')->find($gameId);
        $loadArmies = $this->container->get('army')->loadArmies($game);

        $attackWeakest = $this->container->get('attack.strategy')->attackWeakestArmy($game);
        $attackStrongest = $this->container->get('attack.strategy')->attackStrongestArmy($game);
        $attackRandom = $this->container->get('attack.strategy')->attackRandomArmy($game);
        $findDefeated = $this->container->get('attack.strategy')->findDefeated($game);

        return $this->render('@FrontTemplates/pages/run-attack.html.twig', array(
            'game' => $game,
            'loadArmies' => $loadArmies,
            'attackWeakest' => $attackWeakest,
            'attackStrongest' => $attackStrongest,
            'attackRandom' => $attackRandom,
            'findDefeated' => $findDefeated
        ));
    }
}
