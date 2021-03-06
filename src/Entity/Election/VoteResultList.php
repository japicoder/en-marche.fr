<?php

namespace App\Entity\Election;

use Algolia\AlgoliaSearchBundle\Mapping\Annotation as Algolia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @Algolia\Index(autoIndex=false)
 */
class VoteResultList
{
    use ListFieldTrait;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var VoteResultListCollection
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Election\VoteResultListCollection", inversedBy="lists")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $listCollection;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListCollection(): ?VoteResultListCollection
    {
        return $this->listCollection;
    }

    public function setListCollection(?VoteResultListCollection $listCollection): void
    {
        $this->listCollection = $listCollection;
    }
}
