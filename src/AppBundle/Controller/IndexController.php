<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Liga;
use AppBundle\Form\ImageType;
use AppBundle\Form\LigaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="app_index_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Liga');
        $ligas = $repo->findAll();
        return $this->render(':index:index.html.twig',
            [
                'ligas'=> $ligas,
            ]
        );
    }

    /**
     * @Route("/upload", name="app_index_upload")
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

                return $this->redirectToRoute('app_index_index');
            }
        }

        return $this->render(':index:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/insertLiga", name="app_index_insertLiga")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertLigaAction()
    {
        $p= new Liga();
        $form = $this->createForm(LigaType::class, $p);
        return $this->render(':index:form.html.twig',
            [
                'form' =>   $form->createView(),
                'action'=>  $this->generateUrl('app_index_doinsertLiga')
            ]
        );
    }
    /**
     * @Route("/doinsertLiga", name="app_index_doinsertLiga")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doinsertLigaAction(Request $request)
    {
        $p=new Liga();
        //añadimos creator
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        // set creator in our object
        //is granted
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $p->setCreador($user);
        //create Form
        $form=$this->createForm(LigaType::class,$p);
        $form->handleRequest($request);
        if($form->isValid()) {
            $m = $this->getDoctrine()->getManager();
            $m->persist($p);
            $m->flush();
            $this->addFlash('messages', 'Liga añadida');
            return $this->redirectToRoute('app_index_index');
        }
        $this->addFlash('messages','Review your form data');
        return $this->render(':index:form.html.twig',
            [
                'form'  =>  $form->createView(),
                'action'=>  $this->generateUrl('app_index_doinsertLiga')
            ]
        );
    }

    /**
     * @Route("/removeLiga/{id}", name="app_index_removeLiga")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeLigaAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Liga');
        $liga = $repo->find($id);
        $creator= $liga->getCreador().$id;
        $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($liga);
        $m->flush();
        return $this->redirectToRoute('app_index_index');
    }

    /**
     * @Route("/updateLiga/{id}", name="app_index_updateLiga")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateLigaAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $m=$this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Liga');
        $liga=$repo->find($id);

        $creator= $liga->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }

        $form=$this->createForm(LigaType::class,$liga);
        if($form->isValid()) {
            $m->flush();
            return $this->redirectToRoute('app_index_index');
        }
        return $this->render(':index:form.html.twig',
            [
                'form'=>$form->createView(),
                'action'=>$this->generateUrl('app_index_doUpdate',['id'=>$id])
            ]
        );
    }

    /**
     * @Route("/doUpdate/{id}", name="app_index_doUpdate")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doUpdateAction($id,Request $request)
    {

        $m= $this->getDoctrine()->getManager();
        $repo= $m->getRepository('AppBundle:Liga');
        $liga= $repo->find($id);
        $form=$this->createForm(LigaType::class,$liga);

        //El producto es actualizado con estos datos
        $form->handleRequest($request);
        $liga->setUpdatedAt();

        if($form->isValid()){
            $m->flush();
            $this->addFlash('messages','Liga Updated');

            return $this->redirectToRoute('app_index_index');
        }

        $this->addFlash('message' , 'Review your form');
        return $this->render(':index:form.html.twig',
            [
                'form'=> $form->createView(),
                'action'=> $this->generateUrl('app_index_doUpdate',['id'=>$id]),
            ]
        );
    }

    /**
     * @Route("/{slug}.html", name="app_index_showLiga")
     */
    public function showLigaAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Liga');
        $liga=$repository->find($slug);
        return $this->render(':liga:liga.html.twig', [
            'liga'   => $liga,
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
