<?php

namespace AppBundle\Website\Controller;

use AppBundle\Base\Controller\BaseController;
use AppBundle\Base\Entity\Game;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class GameController extends BaseController
{
    /**
     * @Route("/game/create", name="game_create")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function createGameAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array(
                'label' => 'Name',
                'attr' => ['class' => 'form-control']
            ))
            ->add('status', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                    'active' => Game::STATUS_ACTIVE,
                    'inactive' => Game::STATUS_INACTIVE
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
            $status = $data['status'];

            $this->container->get('games')->createGame($name, $status);

            $this->addFlash('info', 'Game was successfully <a href="/" class="alert-link">added</a>!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@FrontTemplates/pages/game-create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/game/edit/{id}", name="game_edit")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function gameEditAction(Request $request, $id)
    {
        $edit = $this->getDoctrine()->getRepository(Game::class)->find($id);

        $form = $this->createFormBuilder($edit)
            ->add('name', TextType::class, array(
                'label' => 'Name',
                'attr' => ['class' => 'form-control']
            ))
            ->add('status', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => false,
                'choices' => array(
                    'active' => Game::STATUS_ACTIVE,
                    'inactive' => Game::STATUS_INACTIVE
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => ['class' => 'btn btn-primary pull-right action-save']
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('warning', 'Game was successfully <a href="/" class="alert-link">edited</a>!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('@FrontTemplates/pages/game-edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/game/delete/{id}", name="game_delete")
     * @param Game $id
     * @return Response
     */
    public function gameDeleteAction(Game $id)
    {

        if ($id === null) {
            return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        $this->addFlash('danger', 'Game was successfully <a href="/" class="alert-link">deleted</a>!');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/game/show/{id}", name="game_show")
     * @param Game $id
     * @return Response
     */
    public function gameShowAction(Game $id)
    {
        $game = $this->getDoctrine()
            ->getRepository('AppBundle:Game')
            ->find($id);

        $armies = $this->getDoctrine()
            ->getRepository('AppBundle:Army')->findBy(['game' => $game]);

        $countArmies = $this->container->get('army')->countArmies($game);

        return $this->render('@FrontTemplates/pages/game-show.html.twig', array(
            'game' => $game,
            'armies' => $armies,
            'countArmies' => $countArmies
        ));
    }
}
