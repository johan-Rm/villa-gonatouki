<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\Filesystem\Filesystem;


/**
 * @Route("/target")
 */
class TargetController extends AbstractController
{
    /**
     * @Route("/get-article", name="target-article")
     * @Method({ "GET" })
     */
    public function getArticle(Request $request): JsonResponse
    {
        // $target = 'https://www.e-spirit.com/en/blog/headless_cms_everything_you_wanted_to_know.html';
        $target = $request->query->get('target');
        // $node = 'article.blog .content';
        $node = $request->query->get('content');

        $this->generateMarkdownFromUrlTarget($target, $node);

        return new JsonResponse(
            'ok',
            JsonResponse::HTTP_OK
        );
    }

    private function generateMarkdownFromUrlTarget($target, $node)
    {
        $client = HttpClient::create(["verify_peer" => false, "verify_host" => false]);
        $response = $client->request('GET', $target, []);
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $html = $response->getContent();

        $crawler = new Crawler($html);
        $content = $crawler->filter($node)->html();
        // $content = "<h3>Quick, to the Batpoles!</h3>";
        $converter = new HtmlConverter();
        // $converter->getConfig()->setOption('strip_tags', true);
        $markdown = $converter->convert($content);

        $filesystem = new Filesystem();
        $path = $this->getParameter('view.content.path');
        
        if(!$filesystem->exists($path)) {
            $filesystem->mkdir($path);
        }
        // dump($path);die;
        $filepath = $path . DIRECTORY_SEPARATOR . 'test_1.md';
        // dump($filepath);die;
        $filesystem->dumpFile($filepath, $markdown);
        dump($markdown);die();
    }
}