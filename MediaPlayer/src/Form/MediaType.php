<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Media;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FileType::class)
            ->add('description', TextareaType::class)
            #->add('dateCreated')
            ->add('picture', FileType::class)
            #->add('extension')
            ->add('genre', EntityType::class,
                array('class' => Genre::class, 'choice_label' => 'name'))
            #->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
