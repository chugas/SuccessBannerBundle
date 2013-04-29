<?php

namespace Success\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="success_banner_log")
 * @ORM\Entity()
 */
class BannerLog
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Success\BannerBundle\Entity\Banner", cascade={"all"})
     */
    protected $banner;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @ORM\Column(type="integer")
     */
    protected $views;

    /**
     * @ORM\Column(type="integer")
     */
    protected $clicks;

    function __construct($banner)
    {
        $this->banner = $banner;
        $this->date = new \DateTime();
        $this->views = 1;
        $this->clicks = 0;
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
     * Set date
     *
     * @param \DateTime $date
     * @return BannerLog
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return BannerLog
     */
    public function setViews($views)
    {
        $this->views = $views;
    
        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return BannerLog
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
    
        return $this;
    }

    /**
     * Get clicks
     *
     * @return integer 
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set banner
     *
     * @param \Success\BannerBundle\Entity\Banner $banner
     * @return BannerLog
     */
    public function setBanner(\Success\BannerBundle\Entity\Banner $banner = null)
    {
        $this->banner = $banner;
    
        return $this;
    }

    /**
     * Get banner
     *
     * @return \Success\BannerBundle\Entity\Banner 
     */
    public function getBanner()
    {
        return $this->banner;
    }
}