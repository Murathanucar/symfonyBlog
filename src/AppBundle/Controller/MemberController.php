<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Member;
use AppBundle\Form\MemberType;
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
 * Member controller.
 *
 * @Route("member")
 */
class MemberController extends Controller
{
    /**
     * Lists all member entities.
     *
     * @Route("/", name="member_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $members = $em->getRepository('AppBundle:Member')->findAll();

        return $this->render('member/index.html.twig', array(
            'members' => $members,
        ));
    }

    /**
     * Creates a new member entity.
     *
     * @Route("/new", name="member_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $member = new Member();
        /** @var NewActionService $newActionService */
        $newActionService = $this->get("newaction_service");
        $response = $newActionService->actionService(MemberType::class, $member, $this->generateUrl('member_index'));
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->render( 'member/new.html.twig', array(
            'member' => $member,
            'form' => $newActionService->getForm()->createView(),
        ));
    }

    /**
     * Finds and displays a member entity.
     *
     * @Route("/{id}", name="member_show")
     * @Method("GET")
     */
    public function showAction(Member $member)
    {
        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($member,'member_delete');

        return $this->render('member/show.html.twig', array(
            'member' => $member,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing member entity.
     *
     * @Route("/{id}/edit", name="member_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id = 0)
    {
        /** @var Member $member */
        $member = $this->getDoctrine()->getManager()->getReference("AppBundle:Member", $id);
        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($member,'member_delete');

        /** @var EditActionService $editActionService */
        $editActionService = $this->get("editaction_service");
        $response = $editActionService->editService(MemberType::class, $member, $this->generateUrl('member_edit', array('id' => $member->getId())));
        if($response instanceof RedirectResponse ){
            return $response;
        }


        return $this->render('member/edit.html.twig', array(
            'member' => $member,
            'edit_form' => $editActionService->getForm()->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a member entity.
     *
     * @Route("/{id}", name="member_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Member $member)
    {
        /** @var DeleteActionService $deleteActionService */
        $deleteActionService = $this->get("deleteaction_service");
        $response = $deleteActionService->deleteService($member,$this->generateUrl('member_index'),'member_delete');
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->redirectToRoute('member_index');
    }

}
