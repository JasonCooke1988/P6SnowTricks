<?php


namespace App\Form;


use App\Entity\TrickImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrickSingleImageFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path',
                FileType::class, [
                    'label' => 'Ajouter une image',
                    'required' => true,
                    'data_class' => null
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickImage::class,
        ]);
    }

}