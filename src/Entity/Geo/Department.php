<?php

namespace App\Entity\Geo;

use Algolia\AlgoliaSearchBundle\Mapping\Annotation as Algolia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="geo_department")
 * @ORM\HasLifecycleCallbacks
 *
 * @Algolia\Index(autoIndex=false)
 */
class Department
{
    use GeoTrait;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\Region")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    public function __construct(string $code, string $name, Region $region)
    {
        $this->code = $code;
        $this->name = $name;
        $this->region = $region;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }
}