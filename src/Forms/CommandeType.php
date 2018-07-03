<?php
namespace App\Forms;

use App\Entity\Commande;
use App\Entity\Pizza;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse', TextType::class, array('label'  => 'Adresse','attr' => array('id'=>'adresse', 'name'=>'adresse', 'class'=>'form-control',
                'placeholder'=>'Entrez votre adresse ici...')))
            ->add('nom', TextType::class, array('label'  => 'Nom','attr' => array('id'=>'nom', 'name'=>'nom', 'class'=>'form-control',
                'placeholder'=>'Entrez votre nom ici...')))
            ->add('telephone', TextType::class, array('label'  => 'Téléphone','attr' => array('id'=>'telephone', 'name'=>'telephone', 'class'=>'form-control',
                'placeholder'=>'Entrez votre téléphone ici...')))
            ->add('pizzas', EntityType::class, array(
                'class'        => 'App:Pizza',
                'choice_label' => 'name',
                'label'        => 'Pizza',
                'multiple' => true,
                'required' => true,
                'attr' => array('class'=>'form-control')
            ));

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Commande::class,
        ));
    }
    public function getName()
    {
        return 'commande_form';
    }

}