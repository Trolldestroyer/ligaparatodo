<?php
/**
 * Created by PhpStorm.
 * User: albertau
 * Date: 4/05/17
 * Time: 20:35
 */
namespace AppBundle\Controller;
use AppBundle\Entity\Image;
use AppBundle\Entity\Ronda;
use AppBundle\Form\ImageType;
use AppBundle\Form\RondaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class RondaController extends Controller
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
        return $this->render(':ronda:index1.html.twig',
            [
                'liga'=> $liga,
            ]
        );
    }
    /**
     * @Route("/{slug}.html", name="app_ronda_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexRondaAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repo=$m->getRepository('AppBundle:Liga');
        $liga = $repo->find($slug);
        return $this->render(':ronda:index.html.twig',
            [
                'liga'=> $liga,
            ]
        );
    }
    /**
     * @Route("/upload", name="app_ronda_upload")
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
                return $this->redirectToRoute('app_ronda_index');
            }
        }
        return $this->render(':ronda:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/insertRonda/{id}", name="app_ronda_insertRonda")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function insertRondaAction($id, Request $request)
    {
        $c = new Ronda();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(RondaType::class, $c);
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
                return $this->redirectToRoute('app_ronda_index', ['slug' => $id]);
            }
        }
        return $this->render(':ronda:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/removeRonda/{id}", name="app_ronda_removeRonda")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeRondaAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Ronda');
        $ronda = $repo->find($id);
        $liga = $ronda->getLiga();
        $ligaid = $liga->getId();
        $creator= $ronda->getCreador().$id;
        $current = $this->getUser().$id;
        if (($current!=$creator)&&(!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        $m->remove($ronda);
        $m->flush();
        return $this->redirectToRoute('app_ronda_index', ['slug' => $ligaid]);
    }
    /**
     * @Route("/updateRonda/{id}", name="app_ronda_updateRonda")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateRondaAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $m = $this->getDoctrine()->getManager();
        $repo = $m->getRepository('AppBundle:Ronda');
        $ronda=$repo->find($id);
        $liga = $ronda->getLiga();
        $ligaid = $liga->getId();
        $form = $this->createForm(RondaType::class, $ronda);
        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $m->persist($ronda);
                $m->flush();
                return $this->redirectToRoute('app_ronda_index', ['slug' => $ligaid]);
            }
        }
        return $this->render(':ronda:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("detail/{slug}.html", name="app_ronda_show")
     */
    public function showAction($slug)
    {
        $m = $this->getDoctrine()->getManager();
        $repository= $m->getRepository('AppBundle:Ronda');
        $ronda=$repository->find($slug);
        return $this->render(':ronda:ronda.html.twig', [
            'ronda'   => $ronda,
        ]);
    }

}