# VDNH Afisha Backend

Backend API афиши мероприятий (Laravel 12, PHP 8.3).  
Модульная архитектура Domain / Application / Presentation.

## Требования

- Docker + Docker Compose
- Make (опционально)
- PHP 8.3+ и Composer — только если запускаете без Docker

## Быстрый старт (Docker)

```bash
cp .env.example .env
# сгенерируйте ключ после первого up, либо:
# docker compose -f docker-compose.local.yml run --rm php php artisan key:generate

make build
make up
make migrate-fresh   # миграции + сиды категорий и событий
make docs            # HTML / OpenAPI / Postman через Scribe
```

Сервисы:

| URL | Описание |
|-----|----------|
| http://localhost:8000 | API + Laravel |
| http://localhost:8000/docs | Scribe HTML-документация |
| http://localhost:8000/docs/collection.json | Postman collection |
| http://localhost:8000/docs/openapi.yaml | OpenAPI spec |
| http://localhost:8081 | Swagger UI |

Полезные команды:

```bash
make migrate          # только миграции
make seed             # сиды
make docs             # перегенерация документации
make exec             # shell в PHP-контейнере
make down             # остановить стек
make rebuild          # пересборка с нуля + migrate-fresh + docs
```

Формат ответа API:

```json
{
  "success": true,
  "message": null,
  "data": {}
}
```

Префикс API: `/api` (Laravel) + версия `/v1` для бизнес-методов.

---

## Health / служебные

### `GET /api/ping`

Проверка живости сервиса.

**Ответ:** `{ "pong": true }`

### `GET /api/timestamp`

Текущий unix-timestamp.

**Ответ:** `{ "timestamp": 1710000000 }`

---

## Мероприятия (`/api/v1/events`)

### `GET /api/v1/events`

Список мероприятий с фильтрами и пагинацией.

**Query-параметры:**

| Параметр | Тип | Описание |
|----------|-----|----------|
| `category_ids[]` | int[] | Фильтр по нескольким категориям (OR) |
| `date_from` | datetime | Начало интервала |
| `date_to` | datetime | Конец интервала |
| `page` | int | Страница (по умолчанию 1) |
| `per_page` | int | Размер страницы 1–100 (по умолчанию 10) |

**Пример:**

```http
GET /api/v1/events?category_ids[]=1&category_ids[]=2&date_from=2026-10-01&date_to=2026-10-31&page=1&per_page=10
```

**Ответ `data`:**

```json
{
  "items": [ /* EventResource */ ],
  "pagination": {
    "total": 100,
    "per_page": 10,
    "current_page": 1,
    "last_page": 10,
    "from": 1,
    "to": 10
  }
}
```

### `GET /api/v1/events/{id}`

Карточка мероприятия по ID.

### `POST /api/v1/events`

Создание мероприятия.

**Body (JSON):**

| Поле | Тип | Обязательно | Описание |
|------|-----|-------------|----------|
| `title` | string | да | Название |
| `slug` | string | нет | Если пусто — генерируется из `title` |
| `description` | string | нет | Описание |
| `starts_at` | datetime | да | Начало |
| `ends_at` | datetime | нет | Окончание (≥ `starts_at`) |
| `location` | string | нет | Место |
| `category_ids` | int[] | нет | Категории |

**Пример:**

```json
{
  "title": "Вечер на ВДНХ",
  "description": "Концерт под открытым небом",
  "starts_at": "2026-10-15 19:00:00",
  "ends_at": "2026-10-15 21:00:00",
  "location": "Павильон №1",
  "category_ids": [1, 2]
}
```

**Ответ:** `201` + `EventResource`.

### `PUT /api/v1/events/{id}`

Частичное обновление. Поля те же, что при создании; передаются только изменяемые.  
`category_ids: []` — отвязать все категории.

### `DELETE /api/v1/events/{id}`

Удаление мероприятия. **Ответ:** `{ "success": true, "data": null }`.

---

## Категории (`/api/v1/event-categories`)

### `GET /api/v1/event-categories`

Список всех категорий.

### `GET /api/v1/event-categories/{id}`

Категория по ID.

### `POST /api/v1/event-categories`

Создание категории.

| Поле | Тип | Обязательно | Описание |
|------|-----|-------------|----------|
| `name` | string | да | Название |
| `slug` | string | нет | Если пусто — из `name` |

```json
{ "name": "Концерты" }
```

### `PUT /api/v1/event-categories/{id}`

Обновление категории (`name`, `slug`).

### `DELETE /api/v1/event-categories/{id}`

Удаление категории.

---

## Модели ответа

**EventResource**

```json
{
  "id": 1,
  "title": "Вечер на ВДНХ",
  "slug": "vecher-na-vdnh",
  "description": "...",
  "starts_at": "2026-10-15T19:00:00.000000Z",
  "ends_at": "2026-10-15T21:00:00.000000Z",
  "location": "Павильон №1",
  "categories": [
    { "id": 1, "name": "Концерты", "slug": "kontserty" }
  ]
}
```

**EventCategoryResource**

```json
{ "id": 1, "name": "Концерты", "slug": "kontserty" }
```

---

## Структура проекта

```
app/
  Modules/Events/
    Domain/         # Models, DTOs, интерфейсы
    Application/    # Services, Repositories
    Presentation/   # Controllers, Requests, Resources, routes
  Shared/           # ApiResponse, AbstractDTO, Handler
```

Поток запроса: `FormRequest → DTO → Service → Repository → Resource → ApiResponse`.

---

## Postman

1. Импортируйте `public/docs/collection.json`
2. Либо скачайте: http://localhost:8000/docs/collection.json

Перегенерация:

```bash
make docs
# или
docker compose -f docker-compose.local.yml exec php php artisan scribe:generate --force
```

---

## Тесты

```bash
docker compose -f docker-compose.local.yml exec php php artisan test
```

Покрытие: CRUD событий/категорий, фильтр по нескольким категориям + диапазон дат + пагинация.

---

## Сиды

`php artisan db:seed` создаёт:

- категории: Концерты, Выставки, Экскурсии, Детям, Спорт, Фестивали
- 12 тестовых мероприятий со связями категорий
