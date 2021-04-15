<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Name',
                    'attr' => ['placeholder' => 'Your name', 'autofocus' => true],
                    'constraints' => [
                        new NotBlank(['message' => 'Please provide a valid name.']),
                        new Length(['min' => 2, 'max' => 32]),
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email',
                    'attr' => ['placeholder' => 'Your email address'],
                    'constraints' => [
                        new NotBlank(['message' => 'Please provide a valid email.']),
                        new Email(['message' => 'Your email doesn\'t seem to be valid.']),
                        new Length(['min' => 6, 'max' => 64]),
                    ],
                ]
            )
            ->add(
                'subject',
                TextType::class,
                [
                    'label' => 'Subject',
                    'attr' => ['placeholder' => 'Your subject'],
                    'constraints' => [
                        new NotBlank(['message' => 'Please give a subject.']),
                        new Length(['min' => 6, 'max' => 128]),
                    ],
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'label' => 'Message',
                    'attr' => ['placeholder' => 'Your message here'],
                    'constraints' => [
                        new NotBlank(['message' => 'Please write a complete message.']),
                        new Length(['min' => 12, 'max' => 254]),
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['translation_domain' => 'forms', 'attr' => ['id' => 'contact-form']]);
    }
}
