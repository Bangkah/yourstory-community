# Your Story Community - Complete Backend API Documentation

## Table of Contents
1. [Overview](#overview)
2. [Technology Stack](#technology-stack)
3. [Setup & Installation](#setup--installation)
4. [Authentication](#authentication)
5. [API Endpoints](#api-endpoints)
6. [Rate Limiting](#rate-limiting)
7. [Response Format](#response-format)
8. [Error Handling](#error-handling)
9. [Database Schema](#database-schema)
10. [Test Credentials](#test-credentials)
11. [Troubleshooting](#troubleshooting)

---

## Overview

**Your Story Community** is a Laravel-based REST API for a community storytelling platform. Users can:
- Create, read, update, and delete stories
- Comment on stories (with nested/threaded replies)
- Like/unlike stories
- Follow/unfollow other users
- Receive notifications for interactions
- Admin users can manage content and users

---

## Technology Stack

| Component | Version |
|-----------|---------|
| Laravel   | 11.x    |
| PHP       | 8.4     |
| MySQL     | 8.0     |
| Docker    | Latest  |
| Sanctum   | 4.x     |

---

## Setup & Installation

### Prerequisites
- Docker & Docker Compose installed
- Terminal/CLI access
- 8GB RAM minimum

### Installation Steps

```bash
# 1. Navigate to project directory
cd /home/atha/Dokumen/myproject/yourstoryComunity

# 2. Start Docker containers
docker compose up -d --build

# 3. Generate application key
docker compose exec app php artisan key:generate

# 4. Run database migrations
docker compose exec app php artisan migrate --force

# 5. Seed sample data
docker compose exec app php artisan db:seed --force

# 6. Clear caches
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:cache
```

### Verification
```bash
# Check if containers are running
docker compose ps

# Test API
curl -H "Accept: application/json" http://localhost:8080/api/stories
```

**API URL**: `http://localhost:8080/api`

---

## Authentication

### Login
Obtain a Sanctum token for API requests.

```http
POST /auth/login
Content-Type: application/json
Accept: application/json

{
  "email": "admin@yourstory.local",
  "password": "password123"
}
```

**Response (200):**
```json
{  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@yourstory.local",
      "role": "admin",
      "created_at": "2024-01-01T10:00:00Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  }
}
```

### Using the Token

All protected endpoints require the `Authorization` header:

```http
GET /api/auth/me
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
Accept: application/json
```

### Logout

```http
POST /auth/logout
Authorization: Bearer {token}
Accept: application/json
```

---

## API Endpoints

### Response Format

All responses follow this standard format:

```json
{
  "success": true,
  "message": "Operation successful",
  "data": { },
  "meta": { }
}
```

### Rate Limits

| Endpoint Type | Limit | Window |
|---------------|-------|--------|
| Public Read | 60 | 1 min |
| Login | 5 | 1 min |
| Protected Read | 120 | 1 min |
| Content Creation | 30 | 1 min |
| Social Actions | 60 | 1 min |
| Admin | 60 | 1 min |

---

## Stories Endpoints

### List Stories (Public)

```http
GET /api/stories?search=term&author=name&role=admin&sort=latest&per_page=15
Accept: application/json
```

**Query Parameters:**
- `search` - Search in title and body
- `author` - Filter by author name
- `role` - Filter by user role (admin, moderator, member)
- `sort` - `latest` | `oldest` | `most_liked` | `most_commented` (default: latest)
- `per_page` - Items per page (max: 100, default: 15)

### Get Story (Public)

```http
GET /api/stories/{id}
Accept: application/json
```

### Create Story (Protected)

```http
POST /api/stories
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "title": "My Story Title",
  "body": "The full story content goes here...",
  "is_published": true
}
```

### Update Story (Protected)

```http
PUT /api/stories/{id}
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "title": "Updated Title",
  "body": "Updated content...",
  "is_published": true
}
```

### Delete Story (Protected)

```http
DELETE /api/stories/{id}
Authorization: Bearer {token}
Accept: application/json
```

---

## Comments Endpoints

### List Comments

```http
GET /api/stories/{story_id}/comments?per_page=20
Accept: application/json
```

### Create Comment (Protected)

```http
POST /api/stories/{story_id}/comments
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "body": "This is a great story!"
}
```

### Reply to Comment (Protected)

```http
POST /api/stories/{story_id}/comments/{comment_id}/reply
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json

{
  "body": "I agree with this comment!"
}
```

---

## Likes Endpoints

### Toggle Like (Protected)

```http
POST /api/stories/{story_id}/likes/toggle
Authorization: Bearer {token}
Accept: application/json
```

**Response (200):**
```json
{
  "success": true,
  "message": "Like toggled",
  "data": {
    "story_id": 1,
    "liked": true,
    "likes_count": 6
  }
}
```

---

## Notifications Endpoints

### Get Notifications (Protected)

```http
GET /api/notifications?per_page=20
Authorization: Bearer {token}
Accept: application/json
```

### Get Unread Count (Protected)

```http
GET /api/notifications/unread-count
Authorization: Bearer {token}
Accept: application/json
```

### Mark as Read (Protected)

```http
PUT /api/notifications/{notification_id}/read
Authorization: Bearer {token}
Accept: application/json
```

### Mark All as Read (Protected)

```http
POST /api/notifications/read-all
Authorization: Bearer {token}
Accept: application/json
```

### Delete Notification (Protected)

```http
DELETE /api/notifications/{notification_id}
Authorization: Bearer {token}
Accept: application/json
```

---

## Follow/Unfollow Endpoints

### Follow User (Protected)

```http
POST /api/users/{user_id}/follow
Authorization: Bearer {token}
Accept: application/json
```

### Unfollow User (Protected)

```http
DELETE /api/users/{user_id}/follow
Authorization: Bearer {token}
Accept: application/json
```

### Get User's Followers (Public)

```http
GET /api/users/{user_id}/followers?per_page=15
Accept: application/json
```

### Get Users Following (Public)

```http
GET /api/users/{user_id}/following?per_page=15
Accept: application/json
```

### Get Follow Counts (Public)

```http
GET /api/users/{user_id}/follow-counts
Accept: application/json
```

### Check Follow Status (Protected)

```http
GET /api/users/{user_id}/follow-status
Authorization: Bearer {token}
Accept: application/json
```

---

## Admin Endpoints

> **Required Role**: Admin only

### User Management

#### List Users

```http
GET /api/admin/users?search=name&role=member&sort=latest&per_page=15
Authorization: Bearer {admin_token}
Accept: application/json
```

#### Get User

```http
GET /api/admin/users/{user_id}
Authorization: Bearer {admin_token}
Accept: application/json
```

#### Update User Role

```http
PUT /api/admin/users/{user_id}/role
Authorization: Bearer {admin_token}
Content-Type: application/json
Accept: application/json

{
  "role": "moderator"
}
```

#### Suspend/Unsuspend User

```http
POST /api/admin/users/{user_id}/suspend
Authorization: Bearer {admin_token}
Accept: application/json
```

#### Delete User

```http
DELETE /api/admin/users/{user_id}
Authorization: Bearer {admin_token}
Accept: application/json
```

### Story Moderation

#### List All Stories (Moderation View)

```http
GET /api/admin/stories?search=term&author_id=1&is_published=true&sort=latest
Authorization: Bearer {admin_token}
Accept: application/json
```

#### Get Story Details

```http
GET /api/admin/stories/{story_id}
Authorization: Bearer {admin_token}
Accept: application/json
```

#### Update Publication Status

```http
PUT /api/admin/stories/{story_id}/status
Authorization: Bearer {admin_token}
Content-Type: application/json
Accept: application/json

{
  "is_published": false
}
```

#### Delete Story

```http
DELETE /api/admin/stories/{story_id}
Authorization: Bearer {admin_token}
Accept: application/json
```

### Soft Delete Management

#### List Deleted Stories

```http
GET /api/admin/stories/trashed?per_page=15
Authorization: Bearer {admin_token}
Accept: application/json
```

#### Restore Story

```http
POST /api/admin/stories/{story_id}/restore
Authorization: Bearer {admin_token}
Accept: application/json
```

#### Permanently Delete Story

```http
DELETE /api/admin/stories/{story_id}/force-delete
Authorization: Bearer {admin_token}
Accept: application/json
```

---

## Response Format

### Success Response

```json
{
  "success": true,
  "message": "Operation successful",
  "data": { },
  "meta": { }
}
```

### Error Response

```json
{
  "success": false,
  "message": "Error description",
  "errors": { }
}
```

### Validation Error (422)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required"]
  }
}
```

---

## Error Codes

| Code | Status | Description |
|------|--------|-------------|
| 200 | OK | Successful request |
| 201 | Created | Resource created |
| 401 | Unauthorized | Missing/invalid auth |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable | Validation error |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Server Error | Internal error |

---

## Database Schema

### Users Table
- Roles: admin, moderator, member
- Password hashed
- Soft delete support

### Stories Table
- Belongs to User
- Tracks likes/comments count
- Soft delete support

### Comments Table
- Nested via parent_id
- Tree structure support
- Belongs to Story & User

### Followers Table
- Many-to-many relationship
- Unique & self-check constraints
- Timestamps

### Notifications Table
- JSON data field
- Read tracking via read_at
- Notification types

---

## Test Credentials

### Admin
```
Email: admin@yourstory.local
Password: password123
```

### Moderator
```
Email: moderator@yourstory.local
Password: password123
```

### Members
```
Email: member1@yourstory.local
Password: password123

Email: member2@yourstory.local
Password: password123
```

---

## Testing with cURL

```bash
# Login
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@yourstory.local","password":"password123"}'

# Get stories
curl -X GET http://localhost:8080/api/stories \
  -H "Accept: application/json"

# Create story
curl -X POST http://localhost:8080/api/stories \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"title":"My Story","body":"Content...","is_published":true}'
```

---

## Features Implemented

✅ Complete API with 40+ endpoints
✅ Authentication (Sanctum tokens)
✅ Role-based access control
✅ Story CRUD with authorization
✅ Nested comments (tree structure)
✅ Like/unlike functionality
✅ Notification system with events
✅ Follow/unfollow users
✅ User management (admin)
✅ Story moderation
✅ Story soft delete & restore
✅ Search & filter capabilities
✅ Standardized JSON responses
✅ Rate limiting by endpoint type
✅ Comprehensive error handling
✅ Complete documentation

---

## Troubleshooting

### Docker Issues
```bash
docker compose logs
docker compose restart
```

### Database Issues
```bash
docker compose exec app php artisan migrate:refresh --force
docker compose exec app php artisan db:seed --force
```

### Authentication Issues
- Verify token format: `Authorization: Bearer <token>`
- Check token is from login response
- Re-login if token invalid

---

**Last Updated**: January 15, 2024
**Version**: 1.0.0
**Status**: Production Ready ✅

