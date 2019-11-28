<?php

namespace AppBundle\Base\Service;

use AppBundle\Base\Entity\Army;

class ArmyService extends BaseAdminService
{
    /**
     * Create new army
     *
     * @param $gameId
     * @param $name
     * @param $status
     * @param $attackStrategy
     * @return mixed
     */
    public function createArmy($gameId, $name, $status, $attackStrategy)
    {
        $newArmy = new Army();
        $game = $this->getGameRepository()->find($gameId);
        $newArmy->setGame($game);
        $newArmy->setName($name);
        $newArmy->setUnits($status);
        $newArmy->setAttackStrategy($attackStrategy);

        $this->em->persist($newArmy);
        $this->em->flush();

        return $newArmy;
    }


    /**
     * Count number of armies in a game
     *
     * @param $game
     * @return mixed
     */
    public function countArmies($game)
    {
        $result = $this->getArmyRepository()
            ->createQueryBuilder('lu')
            ->select("u.id, count(lu.id)")
            ->join("lu.game", "u")
            ->where('lu.game = :game')
            ->setParameter('game', $game)
            ->groupBy('lu.game')
            ->getQuery()
            ->getResult();

        return intval($result[0][1]);
    }
}