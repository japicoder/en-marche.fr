<?php

namespace App\Entity\ElectedRepresentative;

use App\Entity\EntityReferentTagTrait;
use App\Entity\ReferentTag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as SymfonySerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElectedRepresentative\ZoneRepository")
 * @ORM\Table(
 *     name="elected_representative_zone",
 *     indexes={
 *         @ORM\Index(name="elected_repr_zone_code", columns={"code"}),
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="elected_representative_zone_name_category_unique", columns={"name", "category_id"})
 *     })
 */
class Zone
{
    use EntityReferentTagTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @SymfonySerializer\Groups({"autocomplete"})
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column
     *
     * @SymfonySerializer\Groups({"autocomplete"})
     */
    private $name;

    /**
     * @var ZoneCategory|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ElectedRepresentative\ZoneCategory", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var Collection|ReferentTag[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ReferentTag")
     * @ORM\JoinTable(
     *     name="elected_representative_zone_referent_tag",
     *     joinColumns={
     *         @ORM\JoinColumn(name="elected_representative_zone_id", referencedColumnName="id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="referent_tag_id", referencedColumnName="id", onDelete="CASCADE")
     *     }
     * )
     */
    protected $referentTags;

    /**
     * @var string|null
     *
     * @ORM\Column(nullable=true)
     */
    private $code;

    public function __construct(ZoneCategory $category = null, string $name = null)
    {
        $this->category = $category;
        $this->name = $name;
        $this->referentTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCategory(): ?ZoneCategory
    {
        return $this->category;
    }

    public function setCategory(ZoneCategory $category): void
    {
        $this->category = $category;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
