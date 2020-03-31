<?php

namespace KidzyBundle\Controller;

use KidzyBundle\Entity\Avis;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use KidzyBundle\Form\AvisType;



/**
 * avis controller.
 *
 */
class AvisController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $avis = $em->getRepository('KidzyBundle:Avis')->findAll();

        return $this->render('@Kidzy/avis/index.html.twig', array(
            'avis' => $avis,
        ));
    }
    public function newAction(Request $request)
    {
        $avis = new Avis();
        $form = $this->createForm('KidzyBundle\Form\AvisType', $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $today = new \DateTime('now');
            $avis->setDateAvis($today);
            $em->persist($avis);
            $em->flush();

            return $this->redirectToRoute('Mesavis', array('idAvis' => $avis->getIdAvis() ));
        }
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('@Kidzy/avis/new.html.twig', array(
            'avis' => $avis,
            'parent' => $user,
            'form' => $form->createView(),
        ));
    }

    public function showAction(Avis $avi)
    {
        $deleteForm = $this->createDeleteForm($avi);

        return $this->render('@Kidzy/avis/show.html.twig', array(
            'avi' => $avi,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, Avis $avi)
    {
        $form = $this->createDeleteForm($avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($avi);
            $em->flush();
        }

        return $this->redirectToRoute('avis_index');
    }




    private function createDeleteForm(Avis $avi)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('avis_delete', array('idAvis' => $avi->getIdAvis())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    public function avisFAction()
    {
        $em = $this->getDoctrine()->getManager();

        $avis = $em->getRepository('KidzyBundle:Avis')->findAll();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('@Kidzy/avis/avisF.html.twig', array(
            'avis' => $avis,
            'parent' => $user

        ));
    }

    public function MesavisAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $avis = $em->getRepository('KidzyBundle:Avis')->findAll();
        return $this->render('@Kidzy/avis/Mesavis.html.twig', array(
            'parent' => $user,
            'avis' => $avis




        ));
    }



}