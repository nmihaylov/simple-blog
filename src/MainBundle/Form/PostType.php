<?php

namespace MainBundle\Form;

use MainBundle\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null, [
            'attr' => ['autofocus' => true],
        ]);
        $builder->add('content', null, [
            'attr' => ['rows' => '10']
        ]);
        $builder->add('publishedAt');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'main_bundle_post_type';
    }
}
