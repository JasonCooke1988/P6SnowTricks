<?php


namespace App\Form;


use App\Entity\Group;
use App\Entity\Trick;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', null, [
                'label' => 'Nom de la figure',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom pour la figure',
                    ])
                ]
            ])
            ->add('description', null, [
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
            ->add('mainImage',
                FileType::class, [
                    'label' => 'Image à afficher à la une de la figure',
                    'data_class' => null,
                    'constraints' => [
                        new Image([
                            'maxSize' => '2M',
                            'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximum autorisé est de {{ limit }} {{ suffix }}'
                        ])
                    ]
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }

}