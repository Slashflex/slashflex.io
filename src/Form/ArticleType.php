<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\FieldType;
use App\Form\ImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('introduction')
            ->add('content', CKEditorType::class)
            ->add('main_image')
            // ->add('content', CollectionType::class, [
            //     'label' => false,
            //     'entry_type' => FieldType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true
            // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
