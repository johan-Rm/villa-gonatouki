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

use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Google\Cloud\Translate\TranslateClient;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AppFixtures extends AbstractFixtures implements DependentFixtureInterface, FixtureGroupInterface
{
    /**
     * Default Metadata.
     **/

   /**
   * @var array
   */
    private $genders = [];

    /**
    * @var array
    */
    private $paymentMethods = [];

    /**
    * @var array
    */
    private $documentObjectTypes = [];

    /**
    * @var array
    */
    private $webpageTemplates = [];

    /**
    * @var array
    */
    private $tags = [];

    /**
    * @var array
    */
    private $organizationTypes = [];

    /**
     * Accommodation Metadata.
     **/

   /**
   * @var array
   */
    private $accommodationAmenities = [];

    /**
    * @var array
    */
    private $accommodationLocation = [];

    /**
    * @var array
    */
    private $accommodationTypes = [];

    /**
    * @var array
    */
    private $accommodationNatures = [];

    /**
    * @var array
    */
    private $accommodationPlaces = [];

    /**
    * @var array
    */
    private $accommodationLabels = [];

    /**
    * @var array
    */
    private $rentalTypes = [];

    /**
    * @var array
    */
    private $rentalPriceTypes = [];

    /**
    * @var array
    */
    private $words = [];



    public function __construct()
    {
        /*
        *
        * Dans un premier temps on charge les données en dur
        * features => chargé les données depuis fichier JSON ou YAML
        *
        **/
        $this->setDefaultMetadataTabs();
        $this->setRealEstateMetadataTabs();

    }

    public static function getGroups(): array
    {
        return ['AppFixtures'];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            MediaFixtures::class,
            SettingFixtures::class,
            ComponentFixtures::class,
            // WebPageFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $this->initialize($manager);

        // /*
        // * Init vars
        // **/
        // $this->slugify = new Slugify();
        // $this->manager = $manager;
        // $this->consoleOutput = $this->getConsoleOutput();
        // $this->tableOutput = $this->getTableOutput();
        $start = date('Y-m-d\ H:i:s.u');

        $this->consoleOutput->writeln(
            sprintf('<fire>Start load AppFixtures : %s</fire>', $start)
        );

        $this->loadMetadata();
        $this->loadEvents();
        $this->loadFreeContent();
        // $this->loadDefaultTranslations();

        $this->consoleOutput->writeln('');
    }

    private function loadFreeContent()
    {
        $medias = $this->toolsMediaService->getMediaArray($this->manager);
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/creative_work.json';
        $results = file_get_contents($pathJsonFile);
        $results = json_decode($results, true);
        foreach ($results as $value) {
            $creativeWork = new \App\Entity\CreativeWork();
            $creativeWork->setText($value);
            $this->manager->persist($creativeWork);
            $this->manager->flush();
        }

    }

    private function loadEvents()
    {
        $medias = $this->toolsMediaService->getMediaArray($this->manager);
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $pathJsonFile = $kernelProjectDir.'/data/events.json';
        $results = file_get_contents($pathJsonFile);
        $results = json_decode($results, true);
        foreach ($results as $value) {
            $slug = $this->slugify->slugify($value['category']);
            if (!isset($this->tags[$slug])) {
                $category = new \App\Entity\Tag();
                $category->setName($value['category']);
                $this->manager->persist($category);
                $this->manager->flush();
                $this->tags[$slug] = $category;
            }
            $slug = $this->slugify->slugify($value['tag']);
            if (!isset($this->tags[$slug])) {
                $tag = new \App\Entity\Tag();
                $tag->setName($value['tag']);
                $this->manager->persist($tag);
                $this->manager->flush();
                $this->tags[$slug] = $tag;
            }
            $index = $value['primaryImage'];
            // dump($medias);
            // dump(array_keys($medias));
            // dump($index);
            // die;
            $event = new \App\Entity\Event();
            if (isset($medias[$index])) {
                $media = $medias[$index];
                $event->setPrimaryImage($media);
            }

            $event->setName($value['name']);
            $event->setDescription($value['description']);
            $beginAt = new \DateTime($value['beginAt']);
            $event->setBeginAt($beginAt);
            $endAt = new \DateTime($value['endAt']);
            $event->setEndAt($endAt);
            $event->setCategory($tag);
            $event->addTag($tag);
            $this->manager->persist($event);
            $this->manager->flush();
        }
        $this->consoleOutput->writeln('<comment>loadEvents</comment>');
    }

    public function loadMetadata()
    {
        $this->loadDefaultMetadata();
        $this->loadRealEstateMetadata();

        $this->consoleOutput->writeln('<comment>loadMetadata</comment>');
    }

    public function loadDefaultMetadata()
    {
        foreach ($this->tags as $tag) {
            $entity = new \App\Entity\Tag();
            $entity->setName($tag);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->webpageTemplates as $webpageTemplate) {
            $entity = new \App\Entity\WebPageTemplate();
            $entity->setName($webpageTemplate);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->genders as $gender) {
            $entity = new \App\Entity\Gender();
            $entity->setName($gender);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->paymentMethods as $paymentMethod) {
            $entity = new \App\Entity\PaymentMethod();
            $entity->setName($paymentMethod);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->organizationTypes as $organizationType) {
            $entity = new \App\Entity\OrganizationType();
            $entity->setName($organizationType);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        $this->consoleOutput->writeln('<comment>loadDefaultMetadata</comment>');
    }

    public function loadRealEstateMetadata()
    {
        foreach ($this->documentObjectTypes as $documentObjectType) {
            $entity = new \App\Entity\DocumentObjectType();
            $entity->setName($documentObjectType);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->positions as $position) {
            $entity = new \App\Entity\PersonPosition();
            $entity->setName($position);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->personNatures as $personNature) {
            $entity = new \App\Entity\PersonNature();
            $entity->setName($personNature);
            $this->manager->persist($entity);
        }
        $this->manager->flush();
        foreach ($this->accommodationLabels as $accommodationLabel) {
            $entity = new \App\Entity\AccommodationLabel();
            $entity->setName($accommodationLabel);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->accommodationLocation as $accommodationLocation) {
            $entity = new \App\Entity\AccommodationLocation();
            $entity->setName($accommodationLocation);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->accommodationAmenities as $accommodationAmenity) {
            $entity = new \App\Entity\AccommodationAmenity();
            if (is_array($accommodationAmenity)) {
                $entity->setName($accommodationAmenity[0]);
                $entity->setWithPicto($accommodationAmenity[1]);
                $entity->setSlugPicto($accommodationAmenity[2]);
            } else {
                $entity->setName($accommodationAmenity);
                $entity->setWithPicto(false);
            }

            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->accommodationPlaces as $accommodationPlace) {
            $entity = new \App\Entity\AccommodationPlace();
            $entity->setName($accommodationPlace);
            $entity->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->accommodationTypes as $accommodationType) {
            $entity = new \App\Entity\AccommodationType();
            $entity->setName($accommodationType);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->accommodationNatures as $accommodationNature) {
            $entity = new \App\Entity\AccommodationNature();
            $entity->setName($accommodationNature);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->rentalTypes as $rentalType) {
            $entity = new \App\Entity\RentalType();
            $entity->setName($rentalType);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        foreach ($this->rentalPriceTypes as $rentalPriceType) {
            $entity = new \App\Entity\RentalPriceType();
            $entity->setName($rentalPriceType);
            $this->manager->persist($entity);
        }
        $this->manager->flush();

        $this->consoleOutput->writeln('<comment>loadRealEstateMetadata</comment>');
    }

    // public function loadDefaultTranslations($manager)
    // {
    //     // $translate = new TranslateClient(
    //     //     ['key' => 'YOUR_GOOGLE_MAPS_API_KEY']
    //     // );
    //     $translationRepository = $this->manager->getRepository(Translation::class);
    //     $languages = ['fr', 'en'];

    //     foreach ($languages as $language) {
    //         foreach ($this->words as $key => $value) {
    //             $chain = html_entity_decode($value, ENT_QUOTES);

    //             // $translation = $translate->translate($chain, [
    //             //     'target' => $language
    //             // ]);
    //             // die('andek translation!');
    //             $translation = null;
    //             $translation = html_entity_decode(
    //                 $translation['text'], ENT_QUOTES
    //             );

    //             $entity = new \App\Entity\Translation();
    //             $entity->setLang($language);
    //             $entity->setEntityName('null');
    //             $entity->setEntityId('null');
    //             $entity->setHashKey('null');
    //             $entity->setFieldName('null');
    //             $entity->setKeyLeft($chain);
    //             $entity->setIsLocked(false);
    //             $entity->setValueRight($translation);
    //             $this->manager->persist($entity);
    //             $this->manager->flush();
    //         }
    //     }

    //     $this->consoleOutput->writeln('<comment>loadDefaultTranslations</comment>');
    // }

    private function setDefaultMetadataTabs()
    {
        $this->words = [
            'Derniers articles',
            'vente',
            'location',
            'nos experts',
            'decoration',
            'agence',
            'accueil',
            'calme',
            'jardin',
            'Experience Professionnelle',
            'nos services',
            'post',
            'Blog',
            'plus d\'infos',
            'Projets',
            'Accomplis',
            'Tags',
            'Liens utiles',
            'mois',
            'description',
            'Détails',
            'Identifiant de la propriété',
            'Prix',
            'Type de propriété',
            'Salle de bain',
            'Propriété Taille du lot',
            'Aire d\'atterrissage',
            'Endroit',
            'fonctionnalités',
            'plan d\'étage',
            'Chercher',
            'Partager cette publication',
            'vidéos',
            'appelez nous',
            'Taille',
            'Pièces',
            'Garage',
            'Numéro de téléphone',
            'Adresse électronique',
            'Lire la suite',
            'Soumettre',
            'adresse',
            'ville & pays',
            'Rénovation',
            'Construction',
            'Location saison',
            'Longue durée',
            'chambres',
            'Réf. agence',
            'Téléphone',
            'Vidéo',
            'Localisation',
            'détails',
            'plan d\'étage',
            'video',
            'Une erreur est survenue',
            'Votre message a été envoyé',
            'Votre message a bien été envoyé',
            'Veuillez saisir un prénom',
            'Veuillez saisir un nom',
            'Veuillez saisir un email',
            'Veuillez saisir un message',
            'Veuillez saisir un email valide',
            'Merci de patienter',
            'Nous vous répondrons dans les meilleurs délais',
            'Derniers biens',
            'Chambres',
            'Surface',
            'Terrain',
            'Terrasse',
            'jacuzzi',
            'garage',
            'cheminée',
            'Notre galerie',
            'salle de bain',
            'piscine',
            'wifi',
            'video',
            'veuillez saisir un email valide',
            'veuillez saisir un message',
            'veuillez saisir un email',
            'veuillez saisir un nom',
            'veuillez saisir un prénom',
            'Politique de confidentialité',
            'Contactez nous',
            'Termes et conditions',
            'N\'hésitez pas à lui envoyer un email',
            'Vous n\'avez pas réussi à joindre notre agent ?',
            'Envoyer',
            'Contacter l\'agence',
            'Message',
            'E-mail',
            'Prénom',
            'Nom',
            'Voir plus de détails',
            'A propos de nous',
            'localisation',
            'Saisir la référence',
            'Budget max.',
            'Budget max',
            'Budget min.',
            'Budget min',
            'de surface de terrain',
            'Biens similaires',
            'Rechercher un bien immobilier',
            'Vacances',
            'vous connaissez votre référence ?',
            'Achat',
            'Envoyer',
            'Votre message',
            'Informations & réservations'
        ];
        $this->organizationTypes = [
            'societe' => 'Société',
            'lien-reseau-social' => 'Lien réseau social',
        ];
        $this->webpageTemplates = [
            'default' => 'Default',
            'home' => 'Home',
            'about-us' => 'About Us',
            'contact' => 'Contact',
        ];
        $this->genders = [
            'm' => 'M.',
            'f.' => 'Mme.',
        ];
        $this->paymentMethods = [
          'cheque' => 'Chèque',
          'virement' => 'Virement',
          'espece' => 'Espèce',
          'account-france' => 'Account France',
          'account-maroc' => 'Account Maroc',
        ];
    }

    private function setRealEstateMetadataTabs()
    {
        $this->positions = [
              'notaire' => 'Notaire',
              'gardien' => 'Gardien',
        ];
        $this->tags = [
            'villa-piscine' => 'Villa Piscine',
            'jardin' => 'Jardin',
            'double-vitrage' => 'Double vitrage',
            'villa-luxe' => 'Villa luxe',
            'calme' => 'Calme',
            'appartement-standing' => 'Appartement standing',
        ];
        $this->personNatures = [
            'locataire' => 'Locataire',
            'acheteur' => 'Acheteur',
        ];
        $this->documentObjectTypes = [
          'plan' => 'Plan',
          'mandat' => 'Mandat',
          'titre' => 'Titre',
          'contrat' => 'Contrat',
        ];
        $this->accommodationLabels = [
            'exclusivite' => 'Exclusivité',
            'coup-de-coeur' => 'Coup de coeur',
        ];
        $this->accommodationAmenities = [
            'piscine' => ['Piscine', true, 'swimming-pool'],
            'piscine-chauffee' => ['Piscine chauffée', false, null],
            'climatisation' => ['Climatisation', false, null],
            'climatisation-reversible' => ['Climatisation reversible', false, null],
            'garage' => ['Garage', true, 'warehouse'],
            'jacuzzi' => ['Jacuzzi', true, 'hot-tub'],
            'wifi' => ['Wifi', true, 'wifi'],
            'air-conditionne' => 'Air conditionné',
            'balcon' => 'Balcon',
            'room-service' => 'Room service',
            'salle-de-sport' => 'Salle de sport',
            'parking' => 'Parking',
            'alarme' => 'Alarme',
            'gardien' => 'Gardien',
            'salle-de-bain' => ['Salle de bain', true, 'bath'],
            'cheminee' => ['Cheminée', true, 'fire'],
            'terrasse' => 'Terrasse',
            'solarium' => 'Solarium',
            'parking' => 'Parking',
            'ascenseur' => 'Ascenseur',
            'vue-sur-mer' => 'Vue sur mer',
            'hammam' => 'Hammam',
            'terrasse-attenante' => 'Terrasse attenante',
            'vue-degagee' => 'Vue dégagée',
            'exposition-sud' => 'Exposition Sud',
        ];
        $this->accommodationTypes = [
            'appartement' => 'appartement',
            'riad' => 'riad',
            'maisons-d-hotes' => 'maisons d\'hotes',
            'villa-golf' => 'villa golf',
            'maison-de-campagne' => 'maison de campagne',
            'maison-de-ville' => 'maison de ville',
            'terrain' => 'terrain',
            'affaires-commerciales' => 'affaires commerciales',
            'location-gerance' => 'location gérance',
            'local-commercial' => 'local commercial',
        ];
        $this->accommodationLocation = [
            'longue-duree' => 'Longue durée',
            'saisonniere' => 'Saisonnière',
        ];
        $this->accommodationNatures = [
            'location' => 'Location',
            'vente' => 'Vente',
        ];
        $this->accommodationPlaces = [
            'medina' => 'medina',
            'nouvelle-ville' => 'nouvelle-ville',
            'golf-mogador' => 'golf mogador',
            'campagne' => 'campagne',
            'diabet' => 'diabet',
            'ghazoua' => 'ghazoua',
            'douar-larab' => 'douar larab',
            'sidi-kaouki' => 'sidi kaouki',
            'ida-ougourd' => 'ida ougourd',
            'hrarta' => 'hrarta',
            'bouzama' => 'bouzama',
            'aeroport' => 'aeroport',
            'had-draa' => 'had draa',
            'ounagha' => 'ounagha',
            'moulay-bouzarktoun' => 'moulay bouzarktoun',
            'laraich' => 'laraich',
            'chicht' => 'chicht',
            'route-de-marrakech' => 'route de marrakech',
            'route-de-safi' => 'route de safi',
        ];
        $this->rentalTypes = [
            'la' => 'Location appartement',
            'lm' => 'Location maison',
            'lvg' => 'Location villa du golf',
        ];
        $this->rentalPriceTypes = [
            'hebdomadaire' => 'À la semaine',
            'mensuelle' => 'Au mois',
        ];
    }
}
