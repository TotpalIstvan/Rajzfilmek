<?php

use Petrik\Rajzfilmek\Rajzfilm;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return function(Slim\App $app){
    $app->get('/rajzfilmek', function (Request $request, Response $response) {
        $rajzfilmek = Rajzfilm::osszes();
        $kimenet = json_encode($rajzfilmek);
        
        
        return $response->withHeader('Content-type', 'application/json')
        ->getBody()->write($kimenet);
    });

    $app->post('/rajzfilmek', function(Request $request, Response $response){
        $input = json_decode( $request->getBody(), true);
        $rajzfilm = new Rajzfilm();
        $rajzfilm->setAttributes($input);
        $rajzfilm->uj();

        $kimenet = json_encode($rajzfilm);

        return  $response
        ->withStatus(201)
        ->withHeader('Content-type', 'application/json') 
        ->getBody()->write($kimenet);
    });

    $app->delete('/rajzfilmek/{id}', 
    function(Request $request, Response $response, array $args){
        if(!is_numeric($args['id']) || $args['id'] <= 0){
            $ki =json_encode(['error' => 'Az ID pozitív egész szám kell legyen!']);
            $response->getBody()->write($ki);
            return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(400);
        }
       $rajzfilm = Rajzfilm::getById($args['id']);
       if ($rajzfilm === null) {
           $ki = json_encode(['error' => 'Nincs ilyen ID-jú rajzfilm']);
           return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(404);
       }
       $rajzfilm->torles();
       return $response
       ->withHeader('Content-type', 'application/json')
       ->WithStatus(204);
    });
};