<?php

namespace Success\BannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Success\BannerBundle\Entity\BannerLog;
use Doctrine\ORM\NoResultException;

class BannerController extends Controller
{
    public function viewsAction($place, $filter = null)
    {
        $em = $this->getDoctrine()->getManager();
        $banners = $em->getRepository('SuccessBannerBundle:Banner')->findByPlace($place);

        foreach($banners as $banner){
          $banner_log = $em->getRepository('SuccessBannerBundle:BannerLog')->findOneByBanner($banner->getId());
          if(!$banner_log){
            $banner_log = new BannerLog($banner);
          }

          $banner_log->setViews($banner_log->getViews()+1);

          $em->persist($banner_log);
        }
        $em->flush();

        $parameters = array(
            'banners' => $banners,
            'filter' => $filter,
        );
        
        return $this->render('SuccessBannerBundle:Banner:' . $place . '.html.twig', $parameters);        
    }
    
    public function viewAction($place, $filter = null)
    {
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository('SuccessBannerBundle:Banner')->findOneRandom($place);

        $banner_log = $em->getRepository('SuccessBannerBundle:BannerLog')->findOneByBanner($banner->getId());
        if(!$banner_log){
          $banner_log = new BannerLog($banner);
        }
      
        $banner_log->setViews($banner_log->getViews()+1);
        
        $em->persist($banner_log);
        $em->flush();
        
        $parameters = array(
            'banner' => $banner,
            'filter' => $filter,
        );
        
        return $this->render('SuccessBannerBundle:Banner:view.html.twig', $parameters);        
    }

    public function clickAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository('SuccessBannerBundle:Banner')->find($id);
        $banner_log = $em->getRepository('SuccessBannerBundle:BannerLog')->findOneByBanner($id);
        if(!$banner_log){
          $banner_log = new BannerLog($banner);
        }
        $banner_log->setClicks($banner_log->getClicks()+1);
        $em->persist($banner_log);
        $em->flush();

        return new RedirectResponse($banner->getLink());
    }

    public function statsAction($id)
    {
      
        $em = $this->getDoctrine()->getManager();
        $query = $em
                ->createQuery('SELECT l.views, l.clicks FROM SuccessBannerBundle:BannerLog l WHERE l.banner=:banner GROUP BY l.banner')
                ->setParameter('banner', $id);

        try {
          $stats = $query->getSingleResult(); 
        } catch (NoResultException $e) {
          $stats = false;
        } catch (Exception $e) {
          $stats = false;
        }

        return $this->render('SuccessBannerBundle:Banner:stats.html.twig', array('stats' => $stats));
    }

}
