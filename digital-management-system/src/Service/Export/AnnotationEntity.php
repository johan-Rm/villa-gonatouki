<?php

/*
 * This file is part of the Graines Digitales DMS project.
 *
 * (c) Johan REMY <johan.remy@graines-digitales.online>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Export;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * ...
 *
 * @author Johan REMY <johan.remy@graines-digitales.online>
 */
class AnnotationEntity
{
    private $container;
    private $em;

    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getAnnotationsSchema($classMetadata)
    {
        $annotationReader = new AnnotationReader();
        $annotations = [];
        foreach ($classMetadata->fieldMappings as $field) {
            dump($field);
            $reflectionProperty = new \ReflectionProperty($classMetadata->name, $field['fieldName']);
            $propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);
            $indexName = Inflector::tableize($reflectionProperty->getName());
            $properties = [];

            foreach ($propertyAnnotations as $property) {
                $reflectionClass = new \ReflectionClass($property);
                $properties[strtolower($reflectionClass->getShortName())] = $property;
            }

            $annotations[$indexName] = $properties;
        }

        return $annotations;
    }

    public function getContraintsByField($className, $field)
    {
        $metadata = $this->container
          ->get('validator')
          ->getMetadataFor($className);
        $propertyMetadata = $metadata->getPropertyMetadata($field);

        return $propertyMetadata[0]->constraints;
    }
}
