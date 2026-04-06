<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class SendController extends AbstractController
{
    #[Route('/send', name: 'app_send')]
    public function index(MailerInterface $mailer, Request $request, KernelInterface $kernel): Response
    {
        $nombre = $request->query->get('nombre');
        $emailDestino = $request->query->get('email');
               // Enviar el correo con el nombre personalizado
	try{
        $this->sendEmail($mailer, $kernel, $nombre, $emailDestino);
	}catch (\Exception $e) {
	}
        // Renderizar una vista de confirmación o redirigir
return $this->render('registro/success.html.twig', ['nombre' => $nombre]);
    }

    private function sendEmail(MailerInterface $mailer, KernelInterface $kernel, string $nombre, string $emailDestino)
    {
        // Obtén la ruta física de la imagen
        $imagePath = $kernel->getProjectDir() . '/public/images/logo.png';
    
        $email = (new Email())
            ->from('registrodeoficios.ar@gmail.com') // Correo de origen
            ->to($emailDestino) // Correo ingresado por el usuario
            ->subject('Registro Exitoso')
            ->html("
                <p>Hola $nombre,</p>
                <p>Gracias por registrarte. Tu solicitud fue registrada con éxito y será verificada en las próximas 24 horas. Que tenga un buen día.</p>
                <p><img src=\"cid:logo\" alt=\"Logo\" /></p>
            ")
            ->embedFromPath($imagePath, 'logo'); // Incrusta la imagen con CID 
    
        $mailer->send($email);
    }
}
