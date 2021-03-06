<?php

namespace CftfBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * LsItem
 *
 * @ORM\Table(name="ls_item")
 * @ORM\Entity(repositoryClass="CftfBundle\Repository\LsItemRepository")
 * @UniqueEntity("uri")
 *
 * @Serializer\VirtualProperty(
 *     "uri",
 *     exp="service('salt.api.v1p1.utils').getApiUrl(object)",
 *     options={
 *         @Serializer\SerializedName("uri"),
 *         @Serializer\Expose()
 *     }
 * )
 *
 * @Serializer\VirtualProperty(
 *     "cfDocumentUri",
 *     exp="service('salt.api.v1p1.utils').getLinkUri(object.getLsDoc())",
 *     options={
 *         @Serializer\SerializedName("CFDocumentURI"),
 *         @Serializer\Expose()
 *     }
 * )
 *
 * @Serializer\VirtualProperty(
 *     "cfItemType",
 *     exp="object.getItemType()?object.getItemType().getTitle():null",
 *     options={
 *         @Serializer\SerializedName("CFItemType"),
 *         @Serializer\Expose()
 *     }
 * )
 *
 * @Serializer\VirtualProperty(
 *     "cfItemTypeUri",
 *     exp="service('salt.api.v1p1.utils').getLinkUri(object.getItemType())",
 *     options={
 *         @Serializer\SerializedName("CFItemTypeURI"),
 *         @Serializer\Expose()
 *     }
 * )
 *
 * @Serializer\VirtualProperty(
 *     "conceptKeywords",
 *     exp="service('salt.api.v1p1.utils').splitByComma(object.getConceptKeywords())",
 *     options={
 *         @Serializer\SerializedName("conceptKeywords"),
 *         @Serializer\Expose()
 *     }
 * )
 *
 * @Serializer\VirtualProperty(
 *     "conceptKeywordsUri",
 *     exp="(object.getConcepts().count()===0)?null:service('salt.api.v1p1.utils').getLinkUri(object.getConcepts()[0])",
 *     options={
 *         @Serializer\SerializedName("conceptKeywordsURI"),
 *         @Serializer\Expose()
 *     }
 * )
 *
 * @Serializer\VirtualProperty(
 *     "educationLevel",
 *     exp="service('salt.api.v1p1.utils').splitByComma(object.getEducationalAlignment())",
 *     options={
 *         @Serializer\SerializedName("educationLevel"),
 *         @Serializer\Expose()
 *     }
 * )
 *
 * @Serializer\VirtualProperty(
 *     "licenseUri",
 *     exp="service('salt.api.v1p1.utils').getLinkUri(object.getLicence())",
 *     options={
 *         @Serializer\SerializedName("licenseURI"),
 *         @Serializer\Expose()
 *     }
 * )
 */
class LsItem implements CaseApiInterface, IdentifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Exclude()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=300, nullable=true, unique=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=300)
     *
     * @Serializer\Exclude()
     */
    private $uri;

    /**
     * @var string
     *
     * @ORM\Column(name="ls_doc_identifier", type="string", length=300, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=300)
     *
     * @Serializer\Exclude()
     */
    private $lsDocIdentifier;

    /**
     * @var string
     *
     * @ORM\Column(name="ls_doc_uri", type="string", length=300, nullable=true)
     * @Assert\Length(max=300)
     *
     * @Serializer\Exclude()
     */
    private $lsDocUri;

    /**
     * @var LsDoc
     *
     * @ORM\ManyToOne(targetEntity="CftfBundle\Entity\LsDoc", inversedBy="lsItems")
     * @Assert\NotBlank()
     *
     * @Serializer\Exclude()
     */
    private $lsDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="human_coding_scheme", type="string", length=50, nullable=true)
     *
     * @Assert\Length(max=50)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("humanCodingScheme")
     */
    private $humanCodingScheme;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=300, nullable=false, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("identifier")
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="list_enum_in_source", type="string", length=20, nullable=true)
     *
     * @Assert\Length(max=20)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("listEnumeration")
     */
    private $listEnumInSource;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="bigint", nullable=true)
     *
     * @Serializer\Exclude()
     */
    private $rank;

    /**
     * @var string
     *
     * @ORM\Column(name="full_statement", type="text", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("fullStatement")
     */
    private $fullStatement;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviated_statement", type="string", length=50, nullable=true)
     *
     * @Assert\Length(max=50)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("abbreviatedStatement")
     */
    private $abbreviatedStatement;

    /**
     * @var string
     *
     * @ORM\Column(name="concept_keywords", type="string", length=300, nullable=true)
     *
     * @Assert\Length(max=300)
     *
     * @Serializer\Exclude()
     */
    private $conceptKeywords;

    /**
     * @var string
     *
     * @ORM\Column(name="concept_keywords_uri", type="string", length=300, nullable=true)
     *
     * @Assert\Length(max=300)
     * @Assert\Url()
     *
     * @Serializer\Exclude()
     */
    private $conceptKeywordsUri;

    /**
     * @var LsDefConcept[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="CftfBundle\Entity\LsDefConcept")
     * @ORM\JoinTable(name="ls_item_concept",
     *      joinColumns={@ORM\JoinColumn(name="ls_item_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="concept_id", referencedColumnName="id")}
     * )
     *
     * @Serializer\Exclude()
     * @Serializer\SerializedName("conceptKeywords")
     * @Serializer\Type("array<string>")
     */
    private $concepts;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("notes")
     */
    private $notes;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=10, nullable=true)
     *
     * @Assert\Length(max=10)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("language")
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="educational_alignment", type="string", length=300, nullable=true)
     *
     * @Assert\Length(max=300)
     *
     * @Serializer\Exclude()
     */
    private $educationalAlignment;

    /**
     * @var LsDefItemType
     *
     * @ORM\ManyToOne(targetEntity="CftfBundle\Entity\LsDefItemType")
     * @ORM\JoinColumn(name="item_type_id", referencedColumnName="id")
     *
     * @Serializer\Exclude()
     */
    private $itemType;

    /**
     * @var string
     *
     * @ORM\Column(name="alternative_label", type="string", length=255, nullable=true)
     *
     * @Assert\Length(max=255)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("alternativeLabel")
     */
    private $alternativeLabel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="status_start", type="date", nullable=true)
     *
     * @Assert\Date()
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("statusStartDate")
     * @Serializer\AccessType("public_method")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private $statusStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="status_end", type="date", nullable=true)
     *
     * @Assert\Date()
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("statusEndDate")
     * @Serializer\AccessType("public_method")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    private $statusEnd;

    /**
     * @var LsDefLicence
     *
     * @ORM\ManyToOne(targetEntity="CftfBundle\Entity\LsDefLicence")
     * @ORM\JoinColumn(name="licence_id", referencedColumnName="id", nullable=true)
     *
     * @Serializer\Exclude()
     */
    private $licence;

    /**
     * @var string
     *
     * @ORM\Column(name="licence_uri", type="string", length=300, nullable=true)
     *
     * @Assert\Length(max=300)
     * @Assert\Url()
     *
     * @Serializer\Exclude()
     * @Serializer\SerializedName("CFLicenseURI")
     */
    private $licenceUri;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="changed_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     *
     * @Assert\DateTime()
     *
     * @Serializer\Exclude()
     */
    private $changedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", columnDefinition="DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL")
     * @Gedmo\Timestampable(on="update")
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("lastChangeDateTime")
     */
    private $updatedAt;

    /**
     * @var array
     *
     * @ORM\Column(name="extra", type="json_array", nullable=true)
     *
     * @Serializer\Exclude()
     */
    private $extra;

    /**
     * @var Collection|LsAssociation[]
     *
     * @ORM\OneToMany(targetEntity="CftfBundle\Entity\LsAssociation", mappedBy="originLsItem", indexBy="id", cascade={"persist"})
     *
     * @Serializer\Exclude()
     */
    private $associations;

    /**
     * @var Collection|LsAssociation[]
     *
     * @ORM\OneToMany(targetEntity="CftfBundle\Entity\LsAssociation", mappedBy="destinationLsItem", indexBy="id", cascade={"persist"})
     *
     * @Serializer\Exclude()
     */
    private $inverseAssociations;

    /**
     * @var Collection|CfRubricCriterion[]
     *
     * @ORM\OneToMany(targetEntity="CftfBundle\Entity\CfRubricCriterion", mappedBy="item")
     *
     * @Serializer\Exclude()
     */
    private $criteria;


    /**
     * LsItem constructor.
     *
     * @param string|Uuid|null $identifier
     */
    public function __construct($identifier = null)
    {
        if ($identifier instanceof Uuid) {
            $identifier = strtolower($identifier->toString());
        } elseif (is_string($identifier) && Uuid::isValid($identifier)) {
            $identifier = strtolower(Uuid::fromString($identifier)->toString());
        } else {
            $identifier = Uuid::uuid1()->toString();
        }

        $this->identifier = $identifier;
        $this->uri = 'local:'.$this->identifier;
        $this->children = new ArrayCollection();
        $this->lsItemParent = new ArrayCollection();
        $this->associations = new ArrayCollection();
        $this->inverseAssociations = new ArrayCollection();
        $this->updatedAt = new \DateTime();
        $this->changedAt = $this->updatedAt;
    }

    /**
     * Representation of this item as a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->uri;
    }

    /**
     * Clone the LsItem - Do not carry over any associations
     */
    public function __clone()
    {
        // Clear values for new item
        $this->id = null;
        $this->children = new ArrayCollection();
        $this->lsItemParent = new ArrayCollection();
        $this->associations = new ArrayCollection();
        $this->inverseAssociations = new ArrayCollection();

        // Generate a new identifier
        $identifier = Uuid::uuid1()->toString();
        $this->identifier = $identifier;
        $this->uri = 'local:'.$this->identifier;

        // Set last change/update to now
        $this->updatedAt = new \DateTime();
        $this->changedAt = $this->updatedAt;
    }

    /**
     * Create a copy of the lsItem into a new document
     *
     * @param LsDoc $newLsDoc
     * @param LsDefAssociationGrouping|null $assocGroup
     *
     * @return LsItem
     */
    public function copyToLsDoc(LsDoc $newLsDoc, ?LsDefAssociationGrouping $assocGroup = null): LsItem
    {
        $newItem = clone $this;

        $newItem->setLsDoc($newLsDoc);

        // Add an "Exact" relationship to the original
        $exactMatch = new LsAssociation();
        $exactMatch->setLsDoc($newLsDoc);
        $exactMatch->setOrigin($newItem);
        $exactMatch->setType(LsAssociation::EXACT_MATCH_OF);
        $exactMatch->setDestination($this);

        // PW: set assocGroup if provided and non-null
        // TODO: should the assocGroup be on both associations, or just the first association, or just the inverse association??
        if (null !== $assocGroup) {
            $exactMatch->setGroup($assocGroup);
        }

        $newItem->addAssociation($exactMatch);
        $this->addInverseAssociation($exactMatch);

        foreach ($this->getChildren() as $child) {
            $newChild = $child->copyToLsDoc($newLsDoc, $assocGroup);
            $newItem->addChild($newChild, $assocGroup);
        }

        return $newItem;
    }

    /**
     * Create a duplicate of the lsItem into a new document
     *
     * @param LsDoc $newLsDoc
     * @param LsDefAssociationGrouping|null $assocGroup
     *
     * @return LsItem
     */
    public function duplicateToLsDoc(LsDoc $newLsDoc, ?LsDefAssociationGrouping $assocGroup = null): LsItem
    {
        $newItem = clone $this;
        $newItem->setLsDoc($newLsDoc);

        foreach ($this->getAssociations() as $association) {
            if (LsAssociation::CHILD_OF === $association->getType()) {
                continue;
            }

            $newAssoc = $newLsDoc->createAssociation();
            $newAssoc->setOrigin($newItem);
            $newAssoc->setType($association->getType());
            $newAssoc->setDestination($association->getDestination(), $association->getDestinationNodeIdentifier());
            $newItem->addAssociation($newAssoc);
        }

        foreach ($this->getChildren() as $child) {
            $newChild = $child->duplicateToLsDoc($newLsDoc, $assocGroup);
            $newItem->addChild($newChild, $assocGroup);
        }

        return $newItem;
    }

    public function isLsItem(): bool
    {
        return true;
    }

    public function getGroupedAssociations()
    {
        /** @var Collection $groups[] */
        $groups = [
//            'Children' => $this->getChildren(),
//            'Parent' => $this->getLsItemParent(),
        ];

//        $topItems = $this->getTopItemOf();
//        foreach ($topItems as $item) {
//            $groups['Parent']->add($item);
//        }
//        if ($groups['Parent']->isEmpty()) {
//            $groups['Parent']->add($this->getLsDoc());
//        }

        $typeList = LsAssociation::allTypes();
        foreach ($typeList as $type) {
            $groups[$type] = new ArrayCollection();
            $assocName = LsAssociation::inverseName($type);
            if (null === $assocName) {
                $assocName = 'Inverse '.$type;
            }
            $groups[$assocName] = new ArrayCollection();
        }

        $associations = $this->getAssociations();
        foreach ($associations as $association) {
            /** @var LsAssociation $association */
            if ($association->getLsDoc()->getId() !== $this->getLsDoc()->getId()) {
                continue;
            }
            $groups[$association->getType()]->add($association);
        }

        $associations = $this->getInverseAssociations();
        foreach ($associations as $association) {
            /** @var LsAssociation $association */
            /* Commented out to show relations from other docs
            if ($association->getLsDoc()->getId() !== $this->getLsDoc()->getId()) {
                continue;
            }
            */
            $assocName = LsAssociation::inverseName($association->getType());
            if (null === $assocName) {
                $assocName = 'Inverse '.$association->getType();
            }

            $groups[$assocName]->add($association);
        }

        return $groups;
    }

    /**
     * Get a representation of the item
     *
     * @return string
     */
    public function getDisplayIdentifier(): string
    {
        if ($this->humanCodingScheme) {
            return $this->getHumanCodingScheme();
        }

        if ($this->abbreviatedStatement) {
            return $this->abbreviatedStatement;
        }

        if ($this->fullStatement) {
            return $this->fullStatement;
        }

        $uri = $this->getUri();
        $uri = preg_replace('#^.*/#', '', $uri);
        $uri = preg_replace('#^local:#', '', $uri);

        return $uri;
    }

    /**
     * Get a short version of the statement
     *
     * @return string
     */
    public function getShortStatement(): string
    {
        if ($this->abbreviatedStatement) {
            return $this->getAbbreviatedStatement();
        }

        return substr($this->getFullStatement(), 0, 50);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set uri
     *
     * @param string $uri
     *
     * @return LsItem
     */
    public function setUri(?string $uri): LsItem
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * Set lsDocUri
     *
     * @param string $lsDocUri
     *
     * @return LsItem
     */
    public function setLsDocUri(?string $lsDocUri): LsItem
    {
        $this->lsDocUri = $lsDocUri;

        return $this;
    }

    /**
     * Get lsDocUri
     *
     * @return string
     */
    public function getLsDocUri(): ?string
    {
        return $this->lsDocUri;
    }

    /**
     * Set humanCodingScheme
     *
     * @param string $humanCodingScheme
     *
     * @return LsItem
     */
    public function setHumanCodingScheme(?string $humanCodingScheme): LsItem
    {
        $this->humanCodingScheme = $humanCodingScheme;

        return $this;
    }

    /**
     * Get humanCodingScheme
     *
     * @return string
     */
    public function getHumanCodingScheme(): ?string
    {
        return $this->humanCodingScheme;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     *
     * @return LsItem
     */
    public function setIdentifier(?string $identifier = null): LsItem
    {
        if (null !== $identifier) {
            // If the identifier is in the form of a UUID then lower case it
            if ($identifier instanceof Uuid) {
                $identifier = strtolower($identifier->serialize());
            } elseif (is_string($identifier) && Uuid::isValid($identifier)) {
                $identifier = Uuid::fromString($identifier);
                $identifier = strtolower($identifier->serialize());
            }
        }

        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    /**
     * Set listEnumInSource
     *
     * @param string $listEnumInSource
     *
     * @return LsItem
     */
    public function setListEnumInSource(?string $listEnumInSource): LsItem
    {
        $this->listEnumInSource = $listEnumInSource;

        return $this;
    }

    /**
     * Get listEnumInSource
     *
     * @return string
     */
    public function getListEnumInSource(): ?string
    {
        return $this->listEnumInSource;
    }

    /**
     * Set fullStatement
     *
     * @param string $fullStatement
     *
     * @return LsItem
     */
    public function setFullStatement(?string $fullStatement): LsItem
    {
        $this->fullStatement = $fullStatement;

        return $this;
    }

    /**
     * Get fullStatement
     *
     * @return string
     */
    public function getFullStatement(): ?string
    {
        return $this->fullStatement;
    }

    /**
     * Set abbreviatedStatement
     *
     * @param string $abbreviatedStatement
     *
     * @return LsItem
     */
    public function setAbbreviatedStatement(?string $abbreviatedStatement): LsItem
    {
        $this->abbreviatedStatement = $abbreviatedStatement;

        return $this;
    }

    /**
     * Get abbreviatedStatement
     *
     * @return string
     */
    public function getAbbreviatedStatement(): ?string
    {
        return $this->abbreviatedStatement;
    }

    /**
     * Set conceptKeywords
     *
     * @param string $conceptKeywords
     *
     * @return LsItem
     */
    public function setConceptKeywords(?string $conceptKeywords): LsItem
    {
        $this->conceptKeywords = $conceptKeywords;

        return $this;
    }

    /**
     * Get conceptKeywords
     *
     * @return string
     */
    public function getConceptKeywords(): ?string
    {
        return $this->conceptKeywords;
    }

    /**
     * Set conceptKeywordsUri
     *
     * @param string $conceptKeywordsUri
     *
     * @return LsItem
     */
    public function setConceptKeywordsUri(?string $conceptKeywordsUri): LsItem
    {
        $this->conceptKeywordsUri = $conceptKeywordsUri;

        return $this;
    }

    /**
     * Get conceptKeywordsUri
     *
     * @return string
     */
    public function getConceptKeywordsUri(): ?string
    {
        return $this->conceptKeywordsUri;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return LsItem
     */
    public function setNotes(?string $notes): LsItem
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * Set educationalAlignment
     *
     * @param string $educationalAlignment
     *
     * @return LsItem
     */
    public function setEducationalAlignment(?string $educationalAlignment): LsItem
    {
        $this->educationalAlignment = $educationalAlignment;

        return $this;
    }

    /**
     * Get educationalAlignment
     *
     * @return string
     */
    public function getEducationalAlignment(): ?string
    {
        return $this->educationalAlignment;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): ?string
    {
        $itemType = $this->itemType;
        if (null !== $itemType) {
            return $itemType->getTitle();
        }

        return null;
    }

    /**
     * Set licenceUri
     *
     * @param string $licenceUri
     *
     * @return LsItem
     */
    public function setLicenceUri(?string $licenceUri): LsItem
    {
        $this->licenceUri = $licenceUri;

        return $this;
    }

    /**
     * Get licenceUri
     *
     * @return string
     */
    public function getLicenceUri(): ?string
    {
        return $this->licenceUri;
    }

    /**
     * Set changedAt
     *
     * @param \DateTime $changedAt
     *
     * @return LsItem
     */
    public function setChangedAt(\DateTime $changedAt): LsItem
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    /**
     * Get changedAt
     *
     * @return \DateTime
     */
    public function getChangedAt(): \DateTime
    {
        return $this->changedAt;
    }

    /**
     * Add child
     *
     * @param LsItem $child
     * @param LsDefAssociationGrouping|null $assocGroup
     * @param int|null $sequenceNumber
     *
     * @return LsAssociation
     */
    public function createChildItem(LsItem $child, ?LsDefAssociationGrouping $assocGroup = null, ?int $sequenceNumber = null): LsAssociation
    {
        $association = new LsAssociation();
        $association->setLsDoc($child->getLsDoc());
        $association->setOrigin($child);
        $association->setType(LsAssociation::CHILD_OF);
        $association->setDestination($this);
        if (null !== $sequenceNumber) {
            $association->setSequenceNumber($sequenceNumber);
        }

        // PW: set assocGroup if provided and non-null
        if ($assocGroup !== null) {
            $association->setGroup($assocGroup);
        }

        $child->addAssociation($association);
        $this->addInverseAssociation($association);

        return $association;
    }

    /**
     * Add child
     *
     * @param LsItem $child
     * @param LsDefAssociationGrouping|null $assocGroup
     *
     * @return LsItem
     */
    public function addChild(LsItem $child, ?LsDefAssociationGrouping $assocGroup = null): LsItem
    {
        $this->createChildItem($child, $assocGroup);

        return $this;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection|LsItem[]
     */
    public function getChildren(): Collection
    {
        $children = new ArrayCollection();

        $associations = $this->getInverseAssociations();
        foreach ($associations as $association) {
            /** @var LsAssociation $association */
            if ($association->getType() === LsAssociation::CHILD_OF) {
                $children->add($association->getOriginLsItem());
            }
        }

        return $children;
    }

    /**
     * Get children ids
     *
     * @return array|int[]
     */
    public function getChildIds(): array
    {
        $ids = $this->getChildren()->map(
            function (LsItem $item) {
                return $item->getId();
            }
        );

        return $ids->toArray();
    }

    /**
     * Set lsDoc
     *
     * @param \CftfBundle\Entity\LsDoc $lsDoc
     *
     * @return LsItem
     */
    public function setLsDoc(\CftfBundle\Entity\LsDoc $lsDoc = null): LsItem
    {
        $this->lsDoc = $lsDoc;
        $this->lsDocUri = $lsDoc->getUri();
        $this->lsDocIdentifier = $lsDoc->getIdentifier();

        return $this;
    }

    /**
     * Get lsDoc
     *
     * @return \CftfBundle\Entity\LsDoc
     */
    public function getLsDoc(): LsDoc
    {
        return $this->lsDoc;
    }

    /**
     * Get lsItemParent
     *
     * @return \Doctrine\Common\Collections\Collection|LsItem[]
     */
    public function getLsItemParent(): Collection
    {
        $parents = new ArrayCollection();
        $associations = $this->getAssociations();
        foreach ($associations as $association) {
            /** @var LsAssociation $association */
            if ($association->getType() === LsAssociation::CHILD_OF
                && $association->getDestinationLsItem() !== null
            ) {
                $parents->add($association->getDestinationLsItem());
            }
        }

        return $parents;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return LsItem
     */
    public function setUpdatedAt(\DateTime $updatedAt): LsItem
    {
        $this->updatedAt = $updatedAt;

        $this->lsDoc->setUpdatedAt($updatedAt);

        $parents = $this->getLsItemParent();
        foreach ($parents as $parent) {
            $parent->setUpdatedAt($updatedAt);
        }

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Add association
     *
     * @param \CftfBundle\Entity\LsAssociation $association
     *
     * @return LsItem
     */
    public function addAssociation(\CftfBundle\Entity\LsAssociation $association): LsItem
    {
        $this->associations[] = $association;

        return $this;
    }

    /**
     * Remove association
     *
     * @param \CftfBundle\Entity\LsAssociation $association
     */
    public function removeAssociation(\CftfBundle\Entity\LsAssociation $association): LsItem
    {
        $this->associations->removeElement($association);

        return $this;
    }

    /**
     * Get associations
     *
     * @return \Doctrine\Common\Collections\Collection|LsAssociation[]
     */
    public function getAssociations(): Collection
    {
        return $this->associations;
    }

    /**
     * Add inverseAssociation
     *
     * @param \CftfBundle\Entity\LsAssociation $inverseAssociation
     *
     * @return LsItem
     */
    public function addInverseAssociation(\CftfBundle\Entity\LsAssociation $inverseAssociation): LsItem
    {
        $this->inverseAssociations[] = $inverseAssociation;

        return $this;
    }

    /**
     * Remove inverseAssociation
     *
     * @param \CftfBundle\Entity\LsAssociation $inverseAssociation
     */
    public function removeInverseAssociation(\CftfBundle\Entity\LsAssociation $inverseAssociation): LsItem
    {
        $this->inverseAssociations->removeElement($inverseAssociation);

        return $this;
    }

    /**
     * Get inverseAssociations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInverseAssociations(): Collection
    {
        return $this->inverseAssociations;
    }

    /**
     * Get topItemOf
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTopItemOf(): Collection
    {
        $topItemOf = new ArrayCollection();

        $associations = $this->getAssociations();
        foreach ($associations as $association) {
            /** @var LsAssociation $association */
            if ($association->getType() === LsAssociation::CHILD_OF
                && $association->getDestinationLsDoc() !== null
            ) {
                $topItemOf->add($association->getDestinationLsDoc());
            }
        }

        return $topItemOf;
    }

    /**
     * @return LsItem|null
     */
    public function getParentItem(): ?LsItem
    {
        return $this->getLsItemParent()->first();
    }

    /**
     * @return string
     */
    public function getLsDocIdentifier(): ?string
    {
        return $this->lsDocIdentifier;
    }

    /**
     * @param string $lsDocIdentifier
     *
     * @return LsItem
     */
    public function setLsDocIdentifier(?string $lsDocIdentifier): LsItem
    {
        $this->lsDocIdentifier = $lsDocIdentifier;

        return $this;
    }

    /**
     * @return int
     */
    public function getRank(): ?int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     *
     * @return LsItem
     */
    public function setRank(?int $rank): LsItem
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtra(): ?array
    {
        return $this->extra;
    }

    /**
     * @param string $property
     * @param string $default
     *
     * @return mixed
     */
    public function getExtraProperty(string $property, $default = null)
    {
        if (is_null($this->extra)) {
            return $default;
        }

        if (!array_key_exists($property, $this->extra)) {
            return $default;
        }

        return $this->extra[$property];
    }

    /**
     * @param array $extra
     *
     * @return LsItem
     */
    public function setExtra(?array $extra): LsItem
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return LsItem
     */
    public function setExtraProperty(string $property, $value): LsItem
    {
        if (is_null($this->extra)) {
            $this->extra = [];
        }

        $this->extra[$property] = $value;

        return $this;
    }

    /**
     * Add Parent
     *
     * @param LsItem|LsDoc $parent
     * @param int $sequenceNumber
     * @param LsDefAssociationGrouping|null $assocGroup
     *
     * @return LsAssociation inserted association (in case the caller needs to get the id later)
     */
    public function addParent($parent, ?int $sequenceNumber = null, ?LsDefAssociationGrouping $assocGroup = null): LsAssociation
    {
        $association = new LsAssociation();
        $association->setLsDoc($this->getLsDoc());
        $association->setOrigin($this);
        $association->setType(LsAssociation::CHILD_OF);
        $association->setDestination($parent?:$this->lsDoc);

        // PW: set sequenceNumber if provided and non-null
        if ($sequenceNumber !== null) {
            $association->setSequenceNumber($sequenceNumber);
        }

        // PW: set assocGroup if provided and non-null
        if ($assocGroup !== null) {
            $association->setGroup($assocGroup);
        }

        $this->addAssociation($association);

        return $association;
    }

    /**
     * Get the LsItem language
     *
     * @return string
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Set the LsItem language
     *
     * @param string $language
     *
     * @return LsItem
     */
    public function setLanguage($language): LsItem
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get (an indented) label representing this item
     *
     * @param string $indent
     *
     * @return string
     */
    public function getLabel($indent = "\u{00a0}\u{00a0}\u{00a0}\u{00a0}"): string
    {
        $pfx = '';
        $parent = $this->getLsItemParent();
        while (!$parent->isEmpty()) {
            $pfx .= $indent;
            $parent = $parent->current()->getLsItemParent();
        }
        $statement = $this->getShortStatement();
        $code = $this->getHumanCodingScheme();
        if (null !== $code && '' !== $code) {
            $code .= ' - ';
        }

        return "{$pfx}{$code}{$statement}";
    }

    /**
     * Determine if the LsItem is editable
     *
     * @return bool
     */
    public function canEdit(): bool
    {
        return $this->lsDoc->canEdit();
    }

    /**
     * @return LsDefItemType
     */
    public function getItemType(): ?LsDefItemType
    {
        return $this->itemType;
    }

    /**
     * @param LsDefItemType $itemType
     *
     * @return LsItem
     */
    public function setItemType($itemType): LsItem
    {
        $this->itemType = $itemType;

        return $this;
    }

    /**
     * @return LsDefConcept[]|ArrayCollection
     */
    public function getConcepts()
    {
        return $this->concepts;
    }

    /**
     * @param LsDefConcept[]|ArrayCollection $concepts
     *
     * @return LsItem
     */
    public function setConcepts($concepts): LsItem
    {
        $this->concepts = $concepts;

        return $this;
    }

    /**
     * @param LsDefConcept $concept
     *
     * @return LsItem
     */
    public function addConcept(LsDefConcept $concept): LsItem
    {
        $this->concepts[] = $concept;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAlternativeLabel(): ?string
    {
        if (null === $this->alternativeLabel) {
            return $this->getItemType();
        }

        return $this->alternativeLabel;
    }

    /**
     * @param string $alternativeLabel
     *
     * @return LsItem
     */
    public function setAlternativeLabel(?string $alternativeLabel): LsItem
    {
        $this->alternativeLabel = $alternativeLabel;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStatusStart(): ?\DateTime
    {
        if (null === $this->statusStart) {
            return $this->lsDoc->getStatusStart();
        }

        return $this->statusStart;
    }

    /**
     * @param \DateTime $statusStart
     *
     * @return LsItem
     */
    public function setStatusStart(?\DateTime $statusStart): LsItem
    {
        $this->statusStart = $statusStart;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStatusEnd(): ?\DateTime
    {
        if (null === $this->statusEnd) {
            return $this->lsDoc->getStatusEnd();
        }

        return $this->statusEnd;
    }

    /**
     * @param \DateTime $statusEnd
     *
     * @return LsItem
     */
    public function setStatusEnd(?\DateTime $statusEnd): LsItem
    {
        $this->statusEnd = $statusEnd;

        return $this;
    }

    /**
     * @return LsDefLicence|null
     */
    public function getLicence(): ?LsDefLicence
    {
        return $this->licence;
    }

    /**
     * @param LsDefLicence $licence
     *
     * @return LsItem
     */
    public function setLicence(?LsDefLicence $licence): LsItem
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * @return CfRubricCriterion[]|Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param CfRubricCriterion[]|Collection $criteria
     *
     * @return LsItem
     */
    public function setCriteria($criteria): LsItem
    {
        $this->criteria = $criteria;

        return $this;
    }
}
