<?php

namespace App\Controller\ConferenceEdition;

use App\Repository\ConferenceEditionRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SitemapController extends AbstractController
{
    #[Route('/conferences/editions/sitemap.xml', name: 'api_conference_edition_sitemap')]
    public function __invoke(
        Request $request,
        ConferenceEditionRepository $conferenceEditionRepository,
        NormalizerInterface $serializer,
    ): Response
    {
        $conferenceEditions = $conferenceEditionRepository->findAll();
        $content = '';
        foreach ($conferenceEditions as $conferenceEdition) {
            $content .= '<url>' .
                '<loc>https://www.dev-conferences.com/editions/' . $conferenceEdition->getId() . '/' . (new Slugify())->slugify($conferenceEdition->getName()) . '</loc>' .
                '</url>';
        }
        $response = new Response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . $content . '</urlset>', 200);

        $response->headers->add(['Content-Type' => 'application/xml']);
        return $response;
    }
}
