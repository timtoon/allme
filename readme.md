## Running the code

This is written using the Laravel MVC framework, so code can be run using the following command:

    php artisan serve
    
This will load the site on http://127.0.0.1:8000


## Order handling

Orders are all handled by the `/system` url, which routes/web.php sends to the app/Http/Controllers/Controller.php class. The index() function of this class switches to loading different functions in the SlotController class depending on the value of `order_type` sent to it. SlotController then manipulates data in the /app/Http/Slot class.

Because the slots pertain to Stylist and Client classes, I created those models, as well as controllers for each -- though they are not used in this example.

Splitting up the classes and their controllers is done to adhere to the model/view/controller design pattern, though it is possible to put the functionality into the Slot class itself.


## Storing data

The database migrations are in database/migrations/ and will create the necessary schema. In this case, the `slots` table.


## SlotController functions

The Controller::index() function expects an array of arrays in JSON format, and returns a success/failure flag for each handled order_id.

SlotController::addSlot() will add a new slot for a stylist, provided there isn't already one.

SlotController::removeSlot() will delete the specified slot, provided is exists and does not have a client.

SlotController::bookAppointment() will add the client_id to an existing slot that is not already taken.

SlotController::cancelAppointment() will remove the client_id from an existing slot, provided it already exists for that time.


## Tests

There are integration tests for all these functions in tests/Feature/. They can be run using:

    phpunit
    
Thanks for reading!