<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Media;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
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
        $builder->get('name')->addModelTransformer(new CallbackTransformer(
            function ($nameString) {
                if($nameString==null) return $nameString;
                $file = new File('C:\wamp64\www\Repository\MediaPlayer\public\media\FileUpload\\'.$nameString);
                return $file;
                },
            function ($nameFile) {
                return $nameFile;
            }));

        $builder->get('picture')->addModelTransformer(new CallbackTransformer(
            function ($nameString) {
                if($nameString==null) return $nameString;
                $file = new File('C:\wamp64\www\Repository\MediaPlayer\public\media\PicUpload\\'.$nameString);
                return $file;
            },
            function ($nameFile) {
                return $nameFile;
            }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
