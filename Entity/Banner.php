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
     * @var string $active
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active;
    
    /**
     * @var string $in_new_window
     *
     * @ORM\Column(name="in_new_window", type="boolean", nullable=true)
     */
    private $in_new_window;
    
    /**
     * @var string $position
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;    

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
        $this->start_date = NULL;//new \DateTime();
        $this->end_date = NULL;//new \DateTime('tomorrow 23:59:00');
    }

    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir() . '/' . $this->image;
    }

    public function getWebPath()
    {
        return null === $this->image ? null : '/' . $this->getUploadDir() . '/' . $this->image;
    }

    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/banners';
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
            'simple' => 'Simple',
            'carousel' => 'Carrousel'
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


    /**
     * Set active
     *
     * @param boolean $active
     * @return Banner
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set in_new_window
     *
     * @param boolean $inNewWindow
     * @return Banner
     */
    public function setInNewWindow($inNewWindow)
    {
        $this->in_new_window = $inNewWindow;
    
        return $this;
    }

    /**
     * Get in_new_window
     *
     * @return boolean 
     */
    public function getInNewWindow()
    {
        return $this->in_new_window;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Banner
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }
}