

## SparkTech Task — Laravel Developer Assessment

This project is developed as part of the Laravel Developer assessment for **Spark Tech Agency**.

It implements a simple social post system where:
- Users can create posts.
- Other users receive notifications and emails when a new post is created.
- Users can like and comment on posts.
- Post owners get notified when their posts are liked or commented on.



## Project Setup Guide

### Clone the Repository
- bash
- git clone https://github.com/<your-username>/sparktech-task.git
- cd sparktech-task

## Install Dependencies
- composer install

### Configure Environment (.env)

- DB_DATABASE=sparktech_task
- DB_USERNAME=root
- DB_PASSWORD=

// if you change MAIL_MAILER -> php artisan config:cache
// MAIL_MAILER=log / MAIL_MAILER=smtp

- MAIL_MAILER=log
- MAIL_HOST=sandbox.smtp.mailtrap.io
- MAIL_PORT=2525
- MAIL_USERNAME=552158d10c6549
- MAIL_PASSWORD=5680c34714cb6a
- MAIL_ENCRYPTION=null
- MAIL_FROM_ADDRESS="hellosparktech@example.com"
- MAIL_FROM_NAME="SparkTech Task"

## Run Migrations & Seeders

- php artisan migrate --seed

## Run the Application

- php artisan serve

### API Endpoints
## Create a Post

- POST - http://127.0.0.1:8000/api/posts
    Request Body:
    {
        "user_id": 1,
        "title": "My First Post",
        "content": "This is a sample post."
    }
- Response:
    {
        "message": "Post created successfully & notifications send!",
        "post": {
            "id": 1,
            "title": "test mailtrap title",
            "content": "test mailtrap content",
            "user": {
                "id": 1,
                "name": "John Doe"
            }
        }
    }


## Get All Posts
- GET - http://127.0.0.1:8000/api/posts
    -Response
    [
        {
            "id": 1,
            "user_id": 3,
            "title": "test mailtrap title",
            "content": "test mailtrap content",
            "created_at": "2025-11-12T10:23:12.000000Z",
            "updated_at": "2025-11-12T10:23:12.000000Z",
            "likes_count": 1,
            "comments_count": 0
        },
    ]

## Like / Unlike a Post

- POST - http://127.0.0.1:8000/api/posts/{id}/like-unlike

    - http://127.0.0.1:8000/api/posts/1/like-unlike
    - http://127.0.0.1:8000/api/posts/2/like-unlike

    Request Body:
    {
        "user_id": 2
    }

    - Response 
    {
        "message": "Post liked successfully",
        "post_id": 2,
        "likes_count": 0
    }

    - Notification Example (database):
    {
        "post_id": 1,
        "title": "My First Post",
        "message": "Your post \"My First Post\" was liked by user id: 2"
    }

## Add a Comment
- POST -  http://127.0.0.1:8000/api/posts/{id}/comments
    - http://127.0.0.1:8000/api/posts/1/comments
    - http://127.0.0.1:8000/api/posts/2/comments

    - Request Body:
    {
        "user_id": 3,
        "comment": "Nice post!"
    }
    - Response
    -json
    {
        "message": "Comment added successfully",
        "post_id": {
            "user_id": 2,
            "comment": "Test Comment",
            "post_id": 10,
            "updated_at": "2025-11-13T05:03:11.000000Z",
            "created_at": "2025-11-13T05:03:11.000000Z",
            "id": 8,
            "post": {
                "id": 10,
                "user_id": 4,
                "title": "test mailtrap title",
                "content": "test mailtrap content",
                "created_at": "2025-11-12T11:08:41.000000Z",
                "updated_at": "2025-11-12T11:08:41.000000Z",
                "user": {
                    "id": 4,
                    "name": "Brigitte Quigley",
                    "email": "kianna62@example.net",
                    "email_verified_at": null,
                    "created_at": null,
                    "updated_at": null
                }
            },
            "user": {
                "id": 2,
                "name": "Demarco Lind",
                "email": "grant.eino@example.org",
                "email_verified_at": null,
                "created_at": null,
                "updated_at": null
            }
        }
    }
    - Notification Example:
    {
        "post_id": 1,
        "title": "My First Post",
        "message": "Jane Doe commented: \"Nice post!\""
    }

## Get Comments of a Post
- GET - http://127.0.0.1:8000/api/posts/{id}/comments
      - http://127.0.0.1:8000/api/posts/1/comments
      - http://127.0.0.1:8000/api/posts/2/comments

    - Response
    {
        "post_id": 3,
        "comments": [
            {
                "id": 1,
                "user_id": 3,
                "post_id": 3,
                "comment": "test comment notification",
                "created_at": "2025-11-12T14:57:28.000000Z",
                "updated_at": "2025-11-12T14:57:28.000000Z"
            },
            {
                "id": 2,
                "user_id": 3,
                "post_id": 3,
                "comment": "test comment notification",
                "created_at": "2025-11-12T14:59:59.000000Z",
                "updated_at": "2025-11-12T14:59:59.000000Z"
            },
        ]
    }

### Notifications Overview
    event                   Receiver                    Channel

    Post Create             All other users            Mail + Database
    Post Liked              Post Owner                 Database
    Comment Added           Post Owner                 Database

-All notifications are stored in the notifications table.

### Models & Relationships

- User -> hasMany(Post), hasMany(Comment), hasMany(Like)
- Post -> belongsTo(User), hasMany(Comment), hasMany(Like)
- Comment -> belongsTo(User), belongsTo(Post)
- Like -> belongsTo(User), belongsTo(Post)

## Mail Configuration Note
- The project uses Mailtrap for email notifications in development.

- **Free Mailtrap accounts have a limit on emails per second**, so if multiple notifications are sent at once, a    `500 Internal Server Error` may occur.
- To avoid this, you can switch to **log driver** by updating `.env`:


### Created Components
- **Models:** User, Post, Like, Comment
- **Notifications:** PostCreateNotification, PostLikeNotification, CommentNotification
- **Controllers:** PostController, LikeController, CommentController
- **Mail:** Used `log` driver for local testing (Mailtrap also supported)
- **Seeder:** UserSeeder to create 5–10 dummy users

