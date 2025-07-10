# Design Document
The current implementation of the app has these client side routes.
Changes will be made as development continues, but the functionality
must carry over.
## Scores
Will be able to search for matches and view the results
## Matches 
These are actual shooting competitions
Can view open matches, but must create an account and be logged in to register
## Events
These are classes, not competition, but can be 'shooting' or 'non-shooting'
## Clubs
View any club in your area
## Shooters
View public profiles of shooters
## Profile
Edit your own profile

## Security
Generally speaking, read permissions don't require authentication, 
with a few exceptions with the User entity. This is to encourage 
people to find events to participate in before creating an account.
