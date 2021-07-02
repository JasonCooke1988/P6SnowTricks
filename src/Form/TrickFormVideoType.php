<?php


namespace App\Form;


use App\Entity\TrickVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickFormVideoType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('embed', TextareaType::class, [
                'label' => 'Copier le code \'embed\' récupéré depuis youtube pour la vidéo que vous souhaitez ajouter / modifier :',
                'required' => true
            ])
            ->add('id', HiddenType::class, [
                'data_class' => null,
                'required' => false,
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickVideo::class,
        ]);
    }
}