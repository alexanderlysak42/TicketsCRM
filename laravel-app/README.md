# Tickets CRM

---

## Стек

- Laravel (backend)
- PostgreSQL
- Nginx
- Adminer

---

## Docker

### 1) Запуск контейнеров

```bash
docker compose up -d --build
```

Контейнеры из `docker-compose.yml`:

- `tickets-crm-app` — PHP/Laravel
- `tickets-crm-nginx` — Nginx
- `tickets-crm-db` — Postgres 16
- `tickets-crm-adminer` — Adminer

### 2) Установка зависимостей

```bash
docker exec -it tickets-crm-app composer install
```

### 3) Настройка окружения

Скопировать `.env`:

```bash
cp laravel-app/.env.example laravel-app/.env
```

> `.env.example` если уже указаны значения БД — они должны совпадать с параметрами из `docker-compose.yml`.

### 4) Ключ приложения

```bash
docker exec -it tickets-crm-app php artisan key:generate
```

### 5) Миграции и тестовые данные

```bash
docker exec -it tickets-crm-app php artisan migrate --seed
```

### 6) Storage symlink

```bash
docker exec -it tickets-crm-app php artisan storage:link
```

### 7) Laravel Breeze

```bash
docker exec -it tickets-crm-app npm install
```

```bash
docker exec -it tickets-crm-app npm run build
```

### 8) Swagger UI

```bash
docker exec -it tickets-crm-app mkdir -p public/vendor/swagger-api/swagger-ui
```

```bash
docker exec -it tickets-crm-app ln -s ../../../../vendor/swagger-api/swagger-ui/dist public/vendor/swagger-api/swagger-ui/dist
```

```bash
docker exec -it tickets-crm-app php artisan l5-swagger:generate
```

---

## URL

- Приложение: `http://localhost:8080`
- Виджет: `http://localhost:8080/widget`
- Manager UI:
  - `http://localhost:8080/dashboard`
  - `http://localhost:8080/manager/tickets`
- Adminer: `http://localhost:8081`

---

## Подключение к Postgres

Параметры из `docker-compose.yml`:

- Host: `localhost`
- Port: `5432`
- Database: `tickets-crm`
- User: `tickets-crm`
- Password: `secret`

---

## Seeders

При выполнении `php artisan migrate --seed` запускаются сидеры :

### Роли

- `admin`
- `manager`

### Пользователи

Пароль у обоих: **`password`**

- **Admin**
  - email: `admin@example.com`
  - password: `password`
  - role: `admin`

- **Manager**
  - email: `manager@example.com`
  - password: `password`
  - role: `manager`

> Доступ к manager-панели защищён middleware `manager`. Вход выполнен через стандартные auth-роуты Laravel (`/login`).

### Customers / Tickets / Attachments

- **Customers**: создаётся **30** клиентов (factory)
- **Tickets**: для каждого клиента создаётся **1–4** тикета (random)
- **Attachments**: для части тикетов создаются **0–3** вложения (seed text-файлы) и добавляются в media collection `attachments`

---

## Встраивание виджета (iframe)

Виджет доступен по роуту:

- `GET /widget`

Пример вставки на любой сайт:

```html
<iframe
  src="http://localhost:8080/widget"
  title="Tickets Widget"
  style="width: 380px; height: 520px; border: 0; border-radius: 12px; overflow: hidden;"
  loading="lazy"
></iframe>
```

> Если проект разворачивается не локально — заменить `http://localhost:8080` на домен.

---

## API

Маршруты описаны в `routes/api.php`.

Базовый URL :

- `http://localhost:8080/api`

### 1) Создать тикет из виджета

**POST** `/api/tickets`

#### Валидация (основные поля)

Тело запроса (JSON или `multipart/form-data`):

- `customer` (object, required)
  - `customer.name` (string, 2..120, required)
  - `customer.phone` (string, required) — формат: `+380501234567`  
    regex: `^\+[1-9]\d{7,14}$`
  - `customer.email` (email, optional, max 190)
- `subject` (string, 3..190, required)
- `message` (string, 3..5000, required)
- `files` (array, optional, max 10)
  - каждый файл: до **10MB**, MIME:
    - `image/jpeg`
    - `image/png`
    - `application/pdf`
    - `text/plain`

**Ограничение:**  
Нельзя отправлять больше **1 заявки в сутки** с одного телефона или email.

#### Пример: JSON (без файлов)

```bash
curl -X POST "http://localhost:8080/api/tickets" \
  -H "Content-Type: application/json" \
  -d '{
    "customer": {
      "name": "Alex",
      "phone": "+380501234567",
      "email": "alex@example.com"
    },
    "subject": "Проблема с оплатой",
    "message": "Не проходит платёж, ошибка 500."
  }'
```

#### Пример: multipart/form-data (с файлами)

```bash
curl -X POST "http://localhost:8080/api/tickets" \
  -F 'customer[name]=Alex' \
  -F 'customer[phone]=+380501234567' \
  -F 'customer[email]=alex@example.com' \
  -F 'subject=Проблема с оплатой' \
  -F 'message=Не проходит платёж, ошибка 500.' \
  -F 'files[]=@./example.png' \
  -F 'files[]=@./example.pdf'
```

#### Успешный ответ (200)

```json
{
  "status": "ok",
  "message": "Feedback is sent",
  "data": {
    "id": 123,
    "...": "TicketResource"
  }
}
```

#### Ошибка валидации (422)

Пример (неправильный телефон):

```json
{
  "message": "The customer.phone field format is invalid.",
  "errors": {
    "customer.phone": [
      "Phone format must be +380501234567"
    ]
  }
}
```

Пример (лимит 1 заявка в сутки):

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "customer.phone": [
      "Можно отправлять не более одной заявки в сутки с одного телефона или email."
    ]
  }
}
```

---

### 2) Статистика по тикетам

**GET** `/api/tickets/statistics`

Возвращает количество созданных тикетов за периоды:

- `last_24h`
- `last_7d`
- `last_30d`

#### Пример

```bash
curl "http://localhost:8080/api/tickets/statistics"
```

#### Ответ (200)

```json
{
  "status": "ok",
  "data": {
    "last_24h": 3,
    "last_7d": 25,
    "last_30d": 120
  }
}
```

---

## Manager UI (веб-интерфейс)

Маршруты описаны в `routes/web.php`:

- `GET /dashboard` — список тикетов (middleware: `auth`, `manager`)
- `GET /manager/tickets` — список тикетов
- `GET /manager/tickets/{ticket}` — просмотр тикета
- `PATCH /manager/tickets/{ticket}/status` — смена статуса (`new`, `in_progress`, `done`)

### Фильтры списка

В списке тикетов используются фильтры из query string:

- `date_from`
- `date_to`
- `status`
- `email`
- `phone`

---

## Команды

```bash

# повторно прогнать миграции/сидеры (осторожно: удаляет данные)
docker exec -it tickets-crm-app php artisan migrate:fresh --seed
```
