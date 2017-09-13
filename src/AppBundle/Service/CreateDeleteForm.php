<?php
/**
 * Created by PhpStorm.
 * User: murathan
 * Date: 12.09.2017
 * Time: 17:15
 */

namespace AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing;
use AppBundle\Entity;
use Symfony\Component\HttpFoundation\RedirectResponse;






class CreateDeleteForm
{
    private $generateUrl;
    private $createFormBuilder;
    private $redirectResponse;

    /**
     * CreateDeleteForm constructor.
     * @param Routing\Router $generateUrl
     * @param FormFactoryInterface $createFormBuilder
     * @param RedirectResponse $redirectResponse
     * @internal param Controller\ControllerTrait $createForm
     */
    public function __construct(Routing\Router $generateUrl, FormFactoryInterface $createFormBuilder, RedirectResponse $redirectResponse)
    {
        $this->generateUrl = $generateUrl;
        $this->createFormBuilder = $createFormBuilder;
        $this->redirectResponse = $redirectResponse;
    }

    public function createDeleteForm($entity,$createDeleteFormRoute)
    {
        return $this->createFormBuilder->createBuilder()
        ->setAction($this->generateUrl->generate($createDeleteFormRoute, array('id' => $entity->getId())))
        ->setMethod('DELETE')
        ->getForm();
    }
}
