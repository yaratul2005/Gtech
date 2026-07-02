# Great Endured Technology — API Tunnel Map
Governed by RBP (AGENTS.md)

This registry catalogs all external integrations and internal API endpoints.

---

## 1. Internal API Endpoints

### Contact Form Submission
* **Endpoint**: `/api/contact`
* **Method**: `POST`
* **Purpose**: Submits inquiries from the contact form to the leads database and triggers an email notification.
* **Authentication**: CSRF Token verification (`csrf_token`) + Rate-limiting middleware (max 5 requests per hour per IP).
* **Payload (multipart/form-data)**:
  * `name`: string (required)
  * `email`: email string (required)
  * `service`: string (optional, defaults to "general")
  * `message`: string (required)
  * `csrf_token`: string (required)
* **Response (application/json)**:
  * **Success (200 OK)**:
    ```json
    { "success": true, "message": "Thank you! Your message has been sent successfully." }
    ```
  * **Validation Error (400 Bad Request)**:
    ```json
    { "success": false, "message": "Please enter a valid email address." }
    ```
  * **Spam Block (429 Too Many Requests)**:
    ```json
    { "success": false, "message": "Too many submissions. Please try again in an hour." }
    ```
  * **Security Block (403 Forbidden)**:
    ```json
    { "success": false, "message": "CSRF verification failed." }
    ```

---

## 2. External Services Integrations

None currently defined.
- Future additions (e.g. Stripe, SMS gateways) must be documented here prior to codebase updates.
