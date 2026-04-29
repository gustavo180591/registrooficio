<?php

namespace App\Controller;

use App\Entity\Registro;
use App\Form\RegistroType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BusquedaType;

class RegistroController extends AbstractController
{
    #[Route('/registro', name: 'app_registro')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $registro = new Registro();
        $form = $this->createForm(RegistroType::class, $registro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set creation date
            $registro->setCreatedAt(new \DateTime());
            
            $entityManager->persist($registro);
            $entityManager->flush();

            // Redirigir al controlador app_send con los parámetros nombre y email
            return $this->redirectToRoute('app_send', [
                'nombre' => $registro->getName(),
                'email' => $registro->getEmail(),
            ]);
        }

        return $this->render('registro/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/buscar', name: 'app_lista')]
    public function lista(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(BusquedaType::class);
        $form->handleRequest($request);

        // Si se envía el formulario, buscar con los filtros aplicados
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $oficio = $data['oficio'] ?? null;
            // Extraer IDs de las delegaciones seleccionadas
            $delegaciones = [];
            if (isset($data['delegacion'])) {
                $delegaciones = array_map(function($d) { return $d->getId(); }, $data['delegacion']->toArray());
            }

            // Get pagination parameters
            $page = $request->query->get('page', 1);
            $limit = 12;

            $lista = $entityManager->getRepository(Registro::class)->buscar($oficio, $delegaciones, $page, $limit);

            // Get total count for pagination
            $totalItems = $entityManager->getRepository(Registro::class)->countResults($oficio, $delegaciones);
            $totalPages = ceil($totalItems / $limit);

            // Variables para mensajes cuando no hay resultados
            $noResultsMessage = null;
            $alternativeDelegaciones = [];
            $hasOficioInAnyDelegacion = false;

            // Si no hay resultados y se filtró por oficio y delegación
            if ($totalItems === 0 && $oficio !== null && !empty($delegaciones)) {
                // Verificar si hay registros de ese oficio en alguna delegación
                $countOficioTotal = $entityManager->getRepository(Registro::class)->countByOficioOnly($oficio);

                if ($countOficioTotal > 0) {
                    // Hay registros con ese oficio en otras delegaciones
                    $hasOficioInAnyDelegacion = true;
                    $alternativeDelegaciones = $entityManager->getRepository(Registro::class)->findDelegacionesWithOficio($oficio);
                }
            }

            return $this->render('registro/lista.html.twig', [
                'lista' => $lista,
                'form' => $form,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
                'limit' => $limit,
                'oficio' => $oficio,
                'delegaciones' => $delegaciones,
                'hasOficioInAnyDelegacion' => $hasOficioInAnyDelegacion,
                'alternativeDelegaciones' => $alternativeDelegaciones
            ]);
        }
        
        // Filtrar registros habilitados (status = 1)
        $page = $request->query->get('page', 1);
        $limit = 12;
        
        $query = $entityManager->createQuery(
            'SELECT r 
             FROM App\Entity\Registro r
JOIN r.oficio o 
             WHERE r.status = :status
ORDER BY o.name ASC, r.name ASC'
        )->setParameter('status', true)
        ->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit);
        
        $lista = $query->getResult();
        
        // Get total count for pagination
        $totalQuery = $entityManager->createQuery(
            'SELECT COUNT(r.id) 
             FROM App\Entity\Registro r
JOIN r.oficio o 
             WHERE r.status = :status'
        )->setParameter('status', true);
        
        $totalItems = (int)$totalQuery->getSingleScalarResult();
        $totalPages = ceil($totalItems / $limit);
        
        return $this->render('registro/lista.html.twig', [
            'lista' => $lista,
            'form' => $form,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'limit' => $limit,
            'oficio' => null,
            'delegaciones' => [],
            'hasOficioInAnyDelegacion' => false,
            'alternativeDelegaciones' => []
        ]);
    }

    #[Route('/registro/{id}/toggle-status', name: 'registro_toggle_status', methods: ['POST'])]
    public function toggleStatus($id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $registro = $entityManager->getRepository(Registro::class)->find($id);

        if (!$registro) {
            return new JsonResponse(['error' => 'Registro no encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Estado no proporcionado'], 400);
        }

        $registro->setStatus($data['status']);
        $entityManager->persist($registro);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
