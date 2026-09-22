<?php
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// "Banco de dados" em memória — precisa ser por referência (&) para PUT/DELETE funcionarem
$tarefas = [
    ["id" => 1, "titulo" => "Estudar Slim Framework", "concluida" => false],
    ["id" => 2, "titulo" => "Fazer a Aula 9",        "concluida" => true]
];

/* ============================================================
   GET /tarefas — lista todas
   ============================================================ */
$app->get('/tarefas', function ($request, $response) use ($tarefas) {
    $response->getBody()->write(json_encode($tarefas));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

/* ============================================================
   GET /tarefas/{id} — busca uma tarefa
   ============================================================ */
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

/* ============================================================
   POST /tarefas — cria nova tarefa
   ============================================================ */
$app->post('/tarefas', function ($request, $response) use ($tarefas) {
    $dados = $request->getParsedBody();

    $novoId = 1;
    foreach ($tarefas as $t) {
        if ($t['id'] >= $novoId) $novoId = $t['id'] + 1;
    }

    $novaTarefa = [
        "id"        => $novoId,
        "titulo"    => $dados['titulo']    ?? "Sem título",
        "concluida" => $dados['concluida'] ?? false
    ];

    $response->getBody()->write(json_encode($novaTarefa));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});

/* ============================================================
   PUT /tarefas/{id} — atualiza tarefa existente
   ============================================================ */
$app->put('/tarefas/{id}', function ($request, $response, $args) use (&$tarefas) {
    $id    = (int) $args['id'];
    $dados = $request->getParsedBody();

    foreach ($tarefas as &$tarefa) {
        if ($tarefa['id'] === $id) {

            // Atualiza somente os campos enviados no corpo
            if (isset($dados['titulo'])) {
                $tarefa['titulo'] = $dados['titulo'];
            }
            if (isset($dados['concluida'])) {
                $tarefa['concluida'] = $dados['concluida'];
            }

            $response->getBody()->write(json_encode($tarefa));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        }
    }

    $response->getBody()->write(json_encode(['erro' => 'Tarefa não encontrada']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
});

/* ============================================================
   DELETE /tarefas/{id} — remove tarefa
   ============================================================ */
$app->delete('/tarefas/{id}', function ($request, $response, $args) use (&$tarefas) {
    $id = (int) $args['id'];

    foreach ($tarefas as $indice => $tarefa) {
        if ($tarefa['id'] === $id) {
            array_splice($tarefas, $indice, 1); // remove do array
            return $response->withStatus(204);  // 204 = sucesso sem corpo
        }
    }

    $response->getBody()->write(json_encode(['erro' => 'Tarefa não encontrada']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
});

$app->run();