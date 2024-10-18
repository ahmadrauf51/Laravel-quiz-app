
# Quiz Application - Laravel

This project is a fully-featured Quiz Application built using [Laravel](https://laravel.com/) and the [harishdurga/laravel-quiz](https://github.com/harishdurga/laravel-quiz) package. It offers a RESTful API for managing topics, quizzes, questions, and quiz attempts.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [Testing](#testing)
- [Security](#security)
- [Future Enhancements](#future-enhancements)
- [License](#license)

---

## Features

- Create and manage quiz topics, quizzes, questions, and options.
- Assign questions to quizzes and configure quiz settings (pass marks, attempts, negative marking).
- Secure API access using JWT authentication.
- Real-time quiz attempt handling, including answer submission and result calculation.

## Requirements

- PHP 8.0 or higher
- Laravel 9.x
- MySQL or PostgreSQL
- Composer
- [harishdurga/laravel-quiz](https://github.com/harishdurga/laravel-quiz)

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/yourusername/quiz-app-laravel.git
    cd quiz-app-laravel
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Set up environment variables:**

    Copy the `.env.example` file and rename it to `.env`. Update the environment variables (e.g., database credentials, app URL) as needed.

    ```bash
    cp .env.example .env
    ```

4. **Generate the application key:**

    ```bash
    php artisan key:generate
    ```

5. **Run the database migrations:**

    ```bash
    php artisan migrate
    ```

6. **Install the quiz package:**

    Install and publish the required assets for the [harishdurga/laravel-quiz](https://github.com/harishdurga/laravel-quiz) package.

    ```bash
    composer require harishdurga/laravel-quiz
    php artisan vendor:publish --provider="HarishDurga\LaravelQuiz\LaravelQuizServiceProvider"
    php artisan migrate
    ```

## Configuration

### JWT Authentication

To secure the API endpoints, JWT tokens are used. Install the [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth) package if it's not already installed.

```bash
composer require tymon/jwt-auth
php artisan jwt:secret
```

Add the following line to the `config/auth.php` file for the API guard:

```php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

## Usage

To start using the application, run the following command to start the local server:

```bash
php artisan serve
```

### Creating a Topic

Use the following endpoint to create a new topic.

```bash
POST /api/topics
{
  "name": "Science",
  "slug": "science"
}
```

### Creating a Quiz

```bash
POST /api/quizzes
{
  "name": "Physics Basics",
  "slug": "physics-basics",
  "topic_id": 1,
  "total_marks": 100,
  "pass_marks": 40,
  "max_attempts": 3
}
```

## API Endpoints

| **Endpoint**                         | **Method** | **Description**                                  |
|--------------------------------------|------------|--------------------------------------------------|
| **Topics**                           |            |                                                  |
| `/api/topics`                        | `POST`     | Create a new topic.                              |
| `/api/topics`                        | `GET`      | Fetch all topics.                                |
| `/api/topics/{id}`                   | `PUT`      | Update an existing topic.                        |
| `/api/topics/{id}`                   | `DELETE`   | Delete a specific topic.                         |
| **Quizzes**                          |            |                                                  |
| `/api/quizzes`                       | `POST`     | Create a new quiz.                               |
| `/api/quizzes`                       | `GET`      | Fetch all quizzes.                               |
| `/api/quizzes/{id}`                  | `PUT`      | Update an existing quiz.                         |
| `/api/quizzes/{id}`                  | `DELETE`   | Delete a specific quiz.                          |
| **Questions**                        |            |                                                  |
| `/api/questions`                     | `POST`     | Create a new question.                           |
| `/api/questions/{id}`                | `PUT`      | Update an existing question.                     |
| `/api/questions`                     | `GET`      | Fetch all questions.                             |
| `/api/questions/{id}`                | `DELETE`   | Delete a specific question.                      |
| **Options**                          |            |                                                  |
| `/api/questions/{id}/options`        | `POST`     | Add options to a question.                       |
| `/api/questions/{id}/options/{opt_id}`| `PUT`     | Update a specific option.                        |
| `/api/questions/{id}/options/{opt_id}`| `DELETE`  | Delete a specific option.                        |
| **Quiz Assignment**                  |            |                                                  |
| `/api/quizzes/{quiz_id}/assign-question`| `POST`  | Assign a question to a quiz.                     |
| `/api/quizzes/{quiz_id}/remove-question/{question_id}`| `DELETE`| Remove a question from a quiz.|
| **Quiz Attempt**                     |            |                                                  |
| `/api/quizzes/{quiz_id}/start`       | `POST`     | Start a quiz attempt.                            |
| `/api/quizzes/{quiz_id}/submit`      | `POST`     | Submit quiz answers and finish the quiz.         |
| `/api/quizzes/{quiz_id}/results`     | `GET`      | Fetch quiz attempt results.                      |

---

## Database Schema

The application uses a normalized relational database schema. Key tables include:

- `topics`: Stores quiz topics.
- `quizzes`: Stores quiz metadata.
- `questions`: Stores questions, each related to a quiz.
- `question_options`: Stores possible answers for each question.
- `quiz_attempts`: Logs user attempts, including answers submitted.

For a detailed schema, please refer to the [database schema diagram](link_to_diagram).

---

## Testing

The project includes unit and integration tests to ensure the correct functionality of the APIs and components.

Run the tests using:

```bash
php artisan test
```

---

## Security

Authentication is handled using JWT tokens. Make sure to generate the token using the login endpoint and include it in the `Authorization` header of subsequent API requests.

Example:

```bash
Authorization: Bearer <token>
```

---

## Future Enhancements

- Add **leaderboards** to display top quiz performers.
- Implement **badges and rewards** to incentivize user participation.
- Add support for **quiz analytics** and detailed performance tracking.

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
