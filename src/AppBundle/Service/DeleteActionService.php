<?php
/**
 * Created by PhpStorm.
 * User: murathan
 * Date: 07.09.2017
 * Time: 09:42
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller;




class DeleteActionService
{

    private $em;
    private $request;

    /**
     * @var RedirectResponse
     */
    private $response;
    private $deleteForm;
    private $container;

    public function __construct(EntityManager $em, RequestStack $request, RedirectResponse $response, ContainerInterface $container)
    {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
        $this->response = $response;
        $this->container = $container;
    }

    /**
     * @param $entity
     * @return null|RedirectResponse
     * @internal param $redirectUrl
     */
    public function deleteService($entity,$RedirectUrl,$createDeleteFormRoute)
    {

        /** @var CreateDeleteForm $createDeleteForm */
        $createDeleteForm = $this->container->get("createdeleteform_service");
        $response1 = $createDeleteForm->createDeleteForm($entity,$createDeleteFormRoute);

        $response1->handleRequest($this->request);



        if($response1->isSubmitted() && $response1->isValid()){

            $this->em->remove($entity);
            $this->em->flush();

            $this->response->setTargetUrl($RedirectUrl);
            return $this->response;
        }
        return null;
    }

}