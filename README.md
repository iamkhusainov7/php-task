## Running and usage

- step 1: in the command line run "composer install".
- step 2: php bin/console eloquent:migrate.
- step 3: php bin/console app:create_country
- step 4: You can use postman for this step to make an API request. API Endpoint: `/city`, method: "POST". You should send any form data, that includes name and    country id.
- step 5. API Endpoint: `/city`, method: "GET". Retreives cities.
- step 6. API Endpoint: `/city/{id}`, method: "GET". Retreives the city by id.
- step 7. API Endpoint: `/country/{canonName}`, method: "GET". Retreives the city by cannonical name.
