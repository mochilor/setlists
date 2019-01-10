# Setlists API

This project is an exercise on DDD, Hexagonal Architecture and Clean Code principles. 

## Purpose

The aim of the API is managing "set lists" for a music band. The set of songs that the band will play on a show
is usually decided beforehand. This set list is then printed in a piece of paper and placed near the musician, that 
refers to it during the show in order to know what is the next song. Well, 
[Wikipedia](https://en.wikipedia.org/wiki/Set_list) has a better explanation :)

## Features

The API is a Crud system where a client can connect to create, read, update and delete songs and their corresponding set 
lists. A set list is made with one or more acts, which in turn have one or more songs each. The user can create a song 
and after that add it to one or more set lists. Full list of features:

- Create a song
- Get a song.
- Get a collection of songs, filtered by title and a range.
- Update a song.
- Delete a song. (only if the song does not belong to a set list).
- Force-delete a song. (no matters if the song belongs to a set list or not).
- Create a set list
- Get a set list.
- Get a collection of set list, filtered by name and a range.
- Update a set list.
- Delete a set list.
- Basic login system.

More features are planned:

- Statistics for each song or set list.
- Sort collections of entities by any column.

## Internals

The code is divided in three layers: Domain, Application and Infrastructure. The selected framework is Lumen, but it 
should be easy to move the three layers to any other framework installation. 

The Domain layer has the main Entities (Song and Setlist) and all the Business logic. The Application layer has the 
different use cases, and the Infrastructure layer is responsible to communicate the Application with the outer world.

There are two available and fully functional database drivers (PDO and Eloquent). Also, as a CQRS approximation, I 
decided to persist the Setlists with all their data in another table that can be optionally used for reading purposes. 

All the code in `src` folder (the main code of the application) is covered with unit tests (except repositories 
classes). Also, Behat tests are used to test the functionality of the application.

The login system is handled entirely by the framework and has nothing to do with the layers of the application. I 
decided it this way to keep the project as simple as possible. The user logs in the application at `/api/auth/login`. 
The API then returns a token that the client must include in the url with each request.

## Documentation

I used Swagger for documenting each use case. It's accessible at `/api/documentation`. 

:D
