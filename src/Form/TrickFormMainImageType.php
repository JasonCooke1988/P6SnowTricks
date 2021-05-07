<?php


namespace App\Form;


use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class TrickFormMainImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mainImage',
                FileType::class, [
                    'label' => 'Image à afficher à la une de la figure',
                    'required' => true,
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