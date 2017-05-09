<?php
/**
 * Created by PhpStorm.
 * User: albertau
 * Date: 4/05/17
 * Time: 20:35
 */
namespace AppBundle\Controller;
use AppBundle\Entity\Image;
use AppBundle\Entity\Equipo;
use AppBundle\Entity\Player;
use AppBundle\Form\ImageType;
use AppBundle\Form\EquipoType;
use AppBundle\Form\PlayerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class EquipoController extends Controller
{
    /**
     * @Route("/", name="app_equip_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexEquipAction()
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Liga');
        $liga = $repo->findAll();
        return $this->render(':equipo:index1.html.twig',
            [
                'liga'=> $liga,
            ]
        );
    }
    /**
     * @Route("/{slug}.html", name="app_equipo_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexEquipoAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Liga');
        $liga = $repo->find($slug);
        return $this->render(':equipo:index.html.twig',
            [
                'liga'=> $liga,
            ]
        );
    }
    /**
     * @Route("/upload", name="app_equipos_upload")
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
                return $this->redirectToRoute('app_equipos_index');
            }
        }
        return $this->render(':equipos:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/insertEquipo/{id}", name="app_equipo_insertEquipo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertEquipoAction($id, Request $request)
    {
        $c = new Equipo();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(EquipoType::class, $c);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $repo = $m->getRepository('AppBundle:Liga');
                $liga = $repo->find($id);
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $c->setCreador($user);
                $c->setLiga($liga);
                $m->persist($c);
                $m->flush();
                return $this->redirectToRoute('app_equipo_index', ['slug' => $id]);
            }
        }
        return $this->render(':equipo:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/removeEquipo/{id}", name="app_equipo_removeEquipo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeEquipoAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Equipo');
        $equipo = $repo->find($id);
        $liga = $equipo->getLiga();
        $ligaid = $liga->getId();
        $creator= $equipo->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($equipo);
        $m->flush();
        return $this->redirectToRoute('app_equipo_index', ['slug' => $ligaid]);
    }
    /**
     * @Route("/updateEquipo/{id}", name="app_equipo_updateEquipo")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateEquipoAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Equipo');
        $equipo=$repo->find($id);
        $liga = $equipo->getLiga();
        $ligaid = $liga->getId();
        $form = $this->createForm(EquipoType::class, $equipo);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m->persist($equipo);
                $m->flush();
                return $this->redirectToRoute('app_equipo_index', ['slug' => $ligaid]);
            }
        }
        return $this->render(':equipo:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("detail/{slug}.html", name="app_equipo_show")
     */
    public function showAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Equipo');
        $equipo=$repository->find($slug);
        return $this->render(':equipo:equipo.html.twig', [
            'equipo'   => $equipo,
        ]);
    }
    /**
     * @Route("/addPlayer/{id}", name="app_player_addPlayer")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addPlayerAction($id, Request $request)
    {
        $c = new Player();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(PlayerType::class, $c);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $repo = $m->getRepository('AppBundle:Equipo');
                $equipo = $repo->find($id);
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $c->setCreador($user);
                $c->setEquipo($equipo);
                $m->persist($c);
                $m->flush();
                return $this->redirectToRoute('app_equipo_show', ['slug' => $id]);
            }
        }
        return $this->render(':player:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}