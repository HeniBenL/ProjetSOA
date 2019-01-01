<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\auteur;
use AppBundle\Entity\livre;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
    * @Rest\Get("/books/")
    */
    public function getBooks(Request $request)
        {
           

            if( ($request->query->get('title')!='') && ($request->query->get('ord')!='')){
                $result = $this->getDoctrine()->getRepository(livre::class)->createQueryBuilder('o')
                ->where('o.titre LIKE :title')->orderBy('o.titre', $request->query->get('ord'))
                ->setParameter('title', '%'.$request->query->get('title').'%')
                ->getQuery()
                ->getResult();
            }
            else if( $request->query->get('title')!='' ){
                $result = $this->getDoctrine()->getRepository(livre::class)->createQueryBuilder('o')
                ->where('o.titre LIKE :title')
                ->setParameter('title', '%'.$request->query->get('title').'%')
                ->getQuery()
                ->getResult();    
            }
            else if($request->query->get('auteur')!='' ){
                $result = $this->getDoctrine()->getRepository(livre::class)->findByAuteur($request->query->get('auteur'));
            }

            else{
                $result = $this->getDoctrine()->getRepository(livre::class)->findAll();
            }
            
            if ($result === null)
            return new View("there are no books", Response::HTTP_NOT_FOUND);
            return $result;
            
        }

    /**
    * @Rest\Get("/books/{id}")
    */
    public function getBookDescription($id)
    {
        $result = $this->getDoctrine()->getRepository(livre::class)->find($id);
        
        if ($result === null)
        return new View("there are no books", Response::HTTP_NOT_FOUND);
        return $result->getDescriptif();
        
    }



    /**
    * @Rest\Post("/books")
    */
    public function addLivre(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $auteur = new auteur();
        $auteur->setNom('Adolf ');
        $auteur->setPrenom('Hitler');
        $auteur->setEmail('adolf@heni.com');
        $em->persist($auteur);

        $titre = $request->get('titre');
        if(empty($titre) )
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data = new livre();
        $data->setTitre($titre);
        $data->setDescriptif("Mein Kampf is a 1925 autobiographical book by Nazi Party leader Adolf Hitler.");
        $data->setISBN("213541");
        $data->setDateEdition(new \DateTime());
        $data->setAuteur($auteur);
        
        $em->persist($data);
        $em->flush();
        return new View("Task Added Successfully", Response::HTTP_ACCEPTED);
    }

    /**
    * @Rest\Put("/books/{id}")
    */
    public function misAJourLivre(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $livre = $entityManager->getRepository(livre::class)->find($id);
    
        if (!$livre) {
            throw $this->createNotFoundException(
                'No livre found for id '.$id
            );
        }

        $titre = $request->get('titre');
        $description = $request->get('desc');
        $isbn = $request->get('isbn');

        $livre->setDescriptif($description);
        $livre->setISBN("213541");
        $livre->setTitre($titre);
        $entityManager->flush();
    
        return new View("Livre mis a jour");
    }

    /**
    * @Rest\Patch("/books/{id}")
    */
    public function misAJourTitreLivre(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $livre = $entityManager->getRepository(livre::class)->find($id);
    
        if (!$livre) {
            throw $this->createNotFoundException(
                'No livre found for id '.$id
            );
        }

        $titre = $request->get('titre');
        $livre->setTitre($titre);
        $entityManager->flush();
    
        return new View("Titre de Livre mis a jour");
    }

     /**
    * @Rest\Delete("/books/{id}")
    */
    public function supprimerLivre(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $livre = $entityManager->getRepository(livre::class)->find($id);
        $entityManager->remove($livre);
        $entityManager->flush();
    
        return new View("Livre supprimé");
    }

}
?>