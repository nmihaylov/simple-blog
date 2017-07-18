<?php

namespace MainBundle\Form;

use MainBundle\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->add('imageName');
        $builder->add('imageFile', VichImageType::class, [
            'required' => false,
            'allow_delete' => true,
            'delete_label' => 'Delete image?',
            'download_label' => 'Download image',
//            'download_uri' => true,
            'image_uri' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'main_bundle_image_type';
    }
}
