### Create a database
`create database lig_test`

### Migrate tables
`php artisan migrate`

### Please change .env
`DB_DATABASE=lig_test`

### To make the application running
`php artisan serve`

##### Please take note for login or auth:api please pass a bearer token this token will show after login
Example
```
curl --location --request POST "http://localhost:8000/api/posts" \
  --header "Accept: application/json" \
  --header "Content-Type: application/json" \
  --header "Authorization:Bearer 7u9THYeZP3Fnbrwt6XKK7JNLaz6DMI6O7kH2QLpYNvFh2VlBEVOjpjRKAzr8" \
  --data "{
	\"title\" : \"first post\",
	\"content\": \"content\",
	\"image\": \"http://path/to/image\"
}"
```
