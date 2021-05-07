<?php


namespace App\Form;


use App\Entity\TrickImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickFormSingleImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path',
                FileType::class, [
                    'label' => 'Ajouter / Remplacer une image',
                    'required' => true,
                    'constraints' => [
                        new Image([
                            'maxSize' => '2M',
                            'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximum autorisé est de {{ limit }} {{ suffix }}'
                        ]),
                        new NotBlank([
                            'message' => 'Veuillez renseigner un fichier pour cette image',
                        ])
                    ]
                ])
            ->add('id', HiddenType::class, [
                'data_class' => null,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickImage::class,
        ]);
    }
}