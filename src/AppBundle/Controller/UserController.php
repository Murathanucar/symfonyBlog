<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Service\newActionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\CreateDeleteForm;
use AppBundle\Service\DeleteActionService;
use AppBundle\Service\editActionService;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     * @return null|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $user = new User();
        /** @var NewActionService $newActionService */
        $newActionService = $this->get("newaction_service");
        $response = $newActionService->actionService(UserType::class, $user, $this->generateUrl('user_index'));
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->render( 'user/new.html.twig', array(
            'user' => $user,
            'form' => $newActionService->getForm()->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($user,'user_delete');
        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id = 0)
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getManager()->getReference("AppBundle:User", $id);
        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($user,'user_delete');

        /** @var EditActionService $editActionService */
        $editActionService = $this->get("editaction_service");
        $response = $editActionService->editService(UserType::class, $user, $this->generateUrl('user_edit', array('id' => $user->getId())));
        if($response instanceof RedirectResponse ){
            return $response;
        }


        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editActionService->getForm()->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     * @param User $user
     * @return null|RedirectResponse
     */
    public function deleteAction(User $user)
    {
        /** @var DeleteActionService $deleteActionService */
        $deleteActionService = $this->get("deleteaction_service");
        $response = $deleteActionService->deleteService($user,$this->generateUrl('user_index'),'user_delete');
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->redirectToRoute('user_index');
    }

}
