<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Translation;

use App\Entity\Translation;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Google\Cloud\Translate\TranslateClient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Dumper\XliffFileDumper;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * Translation service for an entity using the easyadmin configuration
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class EasyAdminTranslator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
    * @var EntityManager
    */
    private $em;

    /**
     * @var Google\Cloud\Translate\TranslateClient
     */
    private $googleTranslator;

    /**
    * @var TranslatorInterface
    */
    private $translator;

    /**
    * @var Slugify
    */
    private $slugify;

    /**
    * @var TranslationRepository
    */
    private $translationRepository;

    /**
    * @var array
    */
    // public $newTranslate = [];

    /**
    * @var bool
    */
    private $isActive = true;

    /**
    * @var array
    */
    private $config = [];

    /**
    * @var integer
    */
    private $identifier = null;

    /**
    * @var array
    */
    private $languages = [];

    /**
    * @var array
    */
    // private $translations = [];

    /**
    * @var bool
    */
    private $slugForce = true;

    /**
    * @var Tools\Html
    */
    private $toolsHtmlService;

    /**
    * @var array
    */
    public $newTranslate = [];

    public function __construct(ContainerInterface $container, EntityManager $em, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->slugify = new Slugify();
        $this->container = $container;
        $this->em = $em;
        $this->languages = ['fr', 'en', 'es'];
        $this->googleTranslator = new TranslateClient([
              'key' => $this->container->getParameter('google_translator_key'),
        ]);

        $this->translationRepository = $this->em->getRepository(Translation::class);
        $this->toolsHtmlService = $this->container->get('app.tools.html');
        /*
        *
        * pour dedoublonner
        *
        * DELETE t0 FROM immobiliere_essaouira.translation t0
        * LEFT OUTER JOIN (
        *        SELECT MIN(id) as id, lang, field_name, entity_name, entity_id
        *        FROM immobiliere_essaouira.translation
        *        GROUP BY lang, field_name, entity_name, entity_id
        *    ) as t1
        *    ON t0.id = t1.id
        * WHERE t1.id IS NULL
        * ;
        **/
    }

    public function setEntityIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function setEasyadminConfig($config)
    {
        $this->config = $config;
    }

    public function process($entityValues)
    {

        if(!$this->isActive) {
            return false;
        }

        /**
        * SI LES FICHIERS N'EXISTE PAS IL FAUT LES CRÉER
        *  $this->languages = ['fr', 'en'];
        * il faut changer de process car cela implique un projet en front
        * tu peux tres bien traduire simplement le backoffice
        * et le write tu le ferra si le projet en front le demande
        */

        /**
        * @todo Je peux changer de méthode et ne plus parcourir le dossier
        */
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($this->container->getParameter('view.translations.path'));
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $language = \pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                if (in_array($language, $this->languages)) {
                    $this->translateEntity($entityValues, $language);
                }
            }
        }

        return true;
    }

    public function write()
    {
        if(!$this->isActive) {
            return false;
        }

        $finder = new Finder();
        $finder->depth('== 0');
        // find all files in the current directory
        $finder->files()->in($this->container->getParameter('view.translations.path'));
        // check if there are any search results
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                // $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $language = \pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                if (in_array($language, $this->languages)) {
                    $file = json_decode($file->getContents(), true);
                    $this->writeTranslationsInViewproject($language, $file);
                }
            }
        }
    }

    /**
     * Please do not hesitate to describe your class and your functions...
     *
     * Usage:
     *
     * @author Johan REMY <johan.remy@graines-digitales.online>
     */
    // public function setValuesEntity($entityName)
    // {
    //     $results = $this->translationRepository->findBy(
    //         ['entityName' => $entityName]
    //     );
    //     foreach ($results as $result) {
    //         $key = $this->createKey(
    //             $result->getLang(), $result->getEntityName(), $result->getFieldName(), $result->getEntityId()
    //         );
    //         $this->translations[$key] = $result;
    //     }
    // }

    public function checkFieldConfig($fieldName)
    {
      // si la config existe
      if (isset($this->config['form']['fields'][$fieldName])) {
          // si le champ est à traduire
          if (
              isset($this->config['form']['fields'][$fieldName]['translatable'])
              &&
              $this->config['form']['fields'][$fieldName]['translatable']
          ) {

            return true;
          }
      }

      return false;
    }

    private function translateEntity($values, $language)
    {

        // parcours tous les champs de l'entité
        foreach ($values as $fieldName => $value) {
            $fieldName = Inflector::camelize($fieldName);
            if ($this->checkFieldConfig($fieldName)) {

              /**
              * On récupère la traduction existante pour s'assurer
              * que la traduction n'est pas "locker"
              */
              $existingTranslation = $this->translationRepository->findOneBy(
                  [
                      'lang' => $language,
                      'entityName' => $this->config['name'],
                      'fieldName' => $fieldName,
                      'entityId' => $this->identifier,
                  ]
              );

              $locked = (is_callable([$existingTranslation, 'getIsLocked']) && $existingTranslation->getIsLocked() === true)? true: false;
              if(!$this->checkIfFieldIsValid($locked, $value)) {
                  continue;
              }

              $chain = $this->stringProcessing($value);
              $isHtml = $this->checkIfIsHtml($fieldName);
              // dump($values);

              /**
              * @hack pour CreativeWork ...
              */
              if(!isset($values['slug'])) {

                  $slug = $this->slugify->slugify($values['text']);
              } else {
                  $slug = $values['slug'];
              }


              $keySlug = $this->generateKeySlug($language, $fieldName, $slug);
             

              $translation = $this->getTranslation($language, $chain, $fieldName);

              $this->translationRecording(
                  $chain,
                  $translation,
                  $fieldName,
                  $language,
                  $existingTranslation,
                  $isHtml,
                  $keySlug
              );
            } // end of checkFieldConfig
        } // end foreach values
        // $this->em->clear();
    }

    private function translate($chain, $key, $language)
    {
        if ('slug' == $key) {
            $chain = str_replace('-', ' ', $chain);
            $translation = $this->googleTranslator->translate(
                strtolower($chain), ['target' => $language, 'source' => 'fr']
            );
            $translation['text'] = html_entity_decode($translation['text'], ENT_QUOTES);
            $translation['text'] = $this->slugify->slugify($translation['text']);

            return $translation['text'];
        } else {
            $translation = $this->googleTranslator->translate(
                $chain, ['target' => $language, 'source' => 'fr']
            );

            return html_entity_decode($translation['text'], ENT_QUOTES);
        }
    }

    private function getTranslation($language, $chain, $fieldName)
    {
      /**
      * Pas de traduction de la langue
      * si la langue est la meme que celle configurer dans l'app
      */
      if($this->container->getParameter('locale') !== $language) { // all languages except French
          
          $found = $this->checkIfTranslationExist($language, $chain);

          

          if (false === $found) {

              // $translation = $this->translate(
              //     $chain, $fieldName, $language
              // );

              /**
              * @HACK POUR SIMULER LA TRADUCTION
              */
              $translation = $chain;
          } else {
        //      if($fieldName == "blockquoteTitle") {
        //           dump($language);
        // dump($chain);
        // dump($fieldName);
        // dump($found);
        //       }
              
              $translation = $found;
          }
      } else {
          $translation = $chain;
      }

      return $translation;
    }

    private function checkIfTranslationExist($language, $currentChain)
    {
      

      $catalogue = $this->translator->getCatalogue($language);

      /**
      * si la chaine est différente de celle trouver dans le catalogue (dictionnary)
      * alors la traduction existe deja
      */
      if ($catalogue->has($currentChain, 'messages')) {

// dump($catalogue);
// dump($currentChain);
// dump($catalogue->get($currentChain, 'messages'));
// die;
        // die('la chaine trouvé');
        return $catalogue->get($currentChain, 'messages');
      } else {
        // dump($catalogue);
//         dump($catalogue->get($currentChain, 'messages'));
//         dump($catalogue->has($currentChain, 'messages'));
//         dump($language);
// dump($currentChain);
// die;
        $this->newTranslate[] = $currentChain;
      }

      return false;
    }

    private function checkIfFieldIsValid($locked, $chain)
    {
      if (
          empty($chain)
          || true === $locked
          || is_numeric($chain)
      ) {
          return false;
      }

      return true;
    }

    private function checkIfIsHtml($fieldName)
    {
      if (
          isset($this->config['form']['fields'][$fieldName]['type'])
          &&
          'fos_ckeditor' == $this->config['form']['fields'][$fieldName]['type']
      ) {
          return true;
      }

      return false;
    }

    private function stringProcessing($string)
    {
        $string = trim($string);
        $string = html_entity_decode($string, ENT_QUOTES);

        return $string;
    }

    private function translationRecording($chain, $translation, $key, $language, $entity, $isHtml, $keySlug)
    {
        if (null === $entity) {
            $translatedFieldName = $this->translator->trans($key);
            $translatedEntityName = $this->translator->trans($this->config['name']);
            $entity = new Translation();
            $entity->setLang($language);
            $entity->setEntityName($this->config['name']);
            $entity->setTranslatedEntityName($translatedEntityName);
            $entity->setEntityId($this->identifier);
            $entity->setHashKey('null');
            $entity->setFieldName($key);
            $entity->setTranslatedFieldName($translatedFieldName);
            $entity->setKeyLeft($chain);
            $entity->setIsLocked(false);
            $entity->setIsHtml($isHtml);
            $entity->setValueRight($translation);
            $entity->setKeySlug($keySlug);
            $this->em->persist($entity);
        } else {
            $entity->setIsHtml($isHtml);
            $entity->setKeyLeft($chain);
            $entity->setValueRight($translation);
            $entity->setIsLocked(false);
            $entity->setKeySlug($keySlug);
            $this->em->merge($entity);
        }
        $this->em->flush();

        return $entity;
    }

    private function writeInCatalog($messages, $locale)
    {
        $translationsPath = $this->container->getParameter('app.translations.path');
        $catalogue = $this->translator->getCatalogue($locale);
        $catalogue->add($messages, 'contents');
        $dumper = new XliffFileDumper();
        $dumper->dump($catalogue, [
            'default_locale' => $this->container->getParameter('kernel.default_locale'),
            'xliff_version' => '1.2',
            'path' => $translationsPath,
        ]);

        // dump($catalogue);die;
    }

    /**
    * @todo améliorer el process pour ne pas réécrire
    * tous les fichiers intégralement à chaque update
    */
    private function writeTranslationsInViewproject($language, $file)
    {
        $filesystem = new Filesystem();
        $translationRepository = $this->em->getRepository(Translation::class);
        $results = $translationRepository->findBy(['lang' => $language]);
        $jsonMessages = [];
        $xliffMessages = [];
        foreach ($results as $result) {
            $jsonMessages[$result->getKeySlug()] = $result->getValueRight();
            $xliffMessages[$result->getKeyLeft()] = $result->getValueRight();
        }
        // if($this->container->getParameter('kernel.default_locale') !== $language) {
            // $this->writeInCatalog($xliffMessages, $language);
        // }
        // dump($language);die();
        $jsonFile = json_encode($jsonMessages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $filePath = $this->container->getParameter('view.translations.path').'/'.$language.'.json';
        // dump($jsonFile);
        // dump($filePath);
        $filesystem->dumpFile($filePath, $jsonFile);
    }

    // private function createKey($language, $entityName, $fieldName, $entityId)
    // {
    //     $key = '';
    //     $key .= $language;
    //     $key .= '-'.$entityName;
    //     $key .= '-'.$fieldName;
    //     $key .= '-'.$entityId;
    //     $key = strtolower($key);
    //
    //     return $key;
    // }


    private function generateKeySlug($language, $fieldName, $slug)
    {
        $key = $language.' '.$fieldName.' '.$this->config['name'];
        $key = $this->slugify->slugify($key, '.').'.'.$slug;

        return $key;
    }
}
