<? 
namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
	
     /** 
     * @Route("/api/getProduits", name="getProduits")
    */
    public function GetProduits(Request $request, ProduitRepository $produitRepository){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $data = $request->getContent();
        $var =$produitRepository->findProduits();
        $data =  $serializer->serialize($var, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/api/postProduit", name="postProduit")
     */
    public function PostProduit(Request $request)
    {
        $postdata = json_decode($request->getContent());
        $produit = new Produit();
        $produit->setNom($postdata->Nom);
        

        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();

        $response = new Response("ok");
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
}