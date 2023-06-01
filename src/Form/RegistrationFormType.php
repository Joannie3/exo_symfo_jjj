<?php

namespace App\Form;

use DateTime;
use App\Entity\User;
use Assert\Callback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-control-label',
            ]                
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter les conditions d\'utilisation',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                // 'mapped' => false,
                // 'attr' => [
                //     'autocomplete' => 'new-password'
                // ],
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                // 'options' => ['attr' => ['class' => 'password-field']],
                'options' => ['attr' => ['class' => 'form-control mb-2'], 'label_attr' => ['class' => 'form-label']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de Passe'],
                'second_options' => ['label' => 'Vérification du mot de passe'],
                'label' => false, 
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de rentrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('rue', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Rue'
            ])
            ->add('cp', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Code postal'
            ])
            ->add('ville', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Ville'
            ])
            ->add('date_naissance', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'label' => 'Date de naissance',
                'required' => true,
                 'constraints' => [
                    new Assert\Callback([
                        // Ici $value prend la valeur du champs que l'on est en train de valider, 
                        // ainsi, pour un champs de type TextType, elle sera de type string.
                        'callback' => static function ($value, ExecutionContextInterface $context) { 
                            
                            $date_actuel = new DateTime();
                            $interval = date_diff($date_actuel, $value);
                            // dd($interval->format('%Y%'));

                            if ($value > $date_actuel){
                                $context
                                ->buildViolation('Vous ne pouvez pas choisir une date de naissance supérieure à la date du jour')
                                ->addViolation()
                            ;
                            }
                            else {
                                if ($interval->format('%Y%') < 15){
                                    $context
                                    ->buildViolation('Vous êtes trop jeune pour vous inscrire')
                                    ->addViolation()
                                ;
                                }
                                else {
                                    return;
                                }
                            }

                            


                            
                            
                                
                            

                        }
                    ])
                 ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
