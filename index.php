<?php
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$tarefas = [
    ["id" => 1, "titulo" => "Estudar Slim Framework", "concluida" => false],
    ["id" => 2, "titulo" => "Fazer a Aula 9", "concluida" => true]
];

$app->get('/tarefas', function ($request, $response) use ($tarefas) {
    $response->getBody()->write(json_encode($tarefas));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/tarefas/{id}', function ($request, $response, $args) use ($tarefas) {
    $id = (int) $args['id'];
    foreach ($tarefas as $tarefa) {
        if ($tarefa['id'] === $id) {
            $response->getBody()->write(json_encode($tarefa));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    }
    $response->getBody()->write(json_encode(['erro' => 'Tarefa não encontrada']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
});

$app->post('/tarefas', function ($request, $response) use ($tarefas) {
    $dados = $request->getParsedBody();
    $novoId = 1;
    foreach ($tarefas as $t) {
        if ($t['id'] >= $novoId) $novoId = $t['id'] + 1;
    }
    $novaTarefa = [
        "id"        => $novoId,
        "titulo"    => $dados['titulo'] ?? "Sem título",
        "concluida" => $dados['concluida'] ?? false
    ];
    $response->getBody()->write(json_encode($novaTarefa));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});

$app->run();