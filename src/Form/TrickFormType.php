<?php


namespace App\Form;


use App\Entity\Group;
use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la figure',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom pour la figure',
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la figure',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner une description pour la figure',
                    ])
                ]
            ])
            ->add('group', EntityType::class, [
                'class' => Group::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Le groupe de la figure',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un groupe pour la figure',
                    ])
                ]
            ])
            ->add('mainImageFile',
                FileType::class, [
                    'label' => 'Image à afficher à la une de la figure',
                    'required' => false,
                    'data_class' => null,
                    'constraints' => [
                        new Image([
                            'maxSize' => '2M',
                            'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximum autorisé est de {{ limit }} {{ suffix }}'
                        ])
                    ]
                ])
            ->add('trickVideos', CollectionType::class, [
                'entry_type' => TrickFormVideoType::class,
                'by_reference' => false,
                'label' => 'Copier le code \'embed\' pour la vidéo à ajouter / modifier :',
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ])
            ->add('trickImages', CollectionType::class, [
                'entry_type' => TrickFormSingleImageType::class,
                'by_reference' => false,
                'label' => 'Ajouter une image',
                'required' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }

}