## About Project car_workshop
car workshop is a project for testing purposes. the project is divided into two. backend folder contains backend projects using laravel and another frontend folder contains frontend projects using react-admin.
## Backend setting
point cmd to the backend/car-workshop folder then run 
```
composer install 
```
after that please duplicate the .env.example file to .env and set the database and mail sections. then go back to cmd run the command 
```
php artisan migrate 
php artisan db:seed 
```
once all is done run 
```
php artisan serve 
```
to run the server. The link that appears will be used for setting the frontend
## Frontend setting
point cmd to the frontend/car-workshop folder then run 
```
yarn install
npm install 
```
after that please paste the link from the backend server in the src/App.js file on line 24 then add /api/admin. for example the backend server is http://localhost:8000 then what is written on line 24 is 
```
const dataProvider = simpleRestProvider('http://localhost:8000/api/admin', httpClient);
```
after that the system can be used.
### Usage
Default user:
email: admin@mail.com; password:password; role: admin
email: owner@mail.com; password:password; role: owner
email: mechanic@mail.com; password:password; role: mechanic
role : admin
- customers (master)
- mechanics (master)
- services (master)
- cars (master)
- repair (transaction: record repairs and service recommendations)
- repair-service (transaction to assign mechanic)
- repair-inspect (transaction to inspect the service)
role : owner
- repair (look status of repair)
role : mechanic
- repair-mechanic (transaction to status of service)
### Logic
when the customer comes, the admin will input it to the master customer and master car for the car details. if the customer is not a new customer then it does not need to be inputted.
then the admin adds repair data to the repair menu after being saved, the repair details will appear and then if it is approved by the customer, change the status to approved and save.
Next, go to the repair service menu to give a mechanical task and then save.
then try to login as a mechanic, a mechanic repair menu will appear. if the repair is complete then you can change the status to done.
after all services are finished try logging in as admin then go to the repair-inspects menu.
if there is a complaint from the owner, please enter it in the repair service save. then go to the repair services menu to assign a mechanic, then the mechanic must complete the repair again as in the previous step.
if there are no complaints then change the status to done.
then the system will send an invoice to the email and the process is complete