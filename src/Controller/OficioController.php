<?php

namespace App\Controller;

use App\Entity\Oficio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OficioController extends AbstractController
{
        #[Route('/', name: 'app_oficio', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('oficio/index.html.twig', [
            'controller_name' => 'OficioController',
        ]);
    }
    
    #[Route('/oficio/nuevo', name: 'app_oficio_nuevo', methods: ['POST'])]
    public function nuevo(Request $request, EntityManagerInterface $em): Response
    {
        // Obtener el texto del formulario
        $name = $request->request->get('nombreOficio');

        if ($name) {
            // Crear una nueva recomendación
            $recomendacion = new Oficio();
            $recomendacion->setName($name);
            $recomendacion->setStatus(true);

            // Guardar en la base de datos
            $em->persist($recomendacion);
            $em->flush();

            // Añadir mensaje flash o redirigir a una página de éxito
            $this->addFlash('success', 'Recomendación creada con éxito.');
        }

        // Redirigir o mostrar un mensaje
        return $this->redirectToRoute('app_full_list');
    }
    #[Route('/oficio/{id}/toggle-status', name: 'oficio_toggle_status', methods: ['POST'])]
    public function toggleStatus($id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $oficio = $em->getRepository(Oficio::class)->find($id);

        if (!$oficio) {
            return new JsonResponse(['error' => 'Oficio no encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Estado no proporcionado'], 400);
        }

        $oficio->setStatus($data['status']);
        $em->persist($oficio);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}

