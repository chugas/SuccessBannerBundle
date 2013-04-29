<?php

namespace Success\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Success\BannerBundle\Entity\Banner
 *
 * @ORM\Table(name="success_banner")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Success\BannerBundle\Entity\Repository\BannerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Banner
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $place
     *
     * @ORM\Column(name="place", type="string", length=255)
     */
    private $place;

    /**
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string $link
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $link;

    /**
     * @Assert\Image(maxSize="6000000")
     */
    private $file;

    /**
     * @var \DateTime $start_date
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start_date;

    /**
     * @var \DateTime $end_date
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end_date;

    /**
     * @var string $html
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $html;

    public function __toString()
    {
        return (string)$this->getPlace();
    }

    function __construct()
    {
        $this->start_date = new \DateTime();
        $this->end_date = new \DateTime('tomorrow 23:59:00');
    }

    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir() . '/' . $this->image;
    }

    public function getWebPath()
    {
        return null === $this->image ? null : $this->getUploadDir() . '/' . $this->image;
    }

    protected function getUploadRootDir()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/images/banners';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $this->image = uniqid() . '.' . $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does automatically
        $this->file->move($this->getUploadRootDir(), $this->image);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (!$file = $this->getAbsolutePath()) {
            return;
        }
        if (is_file($file)) {
            unlink($file);
        }
    }

    public static function getPlacesList()
    {
        return array(
            'Main_horizontal' => 'Main_horizontal'
        );
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
     * Set place
     *
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set image
     *
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        if (!empty($file)) {
            $this->image = 'changed';
        }
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set html
     *
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * Get html
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

}
