<?php
/**
 * Created by PhpStorm.
 * User: marija
 * Date: 15.1.18.
 * Time: 14.48
 */

namespace AppBundle\Base\Service;

use AppBundle\Base\Entity\Game;
use AppBundle\Base\Entity\Army;
use AppBundle\Base\Entity\Hall;
use AppBundle\Base\Entity\HallSeat;
use AppBundle\Base\Entity\HallTicket;
use AppBundle\Base\Entity\LoyaltyCard;
use AppBundle\Base\Entity\Play;
use AppBundle\Base\Entity\PlayTicket;
use AppBundle\Base\Entity\PurchasedTicket;
use AppBundle\Base\Entity\Seat;
use AppBundle\Base\Entity\SeatMap;
use AppBundle\Base\Entity\Theater;
use AppBundle\Base\Entity\Ticket;
use AppBundle\Base\Entity\TicketType;
use AppBundle\Base\Entity\User;
use AppBundle\Base\Entity\UserSession;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Doctrine\ORM\EntityManagerInterface;

class BaseAdminService
{
    var $container;
    var $em;

    protected $uniqid;
    protected $data = [];

    /**
     * Constructor
     * @param ContainerInterface $container
     * @param EntityManagerInterface $em
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * Extract request params
     *
     * @param Request $request
     * @param array $mandatoryParams
     * @return array|mixed
     * @throws MissingApiParamException
     */
    public function extractParams(Request $request, $mandatoryParams = array())
    {
        $params = $this->extractParamsFromJSONBody($request);

        // Try with standard post if n content is provided
        if(empty($params)) {
            $params = $request->request->all();
        }

        $formattedParams = array();
        foreach($params as $key => $value) {
            $formattedParams[Inflector::tableize($key)] = $value;
        }

        $files = $request->files->all();

        if(!empty($files)) {
            $formattedParams = array_merge($formattedParams, $files);
        }

        foreach($mandatoryParams as $mandatoryParam) {
            $mandatoryParam = Inflector::tableize($mandatoryParam);
            if(!array_key_exists($mandatoryParam, $formattedParams)) {
                throw new MissingApiParamException(sprintf('Param "%s" is missing', $mandatoryParam));
            }
        }

        return $formattedParams;
    }

    /**
     * Extract params from JSON body
     *
     * @param Request $request
     * @return array|mixed
     */
    private function extractParamsFromJSONBody(Request $request)
    {
        $params = array();

        $content = $request->getContent();
        if(!empty($content)) {
            $result = json_decode($content);

            if (json_last_error() === JSON_ERROR_NONE) {
                $params = $result;
            }
        }

        return $params;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    public function getGameRepository()
    {
        return $this->em->getRepository(Game::class);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    public function getArmyRepository()
    {
        return $this->em->getRepository(Army::class);
    }
}

class MissingApiParamException extends \Exception {}
