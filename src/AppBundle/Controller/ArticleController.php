<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use AppBundle\Service\editActionService;
use AppBundle\Service\newActionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Article controller.
 *
 * @Route("article")
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/", name="article_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->render('article/index.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/new", name="article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction()
    {
        $article = new Article();
        /** @var NewActionService $newActionService */
        $newActionService = $this->get("newaction_service");
        $response = $newActionService->actionService(ArticleType::class, $article, $this->generateUrl('article_index'));
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->render( 'article/new.html.twig', array(
            'article' => $article,
            'form' => $newActionService->getForm()->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/{id}", name="article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($article,'article_delete');

        return $this->render('article/show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/edit", name="article_edit",defaults={"page"=1})
     * @Method({"GET", "POST"})
     */
    public function editAction($id = 0)
    {
        /** @var Article $article */
        $article = $this->getDoctrine()->getManager()->getReference("AppBundle:Article", $id);
        $createDeleteForm = $this->get("createdeleteform_service");
        $deleteForm = $createDeleteForm->createDeleteForm($article,'article_delete');

        /** @var EditActionService $editActionService */
        $editActionService = $this->get("editaction_service");
        $response = $editActionService->editService(ArticleType::class, $article, $this->generateUrl('article_edit', array('id' => $article->getId())));
        if($response instanceof RedirectResponse ){
            return $response;
        }


        return $this->render('article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editActionService->getForm()->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Article $article)
    {

        $deleteActionService = $this->get("deleteaction_service");
        $response = $deleteActionService->deleteService($article,$this->generateUrl('article_index'),'article_delete');
        if($response instanceof RedirectResponse ){
            return $response;
        }
        return $this->redirectToRoute('article_index');
    }

}
