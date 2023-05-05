<?php

namespace App\Controller\Talk;

use App\Repository\TalkRepository;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SitemapController extends AbstractController
{
    #[Route('/talks/sitemap.xml', name: 'api_talks_sitemap')]
    public function __invoke(
        Request $request,
        TalkRepository $talkRepository,
        NormalizerInterface $serializer,
    ): Response
    {
        $talks = $talkRepository->getIterator();
        $content = '';
        $batchSize = 100;
        $processed = [];

        foreach ($talks as $talk) {
            $content .= '<url>' .
                '<loc>https://www.dev-conferences.com/talks/' . $talk->getId() . '/' . (new Slugify())->slugify($talk->getName()) . '</loc>' .
                '</url>';

            if ((\count($processed) % $batchSize) === 0) {
                $talkRepository->clearEM();
                $processed = [];
            }
            $processed[] = $talk->getId();
        }

        $talkRepository->clearEM();

        $response = new Response('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . $content . '</urlset>', 200);

        $response->headers->add(['Content-Type' => 'application/xml']);
        return $response;
    }
}
