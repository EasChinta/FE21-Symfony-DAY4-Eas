<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Manufacturer;
use App\Entity\Price;





use App\Entity\Products;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ZalandoController extends AbstractController
{
    #[Route('/zalando', name: 'zalando')]
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository('App:Products')->findAll();

        return $this->render('zalando/index.html.twig', array('products' => $products));
    }
    #[Route('/create', name: 'zalando_create')]
    public function create(Request $request): Response
    {
        $products = new Products;
        
        $form = $this->createFormBuilder($products)->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('picture', UrlType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('fk_manufacturer', EntityType::class, [
                'class' => Manufacturer::class,
                'choice_label' => 'name',
            ])
            ->add('fk_price', EntityType::class, [
                'class' => Price::class,
                'choice_label' => 'price',
            ])
            ->add('save', SubmitType::class, array('label' => 'Add Product', 'attr' => array('class' => 'btn-grad', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $picture = $form['picture']->getData();



            $products->setName($name);
            $products->setCategory($category);
            $products->setDescription($description);
            $products->setPicture($picture);

            $em = $this->getDoctrine()->getManager();
            $em->persist($products);
            $em->flush();
            $this->addFlash(
                'notice',
                'Product Added'
            );
            return $this->redirectToRoute('zalando');
        }

        return $this->render('zalando/create.html.twig', array('form' => $form->createView()));
    }
    #[Route('/edit/{id}', name: 'zalando_edit')]
    public function edit(Request $request, $id): Response
    {
        $products = $this->getDoctrine()->getRepository('App:Products')->find($id);

        $form = $this->createFormBuilder($products)->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('picture', UrlType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('fk_manufacturer', EntityType::class, [
                'class' => Manufacturer::class,
                'choice_label' => 'name',
            ])
            ->add('fk_price', EntityType::class, [
                'class' => Price::class,
                'choice_label' => 'price',
            ])
            ->add('save', SubmitType::class, array('label' => 'Update Product', 'attr' => array('class' => 'btn-grad', 'style' => 'margin-bottom:15px', 'onclick' => 'return confirm("Save changes?")' )))
            ->add('back', SubmitType::class, array('label' => 'Back', 'attr' => array('class' => 'btn-grad2', 'style' => 'margin-bottom:15px', 'href' => 'index.html.twig' )))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();
            $picture = $form['picture']->getData();



            $products->setName($name);
            $products->setCategory($category);
            $products->setDescription($description);
            $products->setPicture($picture);

            $em = $this->getDoctrine()->getManager();
            $em->persist($products);
            $em->flush();
            $this->addFlash(
                'notice',
                'Product Added'
            );
            return $this->redirectToRoute('zalando');
        }
        return $this->render('zalando/edit.html.twig', array('products' => $products, 'form' => $form->createView()));
    }

    #[Route('/details/{id}', name: 'zalando_details')]
    public function details($id): Response
    {
        $products = $this->getDoctrine()->getRepository('App:Products')->find($id);
        return $this->render('zalando/details.html.twig', array('products' => $products));
    }

    #[Route('/delete/{id}', name: 'zalando_delete')]
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('App:Products')->find($id);
        $em->remove($products);

        $em->flush();
        $this->addFlash(
            'notice',
            'Product Removed'
        );

        return $this->redirectToRoute('zalando');
    }
}
