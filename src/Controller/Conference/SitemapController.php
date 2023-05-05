<?php

namespace App\Controller\Conference;

use App\Repository\ConferenceRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SitemapController extends AbstractController
{
    #[Route('/conferences/sitemap.xml', name: 'api_conference_sitemap')]
    public function __invoke(
        Request $request,
        ConferenceRepository $conferenceRepository,
        NormalizerInterface $serializer,
    ): Response
    {
        $conferences = $conferenceRepository->findAll();
        $content = '';
        foreach ($conferences as $conference) {
            $content .= '<url>' .
                '<loc>https://www.dev-conferences.com/conferences/' . $conference->getId() . '/' . (new Slugify())->slugify($conference->getName()) . '</loc>' .
                '</url>';
        }
        $response = new Response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . $content . '</urlset>', 200);

        $response->headers->add(['Content-Type' => 'application/xml']);
        return $response;
    }
}
