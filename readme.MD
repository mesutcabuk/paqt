- A General structure created for 
-- Database migrations for all required entities
-- Models, controllers, and API routes
-- Logging (Monolog, JSON format)
-- Exception handling
-- Warning & alerting system (email/SMS/Slack-ready)
-- Docker setup
-- GitHub CI/CD workflow
-- Ansible deployment playbook



Decisions & Assumptiona
- used integer as resident id, to be able to used by clients, wmo means medical clients, needs to be as simple as possible
- used postcode to define parcels in a city
- normalised some tables, like city etc. to keep it clean, one entity like street instead of street name, house numner, additions etc..
- did not add any indexes to any db table;s to keep it clean.. 
- a resident can chanfge address, so we need the latest address, the rest can be found from rides/bookings table
- a simple ansible script added to show github release idea's
- a simple docker file added.. 
- first started with php 8.1 then decided to move to 8.4 to the latest stable
- since it's for the Netherlands I've removed country from tables on purpose to keep code smaller
- secrets can be moved out of the repo, and stored in secure systems like keeper, and can be updated during the release
- normally default budget should be different per resident, but ignored to keep code smaller!



API Functionalities
- A call center must be able to retrieve all residents of the given gemeente.
- A call center must be able to book a ride for a resident.
- A taxi company must be able to retrieve all rides for which they are responsible.
- The budget of active residents must be automatically reset annually.
