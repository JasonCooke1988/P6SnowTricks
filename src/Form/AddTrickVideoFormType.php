<?php


namespace App\Form;


use App\Entity\TrickVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrickVideoFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('embed', TextareaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickVideo::class,
        ]);
    }
}