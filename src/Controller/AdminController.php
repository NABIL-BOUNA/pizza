<?php
namespace App\Controller;
use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
use Doctrine\ORM\EntityRepository;
use App\Forms\PizzaType;

class AdminController extends Controller
{

    /**
     * @Route("/admin/", name="admin_pizzas")
     * @Template()
     */
    public function adminAction()
    {
        return array();
    }

    /**
     * @Route("/admin/pizza/ajouter/", name="admin_add_pizzas")
     * @Template()
     */
    public function adminajouterpizzaAction(Request $request)
    {
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->add('add', SubmitType::class, array('label' => 'Commander' ,'attr' => array( 'class' => 'form-control btn btn-success text-center')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $cmd = new Pizza;

            $cmd->setName($form["Name"]->getData())
                ->setPrice($form["Price"]->getData())
                ->setDescription($form["Description"]->getData())
                ->setIngredients($form["Ingredients"]->getData());
            $em->persist($cmd);
            $em->flush();
            $this->addFlash(
                'success', 'Commande bien ajouter, merci :)'
            );
            return $this->render("admin/adminajouterpizza_return.html.twig",['inserted' => true]);
        }
        return [ 'form' => $form->createView()];
    }

    /**
 * @Route("/admin/commande", name="pizza_commande_list")
 * @Template()
 */
    public function pizzascommandeAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT commande FROM App:Commande commande";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

        return ['pagination' => $pagination];
    }

    /**
     * @Route("/admin/commande/{statue}/{id}", name="valide_commande_list")
     * @Template()
     */
    public function validecommandeAction($id,$statue)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commande = $entityManager->getRepository(Commande::class)->find($id);

        if($statue=="valider"){
            $commande->setStatus(1);
        }
        if($statue=="annuler"){
            $commande->setStatus(0);
        }

        $entityManager->flush();

        $this->getDoctrine()
            ->getManager()
            ->getRepository('App:Commande')
            ->findAll();

        return $this->redirectToRoute("pizza_commande_list");
    }


}