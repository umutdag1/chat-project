# Chat Program API
To Start the Server

```sh
cd /api
php -S localhost:8888
```

## Requests
Create a Chat Program API

It must Includes:

- User can create chat groups (Groups are considered as public)
- User can join these groups (can join any group)
- User can send message within the groups the user's joined
- User should be able to list all the messages within a group


## API Example
Respectively, API Example:

Request URL : http://localhost:8888

| EndPoint | Method | Usage | Feature |
| ------ | ------ | ------ | ------ |
| /api/users | GET | No Parameters | Getting All Users|
| /api/user/add | POST  | JSONBody : { "username": "john", "email": "john@example.com", "password": 1234  } | User Registering Based On JSONBody Variables Prepared To Be Sent to Server |
| /api/users/online/1 | PATCH | JSONBody : {"banned_datetime" : "0000-00-00 00:00:00", "last_online_datetime" : "2023-05-04 23:20:00", "is_online" : 1, "is_banned" : 0, "status": 1} | Set User Status to Online |
| /api/chatgroup/create | POST | JSONBody : { "name": "group_1", "created_by": 1 }  | Creating a chat group created by any user |
| /api/chatgroup/join| POST | JSONBody : { "chat_group_id": 1, "user_id" : 2}  | Join a Group Created By Other Users |
| /api/messages/1/user/1| POST | No Parameters | Get All Messages In That Group Requested By User |


## Example Requests
```sh
POST: http://localhost:8888/api/users/add
{
    "username" : "john",
    "email" : "john@example.com",
    "password" : "1234"
}

PATCH: http://localhost:8888/api/users/online/1
{
    "banned_datetime": "0000-00-00 00:00:00",
    "last_online_datetime": "2023-05-03 02:15:00",
    "is_online": 1,
    "is_banned": 0,
    "status": 1
}

POST: http://localhost:8888/api/chatgroup/create
{
    "name" : "johngroup",
    "created_by" : 1
}

POST: http://localhost:8888/api/users/add
{
    "username" : "jack",
    "email" : "jack@example.com",
    "password" : "12345"
}

PATCH: http://localhost:8888/api/users/online/2
{
    "banned_datetime": "0000-00-00 00:00:00",
    "last_online_datetime": "2023-05-03 02:18:00",
    "is_online": 1,
    "is_banned": 0,
    "status": 1
}

POST: http://localhost:8888/api/chatgroup/join
{
    "chat_group_id": 1,
    "user_id": 2
}

POST: http://localhost:8888/api/message/send
{
    "chat_gmember_id": 2,
    "chat_group_id": 1,
    "message": "Hello! This is a test message"
}

GET http://localhost:8888/api/messages/1/user/2
No parameters
```


## Notes
- To run successful for some endpoints in request, you must first change the user's online status to 1 by requesting "/api/users/online/".