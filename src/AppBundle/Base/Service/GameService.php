<?php

namespace AppBundle\Base\Service;

use AppBundle\Base\Entity\Game;

class GameService extends BaseAdminService
{
    /**
     * Get list of games
     */
    public function getGamesList()
    {
        return $this->getGameRepository()
            ->createQueryBuilder('g')
            ->select('g')
            ->getQuery();
    }

    /**
     * Create new game
     *
     * @param $name
     * @param $status
     * @return mixed
     * @return array
     * @throws \Exception
     */
    public function createGame($name, $status)
    {
        $newGame = new Game();
        $newGame->setName($name);
        $newGame->setStatus($status);

        $this->em->persist($newGame);
        $this->em->flush();

        return $newGame;
    }
}