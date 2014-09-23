<?php
namespace Entity; use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;

/**
 * @ORM\Entity(repositoryClass="Repository\Company")
 * @ORM\Table(name="company")
 */
class Company extends BaseEntity implements PersistableBase, Auditable {

    const HYDRATION_LEVEL_BASIC = 1;
    const HYDRATION_LEVEL_ADVANCED = 2;

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\Column(type="string", length=255) */
    protected $name;

    /** @ORM\Column(type="integer", length=3, nullable=true) * */
    protected $intId;
    
    /** @ORM\Column(type="string", length=10, nullable=true) * */
    protected $taxId;
    
    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $address1;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $address2;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $city;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $state;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $postal;
    
    /** @ORM\Column(type="string", length=15, nullable=true) */
    protected $mainPhone;
    
    /**
     * @ORM\ManyToOne(targetEntity="Organization", inversedBy="companies")
     */
    protected $organization;

    /** @ORM\OneToMany(targetEntity="DocumentFolder", mappedBy="company") 
     *  @deprecated Replaced by documentFolders in \Entity\Organization - To be removed in 1.0.8
     */
    protected $documentFolders;

    /**
     * -------------------------------------
     * Implementation of PersistableBase
     * -------------------------------------
     */

    /** @ORM\Column(type="boolean") */
    protected $deleted = false;

    public function delete() {
        $this->deleted = true;
    }

    public function isDeleted() {
        return $this->deleted;
    }

    public function setDeleted($deleted) {
        $this->deleted = $deleted;
    }

    /**
     * -------------------------------------
     * TIMESTAMPABLE CODE
     * -------------------------------------
     */

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @UpdateTimezone(field="createdTimeZone")
     */
    protected $created;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $createdTimeZone;

    public function getCreated($useTimezone = true) {
        return $this->_getDate($this->created, $this->getCreatedTimeZone(), $useTimezone);
    }

    /**
     * @var datetime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @UpdateTimezone(field="updatedTimeZone")
     */
    protected $updated;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $updatedTimeZone;

    public function getUpdated($useTimezone = true) {
        return $this->_getDate($this->updated, $this->getUpdatedTimeZone(), $useTimezone);
    }

    public function getCreatedTimeZone() {
        return $this->createdTimeZone;
    }

    public function getUpdatedTimeZone() {
        return $this->updatedTimeZone;
    }

    public function __construct() {
        $this->documentFolders = new ArrayCollection();
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    public function getTaxId() {
        return $this->taxId;
    }

    public function setTaxId($taxId) {
        $this->taxId = $taxId;
    }

    public function getAddress1() {
        return $this->address1;
    }

    public function setAddress1($address1) {
        $this->address1 = $address1;
    }

    public function getAddress2() {
        return $this->address2;
    }

    public function setAddress2($address2) {
        $this->address2 = $address2;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getPostal() {
        return $this->postal;
    }

    public function setPostal($postal) {
        $this->postal = $postal;
    }

    public function getMainPhone() {
        return $this->mainPhone;
    }

    public function setMainPhone($mainPhone) {
        $this->mainPhone = $mainPhone;
    }
    
    public function getOrganization() {
        return $this->organization;
    }

    public function setOrganization($organization) {
        $this->organization = $organization;
    }

    public function toArray($level = self::HYDRATION_LEVEL_BASIC) {
        $result = array();

        $result['id'] = $this->getId();
        $result['name'] = $this->getName();

        if($level >= self::HYDRATION_LEVEL_ADVANCED) {
            $organization = $this->getOrganization();
            if (is_object($organization)) {
                $result['organization'] = $organization->toArray();
            } else {
                $result['organization'] = null;
            }
        }

        $result['address'] = array(
            'line1' => $this->getAddress1(),
            'line2' => $this->getAddress2(),
            'city' => $this->getCity(),
            'state' => $this->getState(),
            'postalCode' => $this->getPostal()
        );

        $result['phone'] = $this->getMainPhone();

        return $result;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    /** @deprecated Replaced by getDocumentFolders() in \Entity\Organization */
    public function getDocumentFolders() {
        return $this->documentFolders;
    }
    
    /** @deprecated Replaced by setDocumentFolders() in \Entity\Organization */
    public function setDocumentFolders($documentFolders) {
        $this->documentFolders = $documentFolders;
    }

    public function setCreated($created) {
        $this->created = $created;
    }

    public function setUpdated($updated) {
        $this->updated = $updated;
    }

    public function getIntId() {
        return $this->intId;
    }

    public function setIntId($intId) {
        $this->intId = $intId;
    }

    public function auditable() {
        return $this->getId();
    }
}