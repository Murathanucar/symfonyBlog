<?php
namespace AppBundle\Security;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
class CustomLoginAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;
    public function __construct(EntityManager $entityManager, RouterInterface $router)
    {
        $this->em = $entityManager;
        $this->router = $router;
    }
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login' || !$request->isMethod('POST')) {
            return;
        }
        return [
            "username" => $request->request->get("username"),
            "password" => $request->request->get("password"),
            "terms" => $request->request->get("terms"),
        ];
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials["username"]);
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        if($user->getPassword() != $credentials["password"]){
            throw new CustomUserMessageAuthenticationException(
                "Password is invalid"
            );
        }
        return true;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $url = $this->router->generate("login");
        var_dump($exception->getMessage());exit;
        //return new RedirectResponse($url);
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $url = $this->router->generate('article_index');
        return new RedirectResponse($url);
    }
    public function supportsRememberMe()
    {
        return true;
    }
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->router->generate("login");
        return new RedirectResponse($url);
    }
}