<?php

namespace App\Form;

use App\Entity\Project;
use App\Form\AttachmentType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('introduction')
            ->add('content', CKEditorType::class)
            ->add('imageFile', VichImageType::class, ['label' => false])
            ->add('attachments', CollectionType::class, [
                'entry_type' => AttachmentType::class,
                // 'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'label' => false,
                // 'prototype' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}