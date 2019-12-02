<?php

namespace AppBundle\Base\Service;

use AppBundle\Base\Entity\Army;
use AppBundle\Base\Entity\Game;

class AttackStrategyService extends BaseAdminService
{
    /**
     * Attack the army with the lowest number of units
     *
     * @param $gameId
     * @return mixed
     */
    public function attackWeakestArmy($gameId)
    {
        $army = $this->getArmyRepository()->findOneBy([
            'game' => $gameId,
            'attackStrategy'=> Army::ATTACK_STRATEGY_WEAKEST
        ]);


        $attack = $this->getArmyRepository()
            ->createQueryBuilder('a')
            ->select('a, MIN(a.units) AS min_units')
            ->where('a.game = :game')
            ->setParameter('game', $gameId)
            ->groupBy('a')
            ->orderBy('min_units', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        $attackUnit = $this->getArmyRepository()->findOneBy([
            'units' => intval($attack[0]['min_units'])
        ]);
        $unitKilled = $attackUnit->getUnits() -1;

        $attackUnit->setUnits($unitKilled);
        $this->em->flush();

        return array($army, $attack[0][0]);
    }

    /**
     * Attack the army with the highest number of units
     *
     * @param $gameId
     * @return mixed
     */
    public function attackStrongestArmy($gameId)
    {
        $army = $this->getArmyRepository()->findOneBy([
            'game' => $gameId,
            'attackStrategy'=> Army::ATTACK_STRATEGY_STRONGEST
        ]);

        $attack = $this->getArmyRepository()
            ->createQueryBuilder('a')
            ->select('a, MAX(a.units) AS max_units')
            ->where('a.game = :game')
            ->setParameter('game', $gameId)
            ->groupBy('a')
            ->orderBy('max_units', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        $attackUnit = $this->getArmyRepository()->findOneBy([
            'units' => intval($attack[0]['max_units'])
        ]);

        $unitKilled = $attackUnit->getUnits() -1;

        $attackUnit->setUnits($unitKilled);
        $this->em->flush();

        return array($army, $attack[0][0]);
    }

    /**
     * @param $gameId
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function attackRandomArmy($gameId)
    {
        $army = $this->getArmyRepository()->findOneBy([
            'game' => $gameId,
            'attackStrategy'=> Army::ATTACK_STRATEGY_RANDOM
        ]);

        $count = $this->getArmyRepository()
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.game = :game')
            ->setParameter('game', $gameId)
            ->getQuery()
            ->getSingleScalarResult();

        $attack = $this->getArmyRepository()
            ->createQueryBuilder('u')
            ->setFirstResult(rand(0, $count - 1))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        $attackUnit = $this->getArmyRepository()->findOneBy([
            'id' => $attack->getId()
        ]);
        $unitKilled = $attackUnit->getUnits() -1;

        $attackUnit->setUnits($unitKilled);
        $this->em->flush();

        return array($army, $attack);
    }

    /**
     * @param $gameId
     * @return object|null
     */
    public function findDefeated($gameId)
    {
        $findDefeated = $this->getArmyRepository()->findOneBy([
            'game' => $gameId,
            'units' => 0
        ]);

        if ($findDefeated) {
            return $findDefeated;
        }
    }
}