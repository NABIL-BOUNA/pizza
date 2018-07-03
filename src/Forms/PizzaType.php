<?php
namespace App\Forms;

use App\Entity\Pizza;
use App\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PizzaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, array('label'  => 'Nom de pizza','attr' => array('id'=>'adresse', 'name'=>'adresse', 'class'=>'form-control',
                'placeholder'=>'Entrez nom de pizza ici...')))
            ->add('Price', TextType::class, array('label'  => 'Prix','attr' => array('id'=>'nom', 'name'=>'nom', 'class'=>'form-control',
                'placeholder'=>'Entrez le prix ici...')))
            ->add('Description', TextType::class, array('label'  => 'Description','attr' => array('id'=>'telephone', 'name'=>'telephone', 'class'=>'form-control',
                'placeholder'=>'Entrez la description ici...')))
            ->add('Ingredients', EntityType::class, array(
                'class' => 'App:Ingredient',
                'choice_label' => 'name',
                'label' => 'Ingredient',
                'multiple' => true,
                'required' => true,
                'attr' => array('class'=>'form-control')
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Pizza::class,
        ));
    }
    public function getName()
    {
        return 'pizza_form';
    }

}