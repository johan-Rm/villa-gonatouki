<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\MediaObject;
use App\Entity\Tag;
use App\Entity\WebPage;
use App\Service\Export\ArrayToCsv;
use App\Service\Export\CsvToArray;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class WebPageFixtures extends AbstractFixtures implements FixtureGroupInterface
{
    /**
    * @var CsvToArray
    */
    private $csvToArray;

    /**
    * @var ArrayToCsv
    */
    private $arrayToCsv;

    /**
    * @var array
    */
    private $references = [];

    public function __construct(CsvToArray $csvToArray, ArrayToCsv $arrayToCsv)
    {
        $this->csvToArray = $csvToArray;
        $this->arrayToCsv = $arrayToCsv;
    }

    public static function getGroups(): array
    {
        return ['HotelFixtures'];
    }

    public function load(ObjectManager $manager)
    {

        $this->initialize($manager);
        $start = date('Y-m-d\ H:i:s.u');

        $this->consoleOutput->writeln(
            sprintf('<fire>Start load WebPageFixtures : %s</fire>', $start)
        );

        $this->loadJsonArticles($manager);
        $this->loadJsonPages($manager);

        $this->consoleOutput->writeln('');
    }

    private function loadFakeArticles($manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 3; ++$i) {
            $tag = new Tag();
            $tag->setName($faker->name);
            $manager->persist($tag);
            $article = new Article();
            $article->setCategory($tag);
            $article->setHeadline($faker->sentence);
            $article->setAlternativeHeadline($faker->sentence);
            $article->setPushForward($faker->sentence);
            // $articleAgence->setPrimaryImage($image1);
            // $articleAgence->setGallery($galleryHorizontal);
            $article->setArticleBody($faker->text);
            $article->setArticleResume($faker->text);
            $manager->persist($article);
        }
        $manager->flush();
        $this->consoleOutput->writeln('<comment>loadArticles</comment>');
    }

    private function loadJsonArticles($manager)
    {
        $medias = $this->toolsMediaService->getMediaArray($manager);
        $imageNotfound = $manager->getRepository(MediaObject::class)->findOneBy(
            ['slug' => 'exterieur-jardin-img-38561-jpg']
        );

        $slugify = new Slugify();
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/articles.json';
        $articles = file_get_contents($pathJsonFile);
        $articles = json_decode($articles, true);
        foreach ($articles as $value) {
            $article = new Article();

            // FAKER
            $article->setArticleBody($this->faker->text);
            $article->setArticleResume($this->faker->text);
            $article->setAlternativeHeadline($this->faker->sentence(4, true));
            $article->setPushForward($this->faker->text);
            // $article->setText($this->faker->text);
            $article->setText($this->faker->paragraph(3, false));
            $article->setTextResume($this->faker->text);
            // $article->setDescription($this->faker->text);
            $article->setDescription($this->faker->text(200));
            $article->setBlockquote($this->faker->text);
            $article->setBlockquoteTitle($this->faker->sentence(3, true));

            if (isset($value['videos'])) {
                foreach ($value['videos'] as $video) {
                    $media = new MediaObject();
                    $media->setName($video['name']);
                    $media->setUrl($video['url']);
                    $media->setDimensions(null);
                    $media->setOriginalFilename(null);
                    $media->setFilename($video['name']);
                    $media->setEncodingFormat('video/youtube');
                    $media->setContentSize(null);

                    $article->addVideo($media);
                }
            }

            if (isset($value['headline'])) {
                $article->setHeadline($value['headline']);
            }
            if (isset($value['pushForward'])) {
                $article->setPushForward($value['pushForward']);
            }
            if (isset($value['alternativeHeadline'])) {
                $article->setAlternativeHeadline($value['alternativeHeadline']);
            }
            if (isset($value['text'])) {
                $article->setArticleBody($value['text']);
            }
            if (isset($value['category'])) {
                $tagSlug = $slugify->slugify($value['category']);
                $tag = $manager->getRepository(Tag::class)->findOneBy(['slug' => $tagSlug]);
                if (null === $tag) {
                    $tag = new Tag();
                    $tag->setName($value['category']);
                    $manager->persist($tag);
                    $manager->flush();
                }
                $article->setCategory($tag);
                $article->addTag($tag);
            }
            $media = $imageNotfound;
            if (isset($value['primaryImage'])) {
                $filename = $value['primaryImage'];
                if (isset($medias[$filename])) {
                    $media = $medias[$filename];
                }
            }
            $article->setPrimaryImage($media);

            $manager->persist($article);
        }
        $manager->flush();
    }

    private function loadJsonPages($manager)
    {
        /**
         * GET.
         **/
        $imageNotfound = $manager->getRepository(MediaObject::class)->findOneBy(['slug' => 'exterieur-jardin-img-38561-jpg']);
        $tags = $manager->getRepository(Tag::class)->findOneBy(['slug' => 'exterieur-jardin-img-38561-jpg']);

        $medias = $this->toolsMediaService->getMediaArray($manager);
        /**
         * CREATE TAGS.
         **/
        // $tagAccueil = new Tag();
        // $tagAccueil->setName('Accueil');
        // $manager->persist($tagAccueil);
        // $tagAgence = new Tag();
        // $tagBlog = new Tag();
        // $tagBlog->setName('Blog');
        // $manager->persist($tagBlog);
        // $manager->flush();

        $slugify = new Slugify();
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/pages.json';
        $pages = file_get_contents($pathJsonFile);
        $pages = json_decode($pages, true);
        foreach ($pages as $value) {
            $page = new WebPage();

            // FAKER
            $page->setAlternativeHeadline($this->faker->sentence(4, true));
            $page->setPushForward($this->faker->text);
            $page->setText($this->faker->paragraph(3, false));
            // $page->setText($this->faker->text);
            $page->setTextResume($this->faker->text);
            // $page->setDescription($this->faker->text);
            $page->setDescription($this->faker->text(200));
            $page->setBlockquote($this->faker->text);
            $page->setBlockquoteTitle($this->faker->sentence(3, true));

            if (isset($value['headline'])) {
                $page->setHeadline($value['headline']);
            }
            if (isset($value['pushForward'])) {
                $page->setPushForward($value['pushForward']);
            }
            if (isset($value['alternativeHeadline'])) {
                $page->setAlternativeHeadline($value['alternativeHeadline']);
            }
            if (isset($value['text'])) {
                $page->setText($value['text']);
            }
            if (isset($value['blockquote'])) {
                $page->setBlockquote($value['blockquote']);
            }
            if (isset($value['category'])) {
                $tagSlug = $slugify->slugify($value['category']);
                $tag = $manager->getRepository(Tag::class)->findOneBy(['slug' => $tagSlug]);
                if (null === $tag) {
                    $tag = new Tag();
                    $tag->setName($value['category']);
                    $manager->persist($tag);
                    $manager->flush();
                }
                $page->setCategory($tag);
                $page->addTag($tag);
            }

            $media = $imageNotfound;
            if (isset($value['primaryImage'])) {
                $filename = $value['primaryImage'];
                if (isset($medias[$filename])) {
                    $media = $medias[$filename];
                }
            }
            $page->setPrimaryImage($media);
            $manager->persist($page);
        }
        $manager->flush();

        /**************************************************************
        * WEBPAGES :  BIENVENUE
        ***************************************************************/
        // $template = $manager->getRepository(WebPageTemplate::class)->findOneBy(array('slug' => 'contact'));
        // $page = new WebPage();
        // $page->setWebPageTemplate($template);
        // $page->setHeadline('Contact');
        // $page->setPushForward('Pour tous renseignements contactez l\'Immobilière d\'Essaouira');
        // $page->setArticle($article);
        // $page->setAlternativeHeadline('Contact');
        // $page->setCategory($tagAgence);
        // $page->addTag($tagAgence);
        // $page->setPrimaryImage($image1);
        // $page->setSecondaryImage($image8);
        // $manager->persist($page);
        // $manager->flush();
    }

    private function loadPagesAndArticles($manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        /**
         * GET IMAGES HORIZONTAL.
         **/
        $image1 = $manager->getRepository(MediaObject::class)->findOneById(11);
        $image2 = $manager->getRepository(MediaObject::class)->findOneById(12);
        $image3 = $manager->getRepository(MediaObject::class)->findOneById(13);
        $image4 = $manager->getRepository(MediaObject::class)->findOneById(14);

        /**
         * CREATE TAGS.
         **/
        $tagAccueil = new Tag();
        $tagAccueil->setName('Accueil');
        $manager->persist($tagAccueil);
        $tagAgence = new Tag();
        $tagBlog = new Tag();
        $tagBlog->setName('Blog');
        $manager->persist($tagBlog);
        $manager->flush();

        /**************************************************************
        * ARTICLE : ACCUEIL
        ***************************************************************/
        $articleAgence = new Article();
        $articleAgence->setCategory($tagAgence);
        $articleAgence->addTag($tagAccueil);
        $articleAgence->addTag($tagAgence);
        $articleAgence->setHeadline(stripslashes('L\'Immobilière d\'Essaouira: l\'histoire d\'une passion'));
        $articleAgence->setAlternativeHeadline(stripslashes('L\'Immobilière d\'Essaouira'));
        $articleAgence->setPushForward('UN SAVOIR-ETRE ET UN SAVOIR-FAIRE RECONNUS');
        $articleAgence->setPrimaryImage($image1);
        $articleAgence->setSecondaryImage($image5);
        $articleAgence->setGallery($galleryHorizontal);

        $articleAgence->setArticleBody(html_entity_decode('<p>Notre histoire reste avant tout celle d&rsquo;une rencontre. Cet &eacute;v&egrave;nement a d&eacute;clench&eacute; la d&eacute;cision rapide d&rsquo;un nouveau projet<strong> </strong>de vie pour notre couple.</p>

<p>Notre passion commune pour le Maroc a permis de valider la destination o&ugrave; notre vie priv&eacute;e et professionnelle allait dor&eacute;navant se poursuivre, abandonnant nos carri&egrave;res de cadre bancaire et de charg&eacute;e de mission pour la Chambre de Commerce et d&rsquo;Industrie de la Rochelle, sans compter les ann&eacute;es pass&eacute;es dans le domaine de la d&eacute;coration.</p>

<p>Nous avons quitt&eacute; le confort douillet de l&rsquo;agglom&eacute;ration rochelaise pour changer de vie. Nous connaissions le Maroc et Essaouira. La ville nous a tr&egrave;s vite s&eacute;duit par son c&ocirc;t&eacute; authentique mais aussi par l&rsquo;accueil de ses habitants, sa notion de proximit&eacute;, sa m&eacute;dina c&oelig;ur historique de la ville sans voitures, son port, son climat. Que de ressemblances avec notre ville de d&eacute;part, La Rochelle. Les arguments n&rsquo;ont pas manqu&eacute; pour nous faire comprendre que nous &eacute;tions au bon endroit. Une fois install&eacute;s, l&agrave; encore, le hasard a jou&eacute; les bonnes f&eacute;es et g&eacute;n&eacute;r&eacute; rapidement les nombreuses activit&eacute;s professionnelles que nous exer&ccedil;ons encore aujourd&rsquo;hui avec tant de passion. Une nouvelle rencontre avec un entrepreneur marocain nous a permis de commencer la construction d&rsquo;un immeuble de 7 appartements. St&eacute;phane a donc embrass&eacute; la carri&egrave;re d&rsquo;entrepreneur en b&acirc;timent et surf&eacute; sur l&rsquo;engouement des europ&eacute;ens pour le Maroc. De nombreuses r&eacute;alisations ont &agrave; ce jour compl&eacute;t&eacute; notre carte de visite (r&eacute;novations diverses en m&eacute;dina et constructions de maisons en campagne, d&eacute;veloppement d&#39;un parc structur&eacute; de gestion locative, etc...).</p>

<p>D&egrave;s&nbsp; notre installation &agrave; Essaouira en 2004, nous avions &laquo; trac&eacute; la route &raquo;&hellip;</p>

<p>Aujourd&rsquo;hui, notre force r&eacute;side dans notre compl&eacute;mentarit&eacute;. St&eacute;phane s&rsquo;occupe des ventes de produits, de toutes les formalit&eacute;s administratives, des suivis de chantiers, etc&hellip; De mon c&ocirc;t&eacute;, j&rsquo;adapte au mieux les produits que nous vendons &agrave; Essaouira et sa r&eacute;gion (riads, villas, maisons de campagne, appartements, etc), en les am&eacute;nageant et les d&eacute;corant dans le but d&rsquo;optimiser leur rentabilit&eacute; locative. Notre souci premier reste de satisfaire notre client&egrave;le et de l&rsquo;accompagner dans ses projets d&rsquo;installation et de rentabilit&eacute; locative &agrave; Essaouira, dans les meilleures conditions.</p>

<p>Notre entreprise reste la structure id&eacute;ale sur laquelle les candidats &eacute;trangers &agrave; l&rsquo;accession &agrave; la propri&eacute;t&eacute; &agrave; Essaouira peuvent s&rsquo;appuyer. Pour la plus grande tranquillit&eacute; d&rsquo;esprit de nos clients, nous mettons tout en &oelig;uvre pour assurer personnellement le suivi de chaque affaire y compris les formalit&eacute;s administratives, sujet lourd mais n&eacute;anmoins incontournable.</p>

<p>Actuellement, notre parfaite connaissance du march&eacute; immobilier local en terme de transactions et de locations, coupl&eacute; &agrave; nos savoirs faire en mati&egrave;re d&rsquo;am&eacute;nagement et de d&eacute;coration, nous permet de p&eacute;renniser notre notori&eacute;t&eacute; et nos capacit&eacute;s &agrave; continuer d&rsquo;entreprendre au Maroc.</p>

<p>Nous restons &agrave; votre &eacute;coute au sein de notre&nbsp; agence.</p>

<p>A bient&ocirc;t.</p>

<p>Natacha SCHOPPE &amp; St&eacute;phane LAURENT</p>'));

        $articleAgence->setArticleResume(html_entity_decode(strip_tags('Notre histoire reste avant tout celle d&rsquo;une rencontre. Cet &eacute;v&egrave;nement a d&eacute;clench&eacute; la d&eacute;cision rapide d&rsquo;un nouveau projet<strong> </strong>de vie pour notre couple.Notre passion commune pour le Maroc a permis de valider la destination o&ugrave; notre vie priv&eacute;e et professionnelle allait dor&eacute;navant se poursuivre, abandonnant nos carri&egrave;res de cadre bancaire et de charg&eacute;e de mission pour la Chambre de Commerce et d&rsquo;Industrie de la Rochelle, sans compter les ann&eacute;es pass&eacute;es dans le domaine de la d&eacute;coration.')));
        $manager->persist($articleAgence);
        $manager->flush();

        /**************************************************************
        * WEBPAGES :  BIENVENUE
        ***************************************************************/
        $template = $manager->getRepository(WebPageTemplate::class)->findOneBy(['slug' => 'contact']);
        $page = new WebPage();
        $page->setWebPageTemplate($template);
        $page->setHeadline('Contact');
        $page->setPushForward('Pour tous renseignements contactez l\'Immobilière d\'Essaouira');
        $page->setArticle($article);
        $page->setAlternativeHeadline('Contact');
        $page->setCategory($tagAgence);
        $page->addTag($tagAgence);
        $page->setPrimaryImage($image1);
        $page->setSecondaryImage($image8);
        $manager->persist($page);
        $manager->flush();
    }
}
