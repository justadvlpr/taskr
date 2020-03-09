# taskr

Simply manage your daily tasks on a beautiful and intuitive UI.

---

This is a single page application, technologies used:
- Vue.js with Vuetify for the frontend.
- As I am a huge fan of Yii for the backend I used the new Yii3 (no official release yet, but this already shows what you can do with it).
- For the database, SQLite was used, it is located inside the **runtime** folder.

## Development

#### # Setup:

- `git clone https://github.com/justadvlpr/taskr.git`
- `docker-compose up -d`
- `docker-compose exec --user taskr app composer install`
- `docker-compose exec --user taskr app npm run dev`

#### # Create a user:

`docker-compose exec --user taskr app ./vendor/bin/yii user/create user password`

## OR

TODO

# API

```
POST /api/auth/login [ {"login": "", "password": ""} ]

POST /api/auth/verify ( returns the current user data if token is validated )

GET  /api/task                   ( list all the tasks )
     /api/task?filter=today      ( list todays tasks )
     /api/task?page=1&per-page=5 ( enables pagination when searching for tasks )

GET  /api/task/1  ( view the task with the id 1 )

POST /api/task    ( creates a new task )

PUT  /api/task/1  ( updates the task with the id 1 )
```
