<?php

namespace App\Utils;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

trait Utils{

    public function GetJsonResponse(Request $request, $var){
        $encoder = new JsonEncoder();
                $defaultContext = [
                    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                        return $object->getId();
                    },
                ];
                $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
                $serializer = new Serializer([$normalizer], [$encoder]);
                $data = $request->getContent();
                $data = $serializer->serialize($var, 'json');
                $response = new Response($data);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
    }
}
?>