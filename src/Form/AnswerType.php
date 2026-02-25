<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Texte de la réponse',
                'attr' => [
                    'placeholder' => 'Entrez la réponse...',
                    'class' => 'form-control'
                ]
            ])
            ->add('isCorrect', CheckboxType::class, [
                'label' => 'Cette réponse est correcte ?',
                'required' => false,
            ])
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'content',
                'label' => 'Question associée',
                'placeholder' => 'Choisir une question',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}