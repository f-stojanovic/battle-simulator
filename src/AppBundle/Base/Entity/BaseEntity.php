<?php
namespace AppBundle\Base\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Exception\MappingException;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class BaseEntity {

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @Groups({"basic_data", "minimal_data"})
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     * @Groups({"basic_data", "minimal_data"})
     */
    private $modified;

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     * @return BaseEntity
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param \DateTime $modified
     * @return BaseEntity
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCreated() == null)
        {
            $this->setCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    public function getEntityName($entity)
    {
        try {
            $entityName = $this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();
        } catch (MappingException $e) {
            throw new \Exception('Given object ' . get_class($entity) . ' is not a Doctrine Entity. ');
        }

        return $entityName;
    }

    protected function uploadPath($file)
    {
        $className =  explode('\\',  get_class($this));

        $className = $className[3];

        return '/uploads/' . $className . '/' . $file;
    }
}
