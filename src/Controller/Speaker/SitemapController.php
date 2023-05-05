<?php

namespace App\Controller\Speaker;

use App\Repository\SpeakerRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SitemapController extends AbstractController
{
    #[Route('/speakers/sitemap.xml', name: 'api_speaker_sitemap')]
    public function __invoke(
        Request $request,
        SpeakerRepository $speakerRepository,
        NormalizerInterface $serializer,
    ): Response
    {
        $speakers = $speakerRepository->findAll();
        $content = '';
        foreach ($speakers as $speaker) {
            $content .= '<url>' .
                '<loc>https://www.dev-conferences.com/speakers/' . $speaker->getId() . '/' . (new Slugify())->slugify($speaker->getFirstName() . ' ' . $speaker->getLastName()) . '</loc>' .
                '</url>';
        }
        $response = new Response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . $content . '</urlset>', 200);

        $response->headers->add(['Content-Type' => 'application/xml']);
        return $response;
    }
}
