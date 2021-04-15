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

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @todo    Set some options for each fields (label, help ...).
 *
 * @author  Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>
 */
class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('poster')
            ->add('country')
            ->add('rated')
            ->add('runtime')
            ->add(
                'released',
                DateType::class,
                [
                    'input' => 'datetime_immutable',
                    'widget' => 'single_text',
                    'years' => range(date('Y'), date('Y') - 200),
                ]
            )
            ->add('globalRatingValue', NumberType::class)
            ->add('price', MoneyType::class, ['currency' => 'USD'])
            ->add('description')
            ->add('directors')
            ->add('writers')
            ->add('actors')
            ->add('awards')
            ->add('production')
            ->add('ratings')
            ->add('trailers')
            ->add('photos')
            ->add(
                'genres',
                EntityType::class,
                [
                    'expanded' => false,
                    'multiple' => true,
                    'class' => Genre::class,
                    'choice_label' => 'name',
                    'label' => 'Genres',
                    'help' => 'You can select multiple choices.',
                    'query_builder' => static function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC')
                        ;
                    },
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Movie::class,
                'translation_domain' => 'forms',
                'attr' => ['id' => 'movie-form'],
            ]
        );
    }
}
