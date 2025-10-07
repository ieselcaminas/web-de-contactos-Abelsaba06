<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Entity\Provincia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

class ContactoController extends AbstractController
{
    private $contactos = [
        1 => ["nombre" => "Juan Pérez", "telefono" => "524142432", "email" => "juanp@ieselcaminas.org"],
        2 => ["nombre" => "Ana López", "telefono" => "58958448", "email" => "anita@ieselcaminas.org"],
        5 => ["nombre" => "Mario Montero", "telefono" => "5326824", "email" => "mario.mont@ieselcaminas.org"],
        7 => ["nombre" => "Laura Martínez", "telefono" => "42898966", "email" => "lm2000@ieselcaminas.org"],
        9 => ["nombre" => "Nora Jover", "telefono" => "54565859", "email" => "norajover@ieselcaminas.org"]
    ];
    
    #[Route('/contacto/insertar', name:'insertar')]
    public function insertar(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine ->getManager();
        foreach($this->contactos as $c){
            $contacto =new Contacto();
            $contacto->setNombre($c["nombre"]);
            $contacto->setTelefono($c["telefono"]);
            $contacto->setEmail($c["email"]);
            $entityManager->persist($contacto);
        }
        try{
            $entityManager->flush();
            return new Response("Contactos insertados");
        }catch (\Exception $e){
            return new Response("Error insertando objetos");
        }
    }

    #[Route('/contacto/update/{id}/{nombre}', name:'modificar')]
    public function update(ManagerRegistry $doctrine,$id,$nombre):Response{
        $entityManager =$doctrine->getManager();
        $repositorio=$doctrine->getRepository(Contacto::class);
        $contacto=$repositorio->find($id);
        if($contacto){
            $contacto->setNombre($nombre);
            try{
                $entityManager->flush();
                return $this->render('ficha_contacto.html.twig',[
                    'contacto'=>$contacto
                ]);
            }catch (\Exception $e){
            return new Response("Error insertando objetos");
        }
        }else{
            return $this->render('ficha_contacto.html.twig',[
                    'contacto'=>null
                ]);
        }
    }

     #[Route('/contacto/delete/{id}/{nombre}', name:'eliminar')]
    public function delete(ManagerRegistry $doctrine,$id,$nombre):Response{
        $entityManager =$doctrine->getManager();
        $repositorio=$doctrine->getRepository(Contacto::class);
        $contacto=$repositorio->find($id);
        if($contacto){
            $contacto->setNombre($nombre);
            try{
                $entityManager->remove($contacto);
                $entityManager->flush();
                return new Response("Contacto eliminado");
            }catch (\Exception $e){
            return new Response("Error eliminando objetos");
        }
        }else{
            return $this->render('ficha_contacto.html.twig',[
                    'contacto'=>null
                ]);
        }
    }

    #[Route('/contacto/{codigo?1}/insertarProvincias', name:'insertarproviciaaContacto')]
    public function insertarProvincia(ManagerRegistry $doctrine, $codigo): Response
    {
        $entityManager =$doctrine->getManager();
        $repositorio=$doctrine->getRepository(Contacto::class);
        $repositorioProvincia = $doctrine->getRepository(Provincia::class);
        $provincia = $repositorioProvincia->findOneBy(['nombre' => 'Castellón']);
        if (!$provincia) {
        $provincia = new Provincia();
        $provincia->setNombre("Castellón");
        $entityManager->persist($provincia);
        }
        $contacto=$repositorio->find($codigo);
        $contacto->setProvincia($provincia);
        $entityManager->persist($provincia);
        $entityManager->persist($contacto);
        try{
            $entityManager->flush();    
            return new Response("Contacto modificado");
            }catch (\Exception $e){
            return new Response("Error eliminando objetos". $e->getMessage());
        }
    }

    #[Route('/contacto/{codigo?1}', name:'ficha')]
    public function ficha(ManagerRegistry $doctrine, $codigo): Response
    {
        $repositorio=$doctrine->getRepository(Contacto::class);
        $contacto = $repositorio->find($codigo);
        return $this->render('ficha_contacto.html.twig', [
    'contacto' => $contacto]);
    }    
}