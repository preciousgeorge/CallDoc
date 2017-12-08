# CAll DOC API

 Basic End Point groups
 
 * user - for singup, login, list, profile
 * patient - list all/doctors, list own/doctors, consult, unconsult (leave doctors consultation list)
 * doctor - list (patients), block (patient), remove-patient/{patient} (patient)
 * question - list all, ask 
 * answer - get answer, add answer

## Start UP Application

```
:first

git clone https://github.com/preciousgeorge/CallDoc.git && cd CallDoc

:then 

composer install 
```


==== To run the server ====

``` php -S localhost:8888 -t public public/index.php ```

http://localhost:8888/api/v1/ would be the resultant url

### Signup
```/user/signup```

#### Method: POST

data:
     
    {
 	  "email" : "a valid email",
      "password" : "password",
      "name" : "firstname surname",
      "role_id": role_id
    }
    
    e.g
    
    {
      "email" : "mg@gmail.com",
      "name" :  "PG noms",
      "password" : "pass",
      "role" : 1
 	}

    
response :
   ```
   {
    "success": "User created successfully",
    "data": {
        "id": 86,
        "email": "mg@gmail.com",
        "password": "$2y$10$8pPg4YZ7g8BU9mH8Z6n8v.GP.hpXMVBBspsRNv4r0fDrfS/H2M4Ty",
        "name": "PG noms",
        "role_id": 1,
        "created_at": "2017-12-08 11:24:20",
        "updated_at": "2017-12-08 11:24:20"
    }
 }
 ```
 
 ### Login
 
 #### Method: POST
 
 ```/user/login```
 
 data:
 
 	{
      "email" : "a valid email"
      "password" : "password"
    }
 
 
 response :
 		
    {
          "token": 	"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE1MTI3MzI2MjUsImV4cCI6MTUxNDAyODYyNSwibmJmIjoxMzU3MDAwMDAwfQ.wAS34CPgWgijQ8CRULJ09O2PtV9dAEmbLrC1Mb_wNi4"
     }
 	
 
 

### Patient

To acess the group endpoint for patient the user has to have logged in and gotten a `jwt token`. The token is then passed to the the api via the headers of subsiquent requests sent. 

Get all doctors
 ```/patient/all/doctors```
 
#### Method: GET

Get own doctors - doctors that the client has chosen to consult with
```/patient/own/doctors```

#### Method: GET

Consult with doctor - the user can add doctors to the consult list
```/patient/consult```
#### Method: POST

data:
	
    {
      "doctor" : (int)id
    }
    
 response:
 	
    {
      'success' : 'Record added successfully'
    }
        
 unConsult with doctor - the user can take doctors out of the consult lists
 ```/patient/unconsult/{id}```
 
 #### Method: DELETE
 
 response:
 
 	{
         'success' : 'Record removed successfully' 
    }
    
    
### Doctor
List patients - gets lists of patients that have selected the doctor as their consultant

```/doctor/list```

#### Method: GET

response:

    {
        "success": "patients records fetch successful",
        "data": [
            {
                "id": 8,
                "email": "presh1@gmail.com",
                "password": "$2y$10$dYpL4YUjqcCWip0KCJr1O.8XDW.BEhyRB8p27YtfaKdEi5oCT1eEq",
                "name": "Precious George",
                "role_id": 1,
                "created_at": "2017-12-06 11:46:51",
                "updated_at": "2017-12-06 11:46:51"
            }
        ]
    }
    
    
 Block patients from consulting doctor

 ```/doctor/block``` 
 #### Method: POST
 data:
    
    {
       'patient' : (int) id
    }
     
 response :
 
 	{
     'success' : 'Patient Blacklisted successfully'
    }

Remove user from consultation list

```/remove-patient/{patient}```

{patient} = (int) user_id

#### Method: DELETE

reponse: 

	{
       'success' : 'Record removed successfully'
    }
    
    
    


### Answer
Fetch all questions or a particular question using an optional id argument

```question/all[/{id}]```

#### Method: GET

response:

	{
        "success": "questions",
        "data": [
            {
                "id": 1,
                "user_id": 8,
                "question": "how to i treat swollen feet",
                "created_at": null,
                "updated_at": null
            },
            {
                "id": 2,
                "user_id": 8,
                "question": "How to treat small constant headaches",
                "created_at": "2017-12-08 03:16:20",
                "updated_at": "2017-12-08 03:16:20"
            },
            {
                "id": 3,
                "user_id": 10,
                "question": "HOw do i change my hair color",
                "created_at": "2017-12-08 09:38:52",
                "updated_at": "2017-12-08 09:38:52"
            }
          ]
       }
       
       
 Add a question i.e ask a question
 
 ```/question/ask```
 
 #### Method: POST
 
 data : 
 
 	{
      "question" : "Question you wish to pose"
    }
    
    
 response :
 
     {
        'success' : 'Created Successfully', 
        'data' : [{'id' : question_id}]
     }


### Question

View an answer

```/answer/{question_id}```

#### Method: GET

response :

	{
    "success": "Request successful",
    "data": [
        {
            "id": 1,
            "question_id": 2,
            "doctors_id": 10,
            "answer": "Take pills my guy",
            "created_at": "2017-12-08 10:19:41",
            "updated_at": "2017-12-08 10:19:41"
        }
    ]
    }



Add an answer i.e answer a question

```/answer/add```

data: 

	{
      "question" : "question_id"
      "answer" : "text answer to the question"
    }
    
    
response:

	{
      'success' : 'Question answered successfully'
    }
    
    
 ## DATABASE set up
 Go to the phinx.yml file in the root directory to setup the database variable to match your environment
 
 The run 
 
 ```php vendor/bin/phinx migrate -c config-phinx.php```
 
 To migrate all tables to the database
 
 Import the sql file (call_doc_roles_tab.sql) for user roles found in the root directory
 
 and you are pretty much ready to roll
 








 