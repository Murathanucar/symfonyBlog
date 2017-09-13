<?php

namespace AppBundle\Controller;

use AppBundle\Service\newActionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Service\CreateDeleteForm;
use AppBundle\Service\DeleteActionService;
use AppBundle\Service\editActionService;

/**
 * Comment controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all comment entities.
     *
     * @Route("/", name="comment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comment')->findAll();

        return $this->render('comment/index.html.twig', array(
            'comments' => $comments,
        ));
    }

    /**
     * Creates a new comment entity.
     *
     * @Route("/new", name="comment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction()
    {
        $comment = new Comment();
        /** @var NewActionService $newActionService */
        $newActionService = $this->get("newaction_service");
        $response = $newActionService->actionService(CommentType::class, $comment, $this->generateUrl('comment_index'));
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->render('comment/new.html.twig', array(
            'comment' => $comment,
            'form' => $newActionService->getForm()->createView(),
        ));
    }

    /**
     * Finds and displays a comment entity.
     *
     * @Route("/{id}", name="comment_show")
     * @Method("GET")
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Comment $comment)
    {
        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($comment, 'comment_delete');

        return $this->render('comment/show.html.twig', array(
            'comment' => $comment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing comment entity.
     *
     * @Route("/{id}/edit", name="comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id = 0)
    {
        /** @var Comment $comment */
        $comment = $this->getDoctrine()->getManager()->getReference("AppBundle:Comment", $id);
        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($comment, 'comment_delete');

        /** @var EditActionService $editActionService */
        $editActionService = $this->get("editaction_service");
        $response = $editActionService->editService(CommentType::class, $comment, $this->generateUrl('comment_edit', array('id' => $comment->getId())));
        if ($response instanceof RedirectResponse) {
            return $response;
        }


        return $this->render('comment/edit.html.twig', array(
            'comment' => $comment,
            'edit_form' => $editActionService->getForm()->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a comment entity.
     *
     * @Route("/{id}", name="comment_delete")
     * @Method("DELETE")
     * @param Comment $comment
     * @return null|RedirectResponse
     */
    public function deleteAction(Comment $comment)
    {
        /** @var DeleteActionService $deleteActionService */
        $deleteActionService = $this->get("deleteaction_service");
        $response = $deleteActionService->deleteService($comment, $this->generateUrl('comment_index'), 'comment_delete');
        if ($response instanceof RedirectResponse) {
            return $response;
        }
        return $this->redirectToRoute('comment_index');
    }

}
