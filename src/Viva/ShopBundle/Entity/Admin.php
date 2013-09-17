<?php

namespace Viva\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;

/**
 * @ORM\Entity
 * @ORM\Table(name="admin")
 */
class Admin implements AdvancedUserInterface, \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
      ) */
    protected $username;

    /**
     * @ORM\Column(type="string", length=40)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $salt;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = $this->generateUniqueString();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Admin
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Admin
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Validation constraints
     * 
     * Set the validation constraints for each property. Assign the properties 
     * to validation groups. This will help us when we need to validate only 
     * some of the entity properties, such as only the username at the "forgot 
     * password" form.
     * 
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new Email(array(
            'groups'=>array('admin_login', 'admin_forgot', 'admin_profile')
        )));
        $metadata->addPropertyConstraint('password', new NotBlank(array(
            'groups'=>array('admin_login', 'admin_profile')
        )));
        $metadata->addPropertyConstraint('name', new NotBlank(array(
            'groups'=>array('admin_profile')
        )));
    }

    public function eraseCredentials()
    {
        
    }

    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    public function unserialize($serialized)
    {
        list (
                $this->id,
                ) = unserialize($serialized);
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Admin
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Admin
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Admin
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return (bool) $this->isActive;
    }

    /**
     * Generate a unique string
     * 
     * This methond is used when the user salt needs to be generated and also, 
     * during the "forgot password" process, to generate new plain text password.
     * 
     * @param int $length If greater than zero, the string will be truncated to 
     * the specified length, starging from the first character.
     * @return string
     */
    public function generateUniqueString($length = -1)
    {
        $str = md5(uniqid(null, true));       
        
        if($length > 0){
            $str = substr($str, 0, $length);
        }
        
        return $str;
    }
}