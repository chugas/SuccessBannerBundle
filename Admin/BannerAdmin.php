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
            ->add('active')
            ->add('in_new_window')
            ->add('image')
            //->add('start_date')
            //->add('end_date')
            //->add('html')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $options = array('required' => false);
        $options['data_class'] = null;
        if (($subject = $this->getSubject()) && $subject->getImage()) {
          $path = $subject->getWebPath();
          $options['help'] = '<img src="' . $path . '" width="290" />';      
        }
        
        $formMapper
            ->add('place', 'sonata_type_translatable_choice', array(
                'choice_list' => new SimpleChoiceList(Banner::getPlacesList()),
            ))
            ->add('link')
            ->add('active')
            ->add('in_new_window')                
            ->add('file', 'file', $options)
            //->add('start_date')
            //->add('end_date')
            ->add('html')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('place')
            ->add('link')
            ->add('active', null, array('editable' => true))
            ->add('in_new_window', null, array('editable' => true))                
            //->add('start_date')
            //->add('end_date')
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
            ->add('active')
            ->add('in_new_window')                
            //->add('start_date')
            //->add('end_date')
            //->add('html')
        ;
    }

}
