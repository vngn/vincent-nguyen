<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'attr' => ['class' => 'title-form']
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégorie :',
                'attr' => ['class' => 'categories-form']
            ])
            ->add('firstcontent', CKEditorType::class, ['label' => 'Amorce de contenu :'])
            ->add('content', CKEditorType::class, ['label' => 'Contenu :'])
            ->add('Valider', SubmitType::class, ['attr' => ['class' => 'btn-submit-form']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
