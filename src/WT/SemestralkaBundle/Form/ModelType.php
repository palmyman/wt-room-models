<?php

namespace WT\SemestralkaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author')
            ->add('description')
            ->add('room', 'entity', array('class'=>'WTSemestralkaBundle:Room', 'property'=>'name'))
            ->add('file')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WT\SemestralkaBundle\Entity\Model'
        ));
    }

    public function getName()
    {
        return 'wt_semestralkabundle_modeltype';
    }
}
