# Endpoints

## User
- GET /user
- POST /user/register
- POST /user/{email}/password/reset/send
- GET /user/{id}
- PATCH /user/{id}
- PUT /user/{id}/email/change
- PUT /user/{id}/email/verify
- POST /user/{id}/email/send
- PUT /user/{id}/password/change
- PUT /user/{id}/password/reset

## Club
- GET /club
- GET /club/{id}
- PATCH /club/{id}

## Gathering
- GET /gathering
- GET /club/{idClub}/gathering/{id}
- POST /club/{idClub}/gathering
- PATCH /club/{idClub}/gathering/{id}

## Registration
- GET /gathering/{id}/registration
- GET /user/{id}/registration
- GET /user/{id}/registration/{id}
- POST /gathering/{id}/registration
- PATCH /user/{id}/registration/{id}
- DELETE /user/{id}/registration

## Score
- GET /gathering/{id}/score
- GET /user/{id}/score
- GET /user/{id}/score/{id}
- POST /user/{id}/score
- PATCH /user/{id}/score

## Video
- GET /user/{id}/video
- GET /user/{id}/video/{id}
- POST /user/{id}/video
- PATCH /user/{id}/video/{id}
- DELETE /user/{id}/video/{id}

## MatchType
- GET /match_type
- GET /match_type/{name}

## Division
- GET /division/{name}

## Classification
- GET /classification/{name}
