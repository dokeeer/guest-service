# Guest Service

This project is a PHP microservice for managing guest data.

## Setup and Run

1. Clone the repository:
    ```bash
    git clone <repository-url>
    cd guest-service
    ```

2. Start the Docker containers:
    ```bash
    docker-compose up --build
    ```

3. The API will be accessible at `http://localhost:8000`.

## Tests executing

```bash
docker build -t guest-service-tests -f Dockerfile.test .
docker run --rm guest-service-tests
```

## API Endpoints

- `POST /guests` — Create a new guest.
- `GET /guests/{id}` — Retrieve a guest by ID.
- `GET /guests` — Get all guests.
- `PUT /guests/{id}` — Update a guest by ID.
- `DELETE /guests/{id}` — Delete a guest by ID.

## API Documentation
#### Endpoints
1. Create a Guest
    Endpoint: POST /guests
    Description: Creates a new guest.
    Request Format:
    Headers: Content-Type: application/json
    Body (JSON):
        {
            "name": "John",
            "surname": "Doe",
            "phone": "+1234567890",
            "email": "john.doe@example.com"
        }
    Response Format (JSON):
        Success:
            {
                "status": "Guest created",
                "id": 1
            }
        Error:
            {
                "error": "Error message"
            }
2. Retrieve All Guests
    Endpoint: GET /guests
    Description: Retrieves all guests.
    Response Format (JSON):
    Success:
        {
            "id": 1,
            "name": "John",
            "surname": "Doe",
            "phone": "+1234567890",
            "email": "john.doe@example.com",
            "country": "USA"
        }
    Error:
        {
            "error": "Error message"
        }
3. Retrieve a Guest by ID
    Endpoint: GET /guests/{id}
    Description: Retrieves a guest by ID.
    Parameters:
    id (path parameter): The ID of the guest to retrieve.
    Response Format (JSON):
    Success:
        {
            "id": 1,
            "name": "John",
            "surname": "Doe",
            "phone": "+1234567890",
            "email": "john.doe@example.com",
            "country": "USA"
        }
    Error:
        {
            "error": "Error message"
        }
4. Update a Guest by ID
    Endpoint: PUT /guests/{id}
    Description: Updates a guest by ID.
    Parameters:
    id (path parameter): The ID of the guest to update.
    Request Format:
    Headers: Content-Type: application/json
    Body (JSON):
        {
            "name": "John",
            "email": "john.new@example.com"
        }
    Response Format (JSON):
    Success:
        {
            "status": "Guest updated"
        }
        Error:
        {
            "error": "Error message"
        }
5. Delete a Guest by ID
    Endpoint: DELETE /guests/{id}
    Description: Deletes a guest by ID.
    Parameters:
    id (path parameter): The ID of the guest to delete.
    Response Format (JSON):
    Success:
        {
            "status": "Guest deleted"
        }
    Error:
        {
            "error": "Error message"
        }
#### Error Codes
    400 Bad Request: The request was invalid.
    404 Not Found: The requested resource was not found.
    405 Method Not Allowed: The HTTP method is not allowed for the requested resource.
    500 Internal Server Error: An error occurred on the server.