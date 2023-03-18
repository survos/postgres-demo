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
        $defaults = [ 'languages' => 'en', 'countries' => 'US' ];
        $form = $this->createForm(SearchType::class, $defaults);
        $form->handleRequest($request);
        $queryBuilder = $personRepository->createQueryBuilder('p');
//        $field = 'languages';
//        $queryBuilder->select("JSONB_EXISTS(p.info, '$field')");
        $field = $defaults['languages'];

        $queryBuilder->select("p.name,
          (JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$field}')) as speaks, 
        
        JSON_GET_FIELD_AS_TEXT(p.info, 'languages') as languagesText, 
        JSON_GET_FIELD(p.info, 'languages') as languagesArray, p.info");
//            dump($queryBuilder->getQuery()->getSQL(), $queryBuilder->getQuery()->getResult()[0]);

        if ($form->isSubmitted() && $form->isValid()) {
            $defaults = $form->getData();
//            $queryBuilder->andWhere("JSON_GET_FIELD_AS_TEXT(p.info, 'languages') LIKE :fieldValue")
//                ->setParameter('fieldValue', '%' . $defaults['languages'] . '%s');
//            dd($queryBuilder->getQuery()->getSQL(), $queryBuilder->getParameter('fieldValue'));
//            $field = $defaults['languages'];
            $languages = $defaults['languages']? explode(',', $defaults['languages']) : [];
            $andX = $queryBuilder->expr()->andX();
            foreach ($languages as $language) {
                $andX->add($queryBuilder->expr()->eq("(JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$language}'))", 'true'));
            }
            $queryBuilder->select("p.name, p.info");
//            $queryBuilder->andWhere("(JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$field}'))");
//            $queryBuilder->where("(JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$field}'))=true");
            if(count($languages)) {
                $queryBuilder->where($andX);
            }
//            $queryBuilder->andWhere("x=TRUE");
//        dd($queryBuilder->getQuery()->getResult()[0], $queryBuilder->getQuery()->getSQL());

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
