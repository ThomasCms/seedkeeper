<?php

namespace App\Form;

use App\Entity\User;
use App\Service\LocaleSessionManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType
{
    private LocaleSessionManager $localeSessionManager;
    private RequestStack $requestStack;
    private TranslatorInterface $translator;

    public function __construct(LocaleSessionManager $localeSessionManager, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->localeSessionManager = $localeSessionManager;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $session = $this->requestStack->getSession();

        $builder
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans('user.password.does_not_match'),
                'required' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
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
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($session) {
                $locale = $event->getForm()->getData()->getLocale();

                $this->localeSessionManager->setLocaleInSession($session, $locale);
            })
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
