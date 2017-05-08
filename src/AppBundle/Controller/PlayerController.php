<?php
/**
 * Created by PhpStorm.
 * User: albertau
 * Date: 3/05/17
 * Time: 19:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Player;
use AppBundle\Form\ImageType;
use AppBundle\Form\PlayerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class PlayerController extends Controller
{
    /**
     * @Route("/", name="app_playe_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indeAction()
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Player');
        $players = $repo->findAll();
        return $this->render(':player:player.html.twig',
            [
                'players'=> $players,
            ]
        );
    }
    /**
     * @Route("/{slug}.html", name="app_player_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexPlayerAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Equipo');
        $equipo = $repo->find($slug);
        return $this->render(':player:player.html.twig',
            [
                'equipo'=> $equipo,
            ]
        );
    }
    /**
     * @Route("/upload", name="app_player_upload")
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

                return $this->redirectToRoute('app_player_index');
            }
        }

        return $this->render(':player:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/insertPlayer/{id}", name="app_player_insertPlayer")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertPlayerAction($id, Request $request)
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
                return $this->redirectToRoute('app_player_index', ['slug' => $id]);
            }
        }
        return $this->render(':player:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/removePlayer/{id}", name="app_player_removePlayer")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removePlayerAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Player');
        $player = $repo->find($id);
        $equipo = $player->getEquipo();
        $equipoid = $equipo->getID();
        $creator= $player->getCreador().$id;
        $current = $this->getUser().$id;

        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($player);
        $m->flush();
        return $this->redirectToRoute('app_player_index',array('slug' => $equipoid));

    }


    /**
     * @Route("/updatePlayer/{id}", name="app_player_updatePlayer")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePlayerAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Player');
        $player=$repo->find($id);
        $equipo = $player->getEquipo();
        $equipoid = $equipo->getID();
        $form = $this->createForm(PlayerType::class, $player);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m->persist($player);
                $m->flush();
                return $this->redirectToRoute('app_player_index', ['slug' => $equipoid]);
            }
        }
        return $this->render(':player:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("showPlayer/{slug}.html", name="app_player_show")
     */
    public function showPlayerAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Player');
        $player=$repository->find($slug);
        return $this->render(':player:playervist.html.twig', [
            'player'   => $player,
        ]);
    }
}