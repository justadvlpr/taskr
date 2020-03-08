<?php

namespace App\Controller;

use App\Controller;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Web\User\User;

class AuthController extends Controller
{
    private LoggerInterface $logger;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Aliases $aliases,
        WebView $view,
        User $user,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;

        parent::__construct($responseFactory, $user, $aliases, $view);
    }

    protected function getId(): string
    {
        return 'auth';
    }

    public function login(
        ServerRequestInterface $request,
        IdentityRepositoryInterface $identityRepository
    ): ResponseInterface {
        $body = $request->getParsedBody();
        $error = null;

        try {
            foreach (['login', 'password'] as $name) {
                if (empty($body[$name])) {
                    throw new \InvalidArgumentException("{$name} is required.");
                }
            }

            /** @var \App\Entity\User $identity */
            $identity = $identityRepository->findByLogin($body['login']);
            if ($identity === null || !$identity->validatePassword($body['password'])) {
                throw new \InvalidArgumentException('Invalid user or password.');
            }

            if ($this->user->login($identity)) {
                return $this->renderJson(
                    [
                        'success' => true,
                        'user' => [
                            'id' => $identity->getId(),
                            'token' => $identity->getToken(),
                            'login' => $identity->getLogin(),
                        ],
                    ],
                    200
                );
            }

            throw new \InvalidArgumentException('Unable to login');
        } catch (\Throwable $e) {
            $this->logger->error($e);
            $error = $e->getMessage();

            return $this->renderJson(
                [
                    'success' => false,
                    'error' => $error,
                ],
                422
            );
        }
    }

    public function verify(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \App\Entity\User $authUser */
        $authUser = $request->getAttribute('auth_user');

        return $this->renderJson(
            [
                'success' => true,
                'user' => [
                    'id' => $authUser->getId(),
                    'login' => $authUser->getLogin(),
                ],
            ],
            200
        );
    }
}
