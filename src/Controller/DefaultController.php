<?php
namespace App\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Pizza;
use App\Entity\Ingredient;
use App\Entity\Commande;
use Doctrine\ORM\EntityRepository;
use App\Forms\CommandeType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index_pizzas")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render("default/index.html.twig");
    }

    /**
     * @Route("/pizzas", name="pizzas_list")
     * @Template()
     */
    public function pizzasAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT pizza FROM App:Pizza pizza";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 8);

        return $this->render("default/pizzas.html.twig",['pagination' => $pagination]);
    }

    /**
     * @Route("/commande", name="commande_pizzas")
     * @Template()
     */
    public function commandePizzasAction(Request $request) {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->add('add', SubmitType::class, array('label' => 'Commander' ,'attr' => array( 'class' => 'form-control btn btn-success text-center')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $cmd = new Commande;

            $cmd->setNom($form["nom"]->getData())
                ->setadresse($form["adresse"]->getData())
                ->setTelephone($form["telephone"]->getData())
                ->setPizzas($form["pizzas"]->getData());
            $em->persist($cmd);
            $em->flush();
            $this->addFlash(
                'success', 'Commande bien ajouter, merci :)'
            );
            return $this->render("default/commande_return.html.twig",['form' => null, 'inserted' => true]);
        }

        return $this->render("default/commande.html.twig",[ 'form' => $form->createView()]);
    }
    
}