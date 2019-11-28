<?php

namespace AppBundle\Website\Controller;

use AppBundle\Base\Controller\BaseController;
use AppBundle\Base\Entity\Army;
use AppBundle\Base\Entity\Game;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Blank;

class ArmyController extends BaseController
{
    /**
     * @Route("/army/create/{gameId}", name="army_create")
     * @param $gameId
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function createArmyAction(Request $request, $gameId)
    {
        $game = $this->getDoctrine()->getRepository('AppBundle:Game')->find($gameId);
        $armies = $this->getDoctrine()
            ->getRepository('AppBundle:Army')->findBy(['game' => $game]);

        $form = $this->createFormBuilder()
            ->add('game', TextType::class, array(
                'disabled'=> true,
                'data' => $game->getName(),
                'label' => 'Game',
                'attr' => ['class' => 'form-control']
            ))
            ->add('name', TextType::class, array(
                'label' => 'Name',
                'attr' => ['class' => 'form-control']
            ))
            ->add('units', IntegerType::class, array(
                'required' => true,
                'label' => 'Units',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Range(
                        array('min'=> 80, 'minMessage'=>'There must be at least 80 units!',
                              'max'=> 100, 'maxMessage'=>'There can be maximum 100 units!')
                    )
                ]
            ))
            ->add('attackStrategy', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                    'Random' => Army::ATTACK_STRATEGY_RANDOM,
                    'Weakest' => Army::ATTACK_STRATEGY_WEAKEST,
                    'Strongest' => Army::ATTACK_STRATEGY_STRONGEST
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Add',
                'attr' => ['class' => 'btn btn-primary pull-right action-save']
            ))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $name = $data['name'];
            $units = $data['units'];
            $attackStrategy = $data['attackStrategy'];

            $this->container->get('army')->createArmy($game, $name, $units, $attackStrategy);

            $this->addFlash('info', 'Army was successfully <a href="/" class="alert-link">added</a>!');

            return $this->redirectToRoute('game_show', ['id' => $gameId]);
        }
        return $this->render('@FrontTemplates/pages/army-create.html.twig', array(
            'form' => $form->createView(),
            'game' => $game,
            'armies' => $armies
        ));
    }

    /**
     * @Route("/army/delete/{id}", name="army_delete")
     * @param $id
     * @param Army $armies
     * @return Response
     */
    public function armyDeleteAction($id, Army $armies)
    {
        $army = $this->getDoctrine()->getRepository('AppBundle:Army')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($army);
        $em->flush();

        $this->addFlash('danger', 'Army was successfully <a href="/" class="alert-link">deleted</a>!');

        return $this->render('@FrontTemplates/pages/game-show.html.twig', array(
            'game' => $army->getGame(),
            'armies' => $armies
        ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Army::class,
        ]);
    }

}
