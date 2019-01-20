<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Product;
use App\Entity\Tag;

class ProductController extends AbstractController
{

    /**
     * @Route("/product/list", name="product_list")
     */
    public function index()
    {

        $repo = $this->getDoctrine()->getRepository(Product::class);
        $request = Request::createFromGlobals();
        $term = $request->query->get('term');
        if ($term){
            $products = $repo->getProductsFromTagLike($term);

        }else{
            $products = $repo->findAll();
            if (!$products) {
                throw $this->createNotFoundException(
                    'No products are found'
                );
            }
        }



        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{id}/edit", name="product_edit")
     * @Route("/product/create", name="product_create")
     */
    public function edit($id = null)
    {
        $product = new Product();
        $entityManager = $this->getDoctrine()->getManager();
        $msg = [
            'label' => 'Add',
            'h1' => 'Create Product'
        ];
        $image = null;

        if ($id){
            $msg['label'] = 'Update';
            $msg['h1'] = 'Update Product';
            $product = $entityManager->getRepository(Product::class)->find($id);
            if (!$product) {
                throw $this->createNotFoundException(
                    'No product found for id '. $id
                );
            }
            $image = $product->getImage();
        }

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'insert name']
            ])
            ->add('description', Textareatype::class, [
                'attr' => ['placeholder' => 'insert description']
            ])
            ->add('image', FileType::class, [
                'label' => 'Upload Image',
                'data_class' => null
            ])
            ->add('relation', EntityType::class, [
                'choice_label' => 'name',
                'class' => Tag::class,
                'attr' => ['data-url' => '/tag/list'],
                //'attr' => ['name' => 'form[relation][]'],
                'label'     => 'Associated Tags',
                'multiple' => true
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'button expanded'],
                'label' => $msg['label']
            ])
            ->getForm();

        $request = Request::createFromGlobals();
        //$request = $this->getRequest();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            $product->setImage($image);

            $file = $form->get('image')->getData();

            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('product_image_dir'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $product->setImage($fileName);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'h1' => $msg['h1']
        ]);

    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }


}