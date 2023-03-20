<?php

namespace App\Form;

use App\Service\AppService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Languages;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('languages', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'choices' => AppService::LANGUAGES,
                'choice_label' => fn($x) => Languages::getName($x),
                'help' => "comma-delimited list of 2-letter language codes"
            ])
            ->add('all', CheckboxType::class, [
                'required' => false,
                'help' => "if checked, must speak ALL of the languages, otherwise ANY"
            ])
//            ->add('visited', null, [
//                'required' => false,
//                'help' => "comma-delimited list of alpha-2 country codes"
//
//            ])
            ->add('search', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
