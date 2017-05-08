<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Entrenador;
use AppBundle\Form\ImageType;
use AppBundle\Form\EntrenadorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EntrenadorController extends Controller
{
    /**
     * @Route("/entrenador", name="app_entrenador_entrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Entrenador');
        $m->flush();
        $entrenadores = $repo->findAll();
        return $this->render(':entrenador:entrenador.html.twig',
            [
                'entrenador'=> $entrenadores,
            ]
        );
    }

    /**
     * @Route("/upload", name="app_entrenador_upload")
     */
    public function uploadAction(Request $request)
    {
        $p = new Image();
        $form = $this->createForm(ImageType::class, $p);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $m->persist($p);
                $m->flush();

                return $this->redirectToRoute('app_entrenador_entrenador');
            }
        }

        return $this->render(':entrenador:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/insertEntrenador", name="app_entrenador_insertEntrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertEntrenadorAction()
    {
        $p= new EntrenadorType();
        $form = $this->createForm(EntrenadorType::class, $p);
        return $this->render(':entrenador:form.html.twig',
            [
                'form' =>   $form->createView(),
                'action'=>  $this->generateUrl('app_entrenador_doinsertEntrenador')
            ]
        );
    }
    /**
     * @Route("/doinsertEntrenador", name="app_entrenador_doinsertEntrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doinsertEntrenadorAction(Request $request)
    {
        $p=new Entrenador();
        //añadimos creator
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        // set creator in our object
        //is granted
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $p->setCreador($user);
        //create Form
        $form=$this->createForm(EntrenadorType::class,$p);
        $form->handleRequest($request);
        if($form->isValid()) {
            $m = $this->getDoctrine()->getManager();
            $m->persist($p);
            $m->flush();
            $this->addFlash('messages', 'Entrenador añadido');
            return $this->redirectToRoute('app_entrenador_entrenador');
        }
        $this->addFlash('messages','Review your form data');
        return $this->render(':entrenador:form.html.twig',
            [
                'form'  =>  $form->createView(),
                'action'=>  $this->generateUrl('app_entrenador_doinsertEntrenador')
            ]
        );
    }

    /**
     * @Route("/removeEntrenador/{id}", name="app_entrenador_removeEntrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeEntrenadorAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Entrenador');
        $entrenador = $repo->find($id);
        $creator= $entrenador->getCreador().$id;
        $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($entrenador);
        $m->flush();
        return $this->redirectToRoute('app_entrenador_entrenador');
    }

    /**
     * @Route("/updateEntrenador/{id}", name="app_entrenador_updateEntrenador")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateEntrenadorAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $m=$this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Entrenador');
        $entrenador=$repo->find($id);

        $creator= $entrenador->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }

        $form=$this->createForm(EntrenadorType::class,$entrenador);
        if($form->isValid()) {
            $m->flush();
            return $this->redirectToRoute('app_entrenador_entrenador');
        }
        return $this->render(':entrenador:form.html.twig',
            [
                'form'=>$form->createView(),
                'action'=>$this->generateUrl('app_entrenador_doUpdate',['id'=>$id])
            ]
        );
    }

    /**
     * @Route("/doUpdate/{id}", name="app_entrenador_doUpdate")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doUpdateAction($id,Request $request)
    {

        $m= $this->getDoctrine()->getManager();
        $repo= $m->getRepository('AppBundle:Entrenador');
        $entrenador= $repo->find($id);
        $form=$this->createForm(EntrenadorType::class,$entrenador);

        //El producto es actualizado con estos datos
        $form->handleRequest($request);
        $entrenador->setUpdatedAt();

        if($form->isValid()){
            $m->flush();
            $this->addFlash('messages','Entrenador Updated');

            return $this->redirectToRoute('app_entrenador_entrenador');
        }

        $this->addFlash('message' , 'Review your form');
        return $this->render(':entrenador:form.html.twig',
            [
                'form'=> $form->createView(),
                'action'=> $this->generateUrl('app_entrenador_doUpdate',['id'=>$id]),
            ]
        );
    }

    /**
     * @Route("/{slug}.html", name="app_entrenador_showEntrenador")
     */
    public function showEntrenadorAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Entrenador');
        $entrenador=$repository->find($slug);
        return $this->render(':entrenador:entrenador.html.twig', [
            'entrenador'   => $entrenador,
        ]);
    }
    /**
     * @Route("/usuario/{slug}.html", name="app_usuario_show")
     *
     */
    public function showUserAction($slug)
    {
        $m = $this ->getDoctrine()->getManager();
        $repository= $m->getRepository('UserBundle:User');
        $usuario=$repository->find($slug);
        return $this->render('usuario/usuario.html.twig',[
            'usuario' => $usuario,
        ]);
    }


}
