<?php

namespace App\Controller;

use App\Entity\MyTeam\DelegatedAccess;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

trait AccessDelegatorTrait
{
    protected function getMainUser(Request $request): UserInterface
    {
        $this->disableInProduction();

        return $this->getDelegatedAccess($request)->getDelegator();
    }

    protected function getDelegatedAccess(Request $request): DelegatedAccess
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new \LogicException('User is not logged in');
        }

        $delegatedAccess = $currentUser->getReceivedDelegatedAccessByUuid($request->attributes->get(DelegatedAccess::ATTRIBUTE_KEY));

        if (null === $delegatedAccess) {
            throw new \LogicException('Current user does not have a delegated access.');
        }

        return $delegatedAccess;
    }
}
