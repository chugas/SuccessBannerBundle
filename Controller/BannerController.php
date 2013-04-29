<?php

namespace Success\BannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Success\BannerBundle\Entity\BannerLog;
use Doctrine\ORM\Query;

class BannerController extends Controller
{

    /**
     * @Template()
     */
    public function viewAction($place, $filter = null)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $banner = $em->getRepository('SuccessBannerBundle:Banner')->findOneRandom($place);

        $query = $em
                ->createQuery('UPDATE SuccessBannerBundle:BannerLog l SET l.views = l.views+1 WHERE l.banner=:banner')
                ->setParameter('banner', $banner->getId());

        if (!$query->execute()) {
            $log = new BannerLog($banner);
            $em->persist($log);
            $em->flush();
        }

        return array(
            'banner' => $banner,
            'filter' => $filter,
        );
    }

    /**
     * @Route("/banner/redirect/{id}", name="banner_redirect")
     */
    public function clickAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $banner = $em->getRepository('SuccessBannerBundle:Banner')->find($id);

        $query = $em
                ->createQuery('UPDATE SuccessBannerBundle:BannerLog l SET l.clicks = l.clicks+1 WHERE l.banner=:banner')
                ->setParameter('banner', $banner->getId());
        $query->execute();

        return new RedirectResponse($banner->getLink());
    }

    /**
     * @Template()
     */
    public function statsAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $query = $em
                ->createQuery('SELECT l.views, l.clicks FROM SuccessBannerBundle:BannerLog l WHERE l.banner=:banner GROUP BY l.banner')
                ->setParameter('banner', $id);
        $stats = $query->execute(null, Query::HYDRATE_ARRAY);
//        $stats = $query->getSingleResult();

        return array('stats' => $stats);
    }

}
