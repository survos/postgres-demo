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
            $field = $defaults['languages'];
            $expr = $queryBuilder->expr()->orX();

            //            $queryBuilder->andWhere("JSON_GET_FIELD_AS_TEXT(p.info, 'languages') LIKE :fieldValue")
//                ->setParameter('fieldValue', '%' . $defaults['languages'] . '%s');
//            dd($queryBuilder->getQuery()->getSQL(), $queryBuilder->getParameter('fieldValue'));
//            $field = $defaults['languages'];
            $languages = explode(',', $defaults['languages']);
            $andX = $queryBuilder->expr()->orX();
            foreach ($languages as $language) {
                $andX->add($queryBuilder->expr()->eq("(JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$language}'))", 'true'));
            }
            $queryBuilder->where($andX);
//            $queryBuilder->select("p.name, p.info");
//            $queryBuilder->andWhere("(JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$field}'))");
//            $queryBuilder->where("(JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$field}'))=true");
//            $queryBuilder->andWhere("x=TRUE");
//        dd($queryBuilder->getQuery()->getResult()[0], $queryBuilder->getQuery()->getSQL());

            foreach (explode(',',$field) as $lang) {
                $expr->add("(JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$lang}'))=TRUE");
            }

//            $queryBuilder->where("IS_CONTAINED_BY(TO_JSONB(STRING_TO_ARRAY('{$defaults['languages']}', ',')), JSON_GET_FIELD(p.info, 'languages')) = true");

            $queryBuilder->select("p.name, 
             (JSONB_EXISTS(JSON_GET_FIELD(p.info, 'languages'), '{$field}')) as speaks, JSON_GET_FIELD_AS_TEXT(p.info, 'languages') as languagesText, JSON_GET_FIELD(p.info, 'languages') as languagesArray, p.info");
            $queryBuilder->andWhere($expr);
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
