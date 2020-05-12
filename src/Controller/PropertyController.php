<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PropertyController extends AbstractController
{
/**
 * @var PropertyRepository
 */
    private $repository;


    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);
        
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            9
        );
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties'   => $properties,
            'form'         => $form->createView()   
        ]);
    }

    /**
     * @Route("/biens/{id}", name="property.show")
     *@return Response
     */
    public function show($id): Response 
    {
        $property = $this->repository->find($id);
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }
}
