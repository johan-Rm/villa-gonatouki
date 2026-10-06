<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait SluggableHeadlineTrait
{
    /**
     * @Gedmo\Slug(fields={"headline"}, updatable=false)
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    public function getSlug()
    {
        return $this->slug;
    }
}
