<?php
// src/Controller/CommentController.
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Registro; // Asegúrate de importar la entidad Registro
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentController extends AbstractController
{
    #[Route('/guardar_comentario', name: 'guardar_comentario', methods: ['POST'])]
    public function guardarComentario(Request $request, EntityManagerInterface $entityManager, CommentRepository $commentRepository): JsonResponse
    {
        $registroId = $request->request->get('registroId');
        $comentario = $request->request->get('comentario');
        $calification = $request->request->get('calification');
        $name = $request->request->get('name');

        if (!$registroId || !$comentario || !$calification || !$name) {
            return new JsonResponse(['success' => false], Response::HTTP_BAD_REQUEST);
        }

        // Obtener el registro desde la base de datos
        $registro = $entityManager->getRepository(Registro::class)->find($registroId);

        if (!$registro) {
            return new JsonResponse(['success' => false, 'message' => 'Registro no encontrado'], Response::HTTP_NOT_FOUND);
        }

        // Crear un nuevo comentario y asociarlo al registro correspondiente
        $comment = new Comment();
        $comment->setRegistro($registro);
        $comment->setComment($comentario);
        $comment->setCalification((int) $calification);
        $comment->setName($name);

        // Guardar el comentario en la base de datos
        $entityManager->persist($comment);
            $entityManager->flush();
        return new JsonResponse(['success' => true]);
    }
    #[Route('/comentarios/{id}', name: 'comentarios', methods: ['GET'])]
    public function comentarios(int $id, CommentRepository $commentRepository): JsonResponse
    {
        $comments = $commentRepository->findBy(['registro' => $id], ['id' => 'ASC']); // Ordenar por ID ascendente

        $result = [];
        foreach ($comments as $comment) {
            $result[] = [
                'name' => $comment->getName(),
                'calification' => $comment->getCalification(),
                'comment' => $comment->getComment(),
            ];
        }

        return new JsonResponse(['comments' => $result]);
    }
}
