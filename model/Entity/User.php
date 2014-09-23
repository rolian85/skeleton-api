<?php
namespace Entity;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Custom\Annotation\UpdateTimezone as UpdateTimezone;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Repository\User")
 * @ORM\Table(name="user", indexes={@ORM\Index(name="token_idx", columns={"token"})})
 */
class User extends BaseEntity implements PersistableBase, Auditable
{
    const HYDRATION_LEVEL_BASIC = 1;
    const HYDRATION_LEVEL_ADVANCED = 2;
    const HYDRATION_LEVEL_ACCOUNT = 3;

    const GENDER_FEMALE = 'F';
    const GENDER_MALE = 'M';

    /**
     * @ORM\Id @ORM\Column(type="string", length=36)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $prefix;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $suffix;

    /** @ORM\Column(type="string", length=20) */
    protected $firstName;

    /** @ORM\Column(type="string", length=20) */
    protected $lastName;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $middleName;

    /** @ORM\Column(type="date", nullable=false) */
    protected $dob;

    /** @ORM\Column(type="string", length=1, nullable=true) */
    protected $gender;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $race;

    /** @ORM\Column(type="string", length=11, nullable=true) */
    protected $ssn;

    /** @ORM\Column(type="string", length=10, nullable=true) */
    protected $driverLicense;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $address1;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $address2;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $address3;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $city;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $state;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $postal;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $birthPlace;

    /**
     * @ORM\ManyToOne(targetEntity="Country", cascade={"all"})
     * @ORM\JoinColumn(name="country", referencedColumnName="iso")
     */
    protected $country;

    /** @ORM\Column(type="string", length=15, nullable=true) */
    protected $phone1;

    /** @ORM\Column(type="string", length=15, nullable=true) */
    protected $phone2;

    /** @ORM\ManyToOne(targetEntity="Document") */
    protected $avatar;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $height;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $weight;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $eyeColor;

    /** @ORM\Column(type="string", length=20,nullable=true) */
    protected $hairColor;

    /**
     * @ORM\Column(type="string", length=50, nullable=true, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string",length=40, nullable=true)
     */
    protected $password;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    protected $email;

    /** @ORM\OneToMany(targetEntity="UsersGroups", mappedBy="user") */
    protected $groups;

    /** @ORM\ManyToOne(targetEntity="Organization") */
    protected $organization;

    /**
     * @ORM\ManyToMany(targetEntity="Document", inversedBy="assignedUsers")
     * @ORM\JoinTable(name="users_documents")
     */
    protected $assignedDocuments;


    /**
     * @ORM\OneToMany(targetEntity="UserChallengeQuestion", mappedBy="user")
     */
    protected $challengeQuestions;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $employeeId;

    /** @ORM\Column(type="string", nullable=true) */
    protected $employmentPosition;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $employeeStatus;

    /** @ORM\Column(type="string", nullable=true) */
    protected $department;

    /** @ORM\Column(type="boolean") */
    protected $status = false;

    /** @ORM\Column(type="boolean") */
    protected $locked = false;

    /** @ORM\Column(type="string", nullable=true) */
    protected $locked_reason;

    /** @ORM\Column(type="string", nullable=true) */
    protected $locked_reason_by;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @UpdateTimezone(field="locked_reason_dateTimezone")
     */
    protected $locked_reason_date;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $locked_reason_dateTimezone;

    /** @ORM\Column(type="date", nullable=true) */
    protected $account_expiration;

    /** @ORM\Column(type="date", nullable=true) */
    protected $password_expiration;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $token;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $tokenExpired;

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
     * @ORM\Column(type="datetime", nullable=true)
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
        $date = new \DateTime;
        $date->add(new \DateInterval('P90D'));
        $this->password_expiration = $date;

        $this->groups = new ArrayCollection();
        $this->assignedDocuments = new ArrayCollection();
        $this->challengeQuestions = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getPrefix() {
        return $this->prefix;
    }

    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function getSuffix() {
        return $this->suffix;
    }

    public function setSuffix($suffix) {
        $this->suffix = $suffix;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getMiddleName() {
        return $this->middleName;
    }

    public function setMiddleName($middleName) {
        $this->middleName = $middleName;
    }

    public function getDob() {
        return $this->dob;
    }

    public function setDob($dob) {
        $this->dob = $dob;
    }

    public function getGender($showFull = true) {
        if ($showFull) {
            switch ($this->gender) {
                case "F":
                    return "female";
                    break;
                case "M":
                    return "male";
                    break;
                default:
                    return "";
                    break;
            }
        } else
            return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getRace() {
        return $this->race;
    }

    public function setRace($race) {
        $this->race = $race;
    }

    public function getSsn() {
        return $this->ssn;
    }

    public function setSsn($ssn) {
        $this->ssn = $ssn;
    }

    public function getDriverLicense() {
        return $this->driverLicense;
    }

    public function setDriverLicense($driverLicense) {
        $this->driverLicense = $driverLicense;
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

    public function getCountry() {
        return $this->country;
    }

    public function getCountryName() {
        return (($country = $this->getCountry()) instanceof \Entity\Country) ? $country->getPrintable_name() : null;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function getPhone1() {
        return $this->phone1;
    }

    public function setPhone1($phone1) {
        $this->phone1 = $phone1;
    }

    public function getPhone2() {
        return $this->phone2;
    }

    public function setPhone2($phone2) {
        $this->phone2 = $phone2;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function getFullName() {
        return trim("{$this->getPrefix()} {$this->getFirstName()} {$this->getLastName()} {$this->getSuffix()}");
    }

    public function getFullNameArranged()
    {
        $sufix = ($this->middleName) ? ', ' . $this->middleName[0].'.' : '';
        return trim("{$this->getLastName()}, {$this->getFirstName()}{$sufix}");
    }

    /** @ORM\Column(type="boolean") */
    protected $isActive = true;


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

    /**
     * @deprecated 
     * backward compatibility* */
    public function getIsDeleted() {
        return $this->deleted;
    }

    /**
     * @deprecated  
     * backward compatibility* */
    public function setIsDeleted($isDeleted) {
        $this->deleted = $isDeleted;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function getAddress3() {
        return $this->address3;
    }

    public function setAddress3($address3) {
        $this->address3 = $address3;
    }

    public function getBirthPlace() {
        return $this->birthPlace;
    }

    public function setBirthPlace($birthPlace) {
        $this->birthPlace = $birthPlace;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getEyeColor() {
        return $this->eyeColor;
    }

    public function setEyeColor($eyeColor) {
        $this->eyeColor = $eyeColor;
    }

    public function getHairColor() {
        return $this->hairColor;
    }

    public function setHairColor($hairColor) {
        $this->hairColor = $hairColor;
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    /**
     * Change user/patient password.
     * Transform plain password to SHA1 string.
     * Move password expiration to the next 90 days
     * @param $password Plain password
     */
    public function setPassword($password) {
        $date = new \DateTime;
        $date->add(new \DateInterval('P90D'));
        $this->password_expiration = $date;
        $this->password = sha1($password);
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getGroups() {
        return $this->groups;
    }

    public function setGroup(UserGroup $group) {
        // old not worked method
        $this->groups->add($group);
        $group->setUsers($this);
    }

    public function addGroup(UserGroup $group) {
        $this->groups->add($group);
        $group->addUser($this);
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getAssignedDocuments() {
        return $this->assignedDocuments;
    }

    public function setAssignedDocuments($assignedDocuments) {
        $this->assignedDocuments = $assignedDocuments;
    }

    public function toArray($level = self::HYDRATION_LEVEL_BASIC) {

        $result = array();
        $result['id'] = $this->getId();

        if($level >= self::HYDRATION_LEVEL_ADVANCED) {
            $country = $this->getCountry();
            if(is_object($country)) {
                $result['country'] = $country->toArray();
            } else {
                $result['country'] = null;
            }

            $organization = $this->getOrganization();
            if(is_object($organization)) {
                $result['organization'] = $organization->toArray();
            } else {
                $result['organization'] = null;
            }
        }

        $result['prefix'] = $this->getPrefix();
        $result['lastName'] = $this->getLastName();
        $result['firstName'] = $this->getFirstName();
        $result['middleName'] = $this->getMiddleName();
        $result['suffix'] = $this->getSuffix();
        $result['fullName'] = $this->getFullNameArranged();
        $result['dob'] = $this->_formatDate($this->getDob());
        $result['gender'] = $this->getGender(false);

        if($level >= self::HYDRATION_LEVEL_ADVANCED) {
            $result['race'] = $this->getRace();
            $result['ssn'] = $this->getSsn();
            $result['driverLicense'] = $this->getDriverLicense();

            $result['address'] = array(
                'line1' => $this->getAddress1(),
                'line2' => $this->getAddress2(),
                'line3' => $this->getAddress3(),
                'city' => $this->getCity(),
                'state' => $this->getState(),
                'postalCode' => $this->getPostal()
            );

            $result['birthPlace'] = $this->getBirthPlace();
            $result['phones'] = array();

            $phone1 = $this->getPhone1();
            if(!empty($phone1)) {
                $result['phones'][] = $phone1;
            }

            $phone2 = $this->getPhone2();
            if(!empty($phone2)) {
                $result['phones'][] = $phone2;
            }

            $result['height'] = $this->getHeight();
            $result['weight'] = $this->getWeight();
            $result['eyeColor'] = $this->getEyeColor();
            $result['hairColor'] = $this->getHairColor();
        }
        $result['username'] = $this->getUsername();
        $result['email'] = $this->getEmail();

        if($level >= self::HYDRATION_LEVEL_ADVANCED) {
            $result['employment'] = array(
                'employeeId' => $this->getEmployeeId(),
                'position' => $this->getEmploymentPosition(),
                'department' => $this->getDepartment(),
                'status' => $this->getEmployeeStatus()
            );
        }

        if($level >= self::HYDRATION_LEVEL_ACCOUNT) {
            $result['account'] = array(
                'expires' => $this->_formatDate($this->getAccount_expiration()),
                'lock' => array(
                    'locked' => $this->isLocked(),
                    'reason' => $this->getLockedReason(),
                    'user' => $this->getLockedReasonBy(),
                    'date' => $this->_formatDate($this->getLockedReasonDate())
                )
            );
            $result['passwordExpires'] = $this->_formatDate($this->getPassword_expiration());
        }

        return $result;
    }

    public function setOrganization($organization) {
        $this->organization = $organization;
    }

    public function getOrganization() {
        return $this->organization;
    }

    public function getChallengeQuestions() {
        return $this->challengeQuestions;
    }

    public function setChallengeQuestions($challengeQuestions) {
        $this->challengeQuestions = $challengeQuestions;
    }

    public function getGroupsArray() {
        $groupIds = array();
        foreach ($this->groups as $group) {
            $groupIds[] = $group->getGroup()->getId();
        }
        return $groupIds;
    }

    public function getEmployeeId() {
        return $this->employeeId;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
    }

    public function auditable() {
        return $this->getId();
    }

    public function getLocked() {
        return $this->locked;
    }

    public function isLocked() {
        return $this->getLocked() == true;
    }

    public function setLocked($locked) {
        $this->locked = $locked;
    }

    public function getLockedReason() {
        return $this->locked_reason;
    }

    public function setLockedReason($locked_reason) {
        $this->locked_reason = $locked_reason;
    }

    public function getLockedReasonBy() {
        return $this->locked_reason_by;
    }

    public function setLockedReasonBy($locked_reason_by) {
        $this->locked_reason_by = $locked_reason_by;
    }

    public function getLockedReasonDate($useTimezone = true) {
        return $this->_getDate($this->locked_reason_date, $this->getLockedReasonDateTimezone(), $useTimezone);
    }

    public function setLockedReasonDate($locked_reason_date) {
        $this->locked_reason_date = $locked_reason_date;
    }

    public function getLockedReasonDateTimezone() {
        return $this->locked_reason_dateTimezone;
    }

    public function getAccount_expiration() {
        return $this->account_expiration;
    }

    public function setAccount_expiration($account_expiration) {
        $this->account_expiration = $account_expiration;
    }

    public function getPassword_expiration() {
        return $this->password_expiration;
    }

    public function setPassword_expiration($password_expiration) {
        $this->password_expiration = $password_expiration;
    }

    public function setEmploymentPosition($employmentPosition) {
        $this->employmentPosition = $employmentPosition;
    }

    public function getEmploymentPosition() {
        return $this->employmentPosition;
    }

    public function setEmployeeStatus($employeeStatus) {
        $this->employeeStatus = $employeeStatus;
    }

    public function getEmployeeStatus() {
        return $this->employeeStatus;
    }

    public function setDepartment($department) {
        $this->department = $department;
    }

    public function getDepartment() {
        return $this->department;
    }

    public function setUpdated(\DateTime $date) {
        $this->updated = $date;
    }

    public function passwordMatches($password, $isEncrypted = false)
    {
        if (!$isEncrypted)
            $password = sha1($password);
        return $password == $this->getPassword();
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setTokenExpired($tokenExpired)
    {
        $this->tokenExpired = $tokenExpired;
    }

    public function getTokenExpired()
    {
        return $this->tokenExpired;
    }

    public function isTokenExpired()
    {
        $expired = $this->getTokenExpired();
        $now = new \DateTime();
        return (is_object($expired)) ? $expired <= $now : true;
    }
}