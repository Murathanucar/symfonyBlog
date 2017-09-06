<?php
/**
 * Created by PhpStorm.
 * User: murathan
 * Date: 27.08.2017
 * Time: 17:01
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormFactory;


class NewActionService
{

    private $formFactory;
    private $em;
    private $request;

    /**
     * @var RedirectResponse
     */
    private $response;
    private $form;


    public function __construct(FormFactory $formFactory, EntityManager $em, RequestStack $request, RedirectResponse $response)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
        $this->response = $response;
    }

    public function actionService($type, $entity, $redirectUrl)
    {

        $this->form = $this->formFactory->create($type, $entity);
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            $this->response->setTargetUrl($redirectUrl);
            return $this->response;
        }
        return null;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }


}







