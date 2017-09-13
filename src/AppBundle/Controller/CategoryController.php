<?php

namespace AppBundle\Controller;

use AppBundle\Service\newActionService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\CreateDeleteForm;
use AppBundle\Service\DeleteActionService;
use AppBundle\Service\editActionService;



/**
 * Category controller.
 *
 * @Route("category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/new", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        /** @var NewActionService $newActionService */
        $newActionService = $this->get("newaction_service");
        $response = $newActionService->actionService(CategoryType::class, $category, $this->generateUrl('category_index'));
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->render( 'category/new.html.twig', array(
            'category' => $category,
            'form' => $newActionService->getForm()->createView(),
        ));
    }

    /**
     * Finds and displays a category entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {
        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($category,'category_delete');

        return $this->render('category/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id = 0)
    {
        /** @var Category $category */
        $category = $this->getDoctrine()->getManager()->getReference("AppBundle:Category", $id);
        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($category,'category_delete');

        /** @var EditActionService $editActionService */
        $editActionService = $this->get("editaction_service");
        $response = $editActionService->editService(CategoryType::class, $category, $this->generateUrl('category_edit', array('id' => $category->getId())));
        if($response instanceof RedirectResponse ){
            return $response;
        }


        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editActionService->getForm()->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     * @Route("/{id}", name="category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Category $category)
    {
        /** @var DeleteActionService $deleteActionService */
        $deleteActionService = $this->get("deleteaction_service");
        $response = $deleteActionService->deleteService($category,$this->generateUrl('category_index'),'category_delete');
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->redirectToRoute('category_index');
    }


}
