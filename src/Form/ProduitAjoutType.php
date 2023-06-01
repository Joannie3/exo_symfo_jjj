<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitAjoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' => [
                    'class=' => 'form-control',
                ],
                'label' => 'Nom du produit',
                'label_attr' => [
                    'class' => 'control-label',
                ]
            ])
            ->add('description', TextType::class,[
                'attr' => [
                    'class=' => 'form-control',
                ],
                'label' => 'Description du produit',
                'label_attr' => [
                    'class' => 'control-label',
                ]
            ])
            ->add('age_consultation', ChoiceType::class, [
                'choices' => [
                        'Pas d\'age minimum requis' => '0',
                        'Plus de 18 ans' => '18',
                ]
                ])
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
