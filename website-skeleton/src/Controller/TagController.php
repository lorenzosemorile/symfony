<?php
namespace App\Controller;


use function json_last_error;
use function json_last_error_msg;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Tag;

class TagController extends AbstractController
{

    /**
     * @Route("/tag/add", name="tag_add")
     */
    public function add(){

        $request = Request::createFromGlobals();
        if ($request->getContentType() != 'json' || !$request->getContent()) {
            return;
        }
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('invalid json body: ' . json_last_error_msg());
        }

        $tag = new Tag();
        $entityManager = $this->getDoctrine()->getManager();
        $tag->setName($data['tag']['name']);
        $entityManager->persist($tag);
        $entityManager->flush();


        return new JsonResponse([
            'status' => 'OK',
            'tag' => [
                'label' => $tag->getName(),
                'value' => $tag->getId()
            ]
        ]);
    }

    public function list(){


        if ($request->getContentType() != 'json') {
            throw $this->createNotFoundException(
                'No tags are found'
            );
        }

        $tags = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findAll();

        if (!$tags) {
            throw $this->createNotFoundException(
                'No tags are found'
            );
        }

        return new JsonResponse([
            'tags' => $tags
        ]);
    }


}