<?php
/**
 * Created by PhpStorm.
 * User: murathan
 * Date: 07.09.2017
 * Time: 09:42
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormFactory;


class EditActionService
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


    /**
     * @param $type
     * @param $entity
     * @param $redirectUrl
     * @param $array
     * @return null|RedirectResponse
     */
    public function editService($type, $entity, $redirectUrl)
    {
        $this->form = $this->formFactory->create($type, $entity);

        $this->form->handleRequest($this->request);

        if($this->form->isSubmitted() && $this->form->isValid()){
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