<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Settings;
use AppBundle\Form\SettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends Controller
{
    /**
     * @Route("/settings", name="settings")
     */
    public function settingsAction(Request $request)
    {
        // There has to be only one setting.
        $settings = $this->getDoctrine()->getRepository('AppBundle:Settings')->findAll();
        // If there is no setting create a new one --> later maybe in deploy script.
        $settings = $settings ? $settings[0] : new Settings();

        $form = $this->createForm(SettingsType::class, $settings);
        $form->handleRequest($request);
        $isFormSaved = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($settings);
            $em->flush();
            $isFormSaved = true;
        }

        return $this->render('AppBundle::settings.html.twig', array(
            'form' => $form->createView(),
            'isFormSaved' => $isFormSaved
        ));
    }
}