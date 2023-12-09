<?php

namespace App\Form;

use App\Entity\Seed;
use App\Security\Cryptography\EncryptDecryptManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeedType extends AbstractType
{
    private EncryptDecryptManager $encryptDecryptManager;

    public function __construct(EncryptDecryptManager $encryptDecryptManager)
    {
        $this->encryptDecryptManager = $encryptDecryptManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $encryptDecryptManager = $this->encryptDecryptManager;

        $builder
            ->add('text', TextareaType::class, [
                'attr' => [
                    'rows' => 3,
                    'cols' => 50,
                ],
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($encryptDecryptManager) {
                $form = $event->getForm();

                $plainSeed = $form->getData()->getText();
                $cipherText = $encryptDecryptManager->encryptString($plainSeed);

                $form->getData()->setText($cipherText);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seed::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
