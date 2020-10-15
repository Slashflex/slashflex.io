<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttachmentRepository")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks()
 */
class Attachment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="attachments")
     */
    private $project;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="fileName")
     * 
     * @var File|null
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $fileName;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     * @var DateTime $updatedAt
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * @param File|null $file
     *
     * @return Attachment
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;
        if ($file) {
            $this->updatedAt = new DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $fileName
     *
     * @return Attachment
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set updatedAt
     *
     * @param DateTime $updatedAt
     *
     * @return Attachment
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set project
     * @param Project|null $project
     * @return $this
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }
}