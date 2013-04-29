<?php

namespace Success\BannerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Success\BannerBundle\Entity\Banner;

class BannerAdmin extends Admin
{
    protected $translationDomain = 'SonataAdminBundle';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('place')
            ->add('link')
            ->add('image')
            ->add('start_date')
            ->add('end_date')
            ->add('html')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('place', 'sonata_type_translatable_choice', array(
                'choice_list' => new SimpleChoiceList(Banner::getPlacesList()),
            ))
            ->add('link')
            ->add('file', 'file', array('required' => false))
            ->add('start_date')
            ->add('end_date')
            ->add('html')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('place')
            ->add('link')
            ->add('start_date')
            ->add('end_date')
            ->add('stats', null, array('template' => 'SuccessBannerBundle:Banner:stats_partial.html.twig'))
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('place', null, array(), 'sonata_type_translatable_choice', array(
                'choice_list' => new SimpleChoiceList(Banner::getPlacesList()),
                    )
            )
            ->add('link')
            ->add('start_date')
            ->add('end_date')
            ->add('html')
        ;
    }

}
