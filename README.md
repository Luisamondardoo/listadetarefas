# Webservice de Tarefas

**Curso:** Técnico em Desenvolvimento de Sistemas — SENAC
**Unidade Curricular:** Desenvolver Serviços Web
**Aluna:** Luisa Mondardo Lemos

---

## Sobre o projeto

Webservice REST feito em PHP com o framework Slim 4. Ele faz o CRUD completo (criar, ler, atualizar e deletar) de tarefas.

Os dados ficam salvos em um array PHP na memória. Então, se o servidor reiniciar, tudo volta ao começo.

---

## Endpoints

| Método | Rota | O que faz | Retorno |
|--------|------|-----------|---------|
| GET | `/tarefas` | Lista todas as tarefas | 200 |
| GET | `/tarefas/{id}` | Busca uma tarefa pelo id | 200 ou 404 |
| POST | `/tarefas` | Cria uma nova tarefa | 201 |
| PUT | `/tarefas/{id}` | Atualiza uma tarefa | 200 ou 404 |
| DELETE | `/tarefas/{id}` | Deleta uma tarefa | 204 ou 404 |

---

## Exemplos de uso

### Listar todas as tarefas

`GET /tarefas`

Retorno:

```json
[
  {
    "id": 1,
    "titulo": "Estudar para a prova",
    "concluida": false
  },
  {
    "id": 2,
    "titulo": "Entregar o trabalho",
    "concluida": true
  }
]