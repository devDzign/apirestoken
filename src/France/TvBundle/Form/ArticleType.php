<?php

namespace France\TvBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('required' => true))
            ->add('leading', TextType::class, array('required' => false))
            ->add('body', TextType::class, array('required' => false))
            ->add('c', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\ChoiceType'), array_merge(
                array('choices' => array('x' => 'X', 'y' => 'Y', 'z' => 'Z')),
                LegacyFormHelper::isLegacy() ? array() : array('choices_as_values' => true)
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'France\TvBundle\Entity\Article',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'france_tvbundle_article';
    }
    
    
}
