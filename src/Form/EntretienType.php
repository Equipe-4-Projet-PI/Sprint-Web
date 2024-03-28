<?php

namespace App\Form;

use App\Entity\Condidat;
use App\Entity\Entretien;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntretienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


           
            ->add('type',ChoiceType::class,[
                'choices'=>[
                    'Technique N1'=>'Technique N1',
                    'Technique N2'=>'Technique N2',
                    'Ressources Humaine'=>'Ressources Humaine'
                ],
                'expanded'=>true,
                'multiple'=>false
            ])
            ->add('date')
           
            ->add('Add',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entretien::class,
        ]);
    }
}
