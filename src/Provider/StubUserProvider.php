<?php

declare(strict_types=1);

namespace App\Provider;

use App\Entity\User;
use App\Exception\Unauthorised;
use App\Repository\UserRepository;

class StubUserProvider implements UserProviderInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * @throws Unauthorised
     */
    public function getCurrentUser(): User
    {
        $user = $this->userRepository->getFirstUser();

        if ($user === null) {
            throw new Unauthorised();
        }

        return $user;
    }
}
