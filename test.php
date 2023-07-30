<?php

class JsonPlaceholderAPI
{
    private $base_url = 'https://jsonplaceholder.typicode.com';

    // Метод для выполнения HTTP запросов
    private function makeRequest($url, $method = 'GET', $data = null)
    {
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
        ];

        if ($data) {
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    // Метод для получения пользователей
    public function getUsers()
    {
        $url = $this->base_url . '/users';
        return json_decode($this->makeRequest($url), true);
    }

    // Метод для получения постов пользователя
    public function getUserPosts($userId)
    {
        $url = $this->base_url . "/users/{$userId}/posts";
        return json_decode($this->makeRequest($url), true);
    }

    // Метод для получения заданий пользователя
    public function getUserTodos($userId)
    {
        $url = $this->base_url . "/users/{$userId}/todos";
        return json_decode($this->makeRequest($url), true);
    }

    // Метод для работы с конкретным постом (добавление / редактирование / удаление)
    public function managePost($postId, $title, $body, $userId, $method = 'GET')
    {
        $url = $this->base_url . "/posts/{$postId}";

        $post_data = json_encode([
            'title' => $title,
            'body' => $body,
            'userId' => $userId
        ]);

        return json_decode($this->makeRequest($url, $method, $post_data), true);
    }
}

// Пример использования класса:

$api = new JsonPlaceholderAPI();

// Получить список пользователей
$users = $api->getUsers();
print_r($users);

// Получить посты для пользователя с ID = 1
$userPosts = $api->getUserPosts(1);
print_r($userPosts);

// Получить задания для пользователя с ID = 1
$userTodos = $api->getUserTodos(1);
print_r($userTodos);

// Добавить новый пост
$newPost = $api->managePost(null, 'Новый пост', 'Содержание нового поста', 1, 'POST');
print_r($newPost);

// Обновить пост с ID = 1
$updatedPost = $api->managePost(1, 'Обновленный пост', 'Содержание обновленного поста', 1, 'PUT');
print_r($updatedPost);

// Удалить пост с ID = 1
$deletedPost = $api->managePost(1, null, null, null, 'DELETE');
print_r($deletedPost);
