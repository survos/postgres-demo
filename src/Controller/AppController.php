<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_app')]
    public function index(Request $request, PersonRepository $personRepository): Response
    {
        $defaults = ['languages' => 'en', 'countries' => 'US'];
        $form = $this->createForm(SearchType::class, $defaults);
        $form->handleRequest($request);
        $queryBuilder = $personRepository->createQueryBuilder('p');

        if ($form->isSubmitted() && $form->isValid()) {
            $defaults = $form->getData();
            $queryBuilder->select("p.name, JSON_GET_FIELD_AS_TEXT(p.info, 'languages') as languagesText, p.info");
//            dd($queryBuilder->getQuery()->getResult()[0]);
//            $queryBuilder->andWhere("JSON_GET_FIELD_AS_TEXT(p.info, 'languages') LIKE :fieldValue")
//                ->setParameter('fieldValue', '%' . $defaults['languages'] . '%s');
//            dd($queryBuilder->getQuery()->getSQL(), $queryBuilder->getParameter('fieldValue'));

            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
//            $languages = explode(',', $form->get('languages')->getData());
        }

        return $this->render('app/index.html.twig', [
            'languages' => $defaults['languages'],
            'countries' => $defaults['countries'],
            'form' => $form->createView(),
            'persons' => $queryBuilder->getQuery()->getResult(),
            'controller_name' => 'AppController',
        ]);
    }
}
