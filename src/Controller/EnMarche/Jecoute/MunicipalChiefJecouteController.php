<?php

namespace App\Controller\EnMarche\Jecoute;

use App\Entity\Jecoute\LocalSurvey;
use App\Jecoute\JecouteSpaceEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espace-municipales-2020/jecoute", name="app_jecoute_municipal_chief_")
 *
 * @Security("is_granted('ROLE_MUNICIPAL_CHIEF') and user.municipalChiefManagedArea.hasJecouteAccess()")
 */
class MunicipalChiefJecouteController extends AbstractJecouteController
{
    protected function getSpaceName(): string
    {
        return JecouteSpaceEnum::MUNICIPAL_CHIEF_SPACE;
    }

    protected function getLocalSurveys(Request $request): array
    {
        return $this->localSurveyRepository->findAllByAuthor($this->getUser());
    }

    protected function createSurveyForm(LocalSurvey $localSurvey): FormInterface
    {
        if (!$localSurvey->getCity()) {
            $localSurvey->setCity($this->getUser()->getMunicipalChiefManagedArea()->getCityName());
        }

        return parent::createSurveyForm($localSurvey)
            ->remove('concernedAreaChoice')
            ->remove('city')
        ;
    }

    protected function getSurveyTags(Request $request): array
    {
        return (array) $this->getUser()->getMunicipalChiefManagedArea()->getDepartmentalCode();
    }
}
