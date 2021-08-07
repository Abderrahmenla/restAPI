<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\UserRepository;

/**
 * @MongoDB\Document(db="documents",
 * collection="users",
 * repositoryClass=UserRepository::class) 
 */
class User implements \JsonSerializable {
    /**
     * @MongoDB\Id
     */
    protected String $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected String $name;
    /**
     * @MongoDB\Field(type="int")
     */
    protected String $age;

    /**
     * @MongoDB\Field(type="string")
     */
    protected String $birthday;
    /**
     * User constructor.
     */
    public function __construct(){}

    /**
     * @param string $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
  
    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) : void
    {
        $this->name = $name;
    }
    /**
     * @return int
     */
    public function getAge() : int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age) : void
    {
        $this->age = $age;
    }    

    /**
     * @param string $birthday
     */
    public function setBirthday($birthday) : void
    {
        $this->birthday = $birthday;
    }   

        /**
     * @return string
     */
    public function getBirthday() : string
    {
       return $this->birthday ;
    }   

  public function jsonSerialize()
  {
    return [
        "id" => $this->getId(),
        "name" => $this->getName(),
        "age" => $this->getAge(),
        "birthday" => $this->getBirthday()
        ];
  }
    /**
     * @MongoDB\PrePersist
     */
    public function validate() : void {
      if(empty($this->getName())){
          throw new \Error("Username cannot be empty");
      }

  }  
}