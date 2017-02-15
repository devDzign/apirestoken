<?php

namespace France\TvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoolType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('title', ChoiceType::class, array(
//                'choices'  => array(
//                    'Yes' => 'aaaaa',
//                    'No' => 'bbbbbb',
//                ),
//            ));

         ->add('note', null, array(
             'description' => 'this is a note',
         ));
    }
    

    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'france_tvbundle_cool';
    }
    
    
}
