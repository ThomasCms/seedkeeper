<?php

namespace App\Form;

use App\Entity\User;
use App\Service\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    private Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans('user.password.does_not_match'),
                'required' => true,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('user.password.empty'),
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => $this->translator->trans('user.password.too_short', ['{limit}' => 8]),
                        'max' => 4096,
                    ]),
                ],
                'first_options'  => ['label' => $this->translator->trans('user.password.label')],
                'second_options' => ['label' => $this->translator->trans('user.password.repeat_label')],
            ])
            ->add('locale', ChoiceType::class, [
                'choices'  => [
                    '🇬🇧 English' => 'en',
                    '🇫🇷 Français' => 'fr',
                ]
            ])
            ->add('googleAuthenticatorSecret', CheckboxType::class, [
                'label' => $this->translator->trans('user.2fa'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
