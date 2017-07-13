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
            'attr' => [
                'autofocus' => true,
                'rows' => '1'
            ],
        ]);
        $builder->add('description', null, [
            'attr' => ['rows' => '2']
        ]);
        $builder->add('content', null, [
            'attr' => ['rows' => '10']
        ]);
        $builder->add('tags', null, [
            'required' => false
        ]);

        $builder->add('image', ImageType::class, [
            'required' => false
        ]);
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
