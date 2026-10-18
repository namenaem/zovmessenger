# ZOV messenger 

Abandoned project I made in 2025 or 2024. I I think I stole someone's code and started to build over it... A simple messenger on pure PHP and SQL. Back then I did not use GitHub much now I decided to start using it and I decided to upload it. Have fun. Idk if it works or not, I hope it does lol

## Requirements

php
mysql or mariadb idk

## 1. Get the code
just clone the code....

## 2. Create the database

create the database how you want but it must use schema from dbdump.sql

## 3. Configure the connection
the most important part
go to /app folder and edit the db.conn.php and enter your DB credentials.

## 4. Upload & permissions

just dump all the files into your web root folder (`public_html/` or whatever). keep the folders where they are or it breaks. 

also the `uploads/` folder needs to be writable because profile pics go there. 

## 5. Run it

- **Local:** run `php -S localhost:8000` in the terminal then open http://localhost:8000
or host it how you want idk.. 

make an account, upload a pic, or just use the default.

## How it works (quick tour)

| Path | Purpose |
|---|---|
| `app/http/auth.php` | login handler |
| `app/http/signup.php` | registration handler (incl. photo upload) |
| `app/ajax/insert.php` | saves a sent message |
| `app/ajax/getMessage.php` | returns new messages (polled every second) |
| `app/ajax/search.php` | user search for the sidebar |
| `app/ajax/update_last_seen.php` | keeps `last_seen` fresh |
| `app/helpers/*` | small functions: chats, conversations, "time ago" formatting |

frontend uses jquery to check for new messages every 1 second. conversation list refreshes every 5 seconds.

## Banning a user

if someone is annoying just ban them in the database directly. 

```sql
UPDATE users SET banned = 1 WHERE username = 'someone';
```
...there is no admin.. sorry.. i mean it's a project I made in 2025 as a joke and for practice.

## License

MIT License - check the LICENSE file. Do whatever you want with this code, but don't blame me if your database catches fire.
