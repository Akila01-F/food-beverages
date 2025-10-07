# Flutter Authentication & Production API Documentation

## Overview
This document provides complete API documentation for the Food & Beverage Laravel backend, including authentication endpoints and production deployment information for Flutter mobile app integration.

## API Base URLs
```
Local Development: http://127.0.0.1:8000/api/v1
Production (Railway): https://food-beverages-production.up.railway.app/api/v1
```

## Authentication System
The API uses Laravel Sanctum for token-based authentication. All authentication tokens are Bearer tokens with a 30-day expiration.

---

## Authentication Endpoints

### 1. Register User
Create a new user account.

**Endpoint:** `POST /auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `username`: required, string, max 255 characters, unique
- `email`: required, valid email, max 255 characters, unique
- `password`: required, string, minimum 8 characters, confirmed

**Success Response (201):**
```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "is_admin": false,
      "created_at": "2025-10-07T12:00:00.000000Z"
    },
    "token": "1|abc123...",
    "token_type": "Bearer",
    "expires_in": 2592000
  }
}
```

**Error Response (422):**
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."],
    "username": ["The username has already been taken."]
  },
  "data": null
}
```

### 2. Login with Email
Authenticate user with email and password.

**Endpoint:** `POST /auth/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "is_admin": false,
      "created_at": "2025-10-07T12:00:00.000000Z"
    },
    "token": "2|def456...",
    "token_type": "Bearer",
    "expires_in": 2592000
  }
}
```

**Error Response (401):**
```json
{
  "status": "error",
  "message": "Invalid credentials",
  "data": null
}
```

### 3. Login with Username
Authenticate user with username and password.

**Endpoint:** `POST /auth/login-username`

**Request Body:**
```json
{
  "username": "johndoe",
  "password": "password123"
}
```

Response format is the same as email login.

---

## Protected Endpoints (Require Authentication)

All protected endpoints require the `Authorization` header:
```
Authorization: Bearer {token}
```

### 4. Get User Profile
Retrieve authenticated user's profile.

**Endpoint:** `GET /auth/profile`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Profile retrieved successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "johndoe",
      "email": "john@example.com",
      "is_admin": false,
      "created_at": "2025-10-07T12:00:00.000000Z",
      "updated_at": "2025-10-07T12:00:00.000000Z"
    }
  }
}
```

### 5. Update Profile
Update user profile information.

**Endpoint:** `PUT /auth/profile`

**Request Body (all fields optional):**
```json
{
  "name": "John Smith",
  "username": "johnsmith",
  "email": "johnsmith@example.com"
}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Profile updated successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Smith",
      "username": "johnsmith",
      "email": "johnsmith@example.com",
      "is_admin": false,
      "updated_at": "2025-10-07T12:30:00.000000Z"
    }
  }
}
```

### 6. Change Password
Change user's password.

**Endpoint:** `POST /auth/change-password`

**Request Body:**
```json
{
  "current_password": "oldpassword123",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Password changed successfully. Please login again.",
  "data": null
}
```

**Note:** All tokens are revoked after password change. User must login again.

### 7. Logout
Logout from current device (revoke current token).

**Endpoint:** `POST /auth/logout`

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Logged out successfully",
  "data": null
}
```

### 8. Logout from All Devices
Logout from all devices (revoke all tokens).

**Endpoint:** `POST /auth/logout-all`

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Logged out from all devices successfully",
  "data": null
}
```

### 9. Refresh Token
Refresh authentication token.

**Endpoint:** `POST /auth/refresh-token`

**Success Response (200):**
```json
{
  "status": "success",
  "message": "Token refreshed successfully",
  "data": {
    "token": "3|ghi789...",
    "token_type": "Bearer",
    "expires_in": 2592000
  }
}
```

---

## Products & Categories API

All product and category endpoints from the previous documentation remain the same. They are public endpoints and don't require authentication:

- `GET /categories` - Get all categories
- `GET /categories/{id}` - Get single category  
- `GET /products` - Get products with filtering
- `GET /products/featured` - Get featured products
- `GET /products/search` - Search products
- `GET /products/{id}` - Get single product
- `GET /categories/{id}/products` - Get products by category

Refer to the main API documentation for detailed information about these endpoints.

---

## Flutter Integration Examples

### 1. Authentication Service Class

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class AuthService {
  static const String baseUrl = 'https://food-beverages-production.up.railway.app/api/v1';
  
  String? _token;
  Map<String, dynamic>? _user;
  
  // Getters
  String? get token => _token;
  Map<String, dynamic>? get user => _user;
  bool get isAuthenticated => _token != null;
  
  // Headers with authentication
  Map<String, String> get headers => {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    if (_token != null) 'Authorization': 'Bearer $_token',
  };
  
  // Register new user
  Future<AuthResult> register({
    required String name,
    required String username,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/register'),
        headers: headers,
        body: json.encode({
          'name': name,
          'username': username,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
        }),
      );
      
      final data = json.decode(response.body);
      
      if (response.statusCode == 201 && data['status'] == 'success') {
        _token = data['data']['token'];
        _user = data['data']['user'];
        await _saveTokenLocally(_token!);
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.error(data['message'], data['errors']);
      }
    } catch (e) {
      return AuthResult.error('Network error: $e');
    }
  }
  
  // Login with email
  Future<AuthResult> loginWithEmail(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/login'),
        headers: headers,
        body: json.encode({
          'email': email,
          'password': password,
        }),
      );
      
      final data = json.decode(response.body);
      
      if (response.statusCode == 200 && data['status'] == 'success') {
        _token = data['data']['token'];
        _user = data['data']['user'];
        await _saveTokenLocally(_token!);
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.error(data['message']);
      }
    } catch (e) {
      return AuthResult.error('Network error: $e');
    }
  }
  
  // Login with username
  Future<AuthResult> loginWithUsername(String username, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/login-username'),
        headers: headers,
        body: json.encode({
          'username': username,
          'password': password,
        }),
      );
      
      final data = json.decode(response.body);
      
      if (response.statusCode == 200 && data['status'] == 'success') {
        _token = data['data']['token'];
        _user = data['data']['user'];
        await _saveTokenLocally(_token!);
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.error(data['message']);
      }
    } catch (e) {
      return AuthResult.error('Network error: $e');
    }
  }
  
  // Get user profile
  Future<AuthResult> getProfile() async {
    if (!isAuthenticated) return AuthResult.error('Not authenticated');
    
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/auth/profile'),
        headers: headers,
      );
      
      final data = json.decode(response.body);
      
      if (response.statusCode == 200 && data['status'] == 'success') {
        _user = data['data']['user'];
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.error(data['message']);
      }
    } catch (e) {
      return AuthResult.error('Network error: $e');
    }
  }
  
  // Update profile
  Future<AuthResult> updateProfile({
    String? name,
    String? username,
    String? email,
  }) async {
    if (!isAuthenticated) return AuthResult.error('Not authenticated');
    
    try {
      final Map<String, dynamic> body = {};
      if (name != null) body['name'] = name;
      if (username != null) body['username'] = username;
      if (email != null) body['email'] = email;
      
      final response = await http.put(
        Uri.parse('$baseUrl/auth/profile'),
        headers: headers,
        body: json.encode(body),
      );
      
      final data = json.decode(response.body);
      
      if (response.statusCode == 200 && data['status'] == 'success') {
        _user = data['data']['user'];
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.error(data['message'], data['errors']);
      }
    } catch (e) {
      return AuthResult.error('Network error: $e');
    }
  }
  
  // Change password
  Future<AuthResult> changePassword({
    required String currentPassword,
    required String newPassword,
    required String newPasswordConfirmation,
  }) async {
    if (!isAuthenticated) return AuthResult.error('Not authenticated');
    
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/change-password'),
        headers: headers,
        body: json.encode({
          'current_password': currentPassword,
          'password': newPassword,
          'password_confirmation': newPasswordConfirmation,
        }),
      );
      
      final data = json.decode(response.body);
      
      if (response.statusCode == 200 && data['status'] == 'success') {
        // Password changed successfully, user needs to login again
        await logout();
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.error(data['message'], data['errors']);
      }
    } catch (e) {
      return AuthResult.error('Network error: $e');
    }
  }
  
  // Logout
  Future<AuthResult> logout() async {
    if (!isAuthenticated) return AuthResult.error('Not authenticated');
    
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/logout'),
        headers: headers,
      );
      
      final data = json.decode(response.body);
      
      // Clear local data regardless of API response
      _token = null;
      _user = null;
      await _clearTokenLocally();
      
      if (response.statusCode == 200 && data['status'] == 'success') {
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.success('Logged out locally');
      }
    } catch (e) {
      // Clear local data even if network fails
      _token = null;
      _user = null;
      await _clearTokenLocally();
      return AuthResult.success('Logged out locally');
    }
  }
  
  // Logout from all devices
  Future<AuthResult> logoutAll() async {
    if (!isAuthenticated) return AuthResult.error('Not authenticated');
    
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/logout-all'),
        headers: headers,
      );
      
      final data = json.decode(response.body);
      
      // Clear local data
      _token = null;
      _user = null;
      await _clearTokenLocally();
      
      if (response.statusCode == 200 && data['status'] == 'success') {
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.success('Logged out locally');
      }
    } catch (e) {
      // Clear local data even if network fails
      _token = null;
      _user = null;
      await _clearTokenLocally();
      return AuthResult.success('Logged out locally');
    }
  }
  
  // Refresh token
  Future<AuthResult> refreshToken() async {
    if (!isAuthenticated) return AuthResult.error('Not authenticated');
    
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/refresh-token'),
        headers: headers,
      );
      
      final data = json.decode(response.body);
      
      if (response.statusCode == 200 && data['status'] == 'success') {
        _token = data['data']['token'];
        await _saveTokenLocally(_token!);
        return AuthResult.success(data['message']);
      } else {
        return AuthResult.error(data['message']);
      }
    } catch (e) {
      return AuthResult.error('Network error: $e');
    }
  }
  
  // Initialize from stored token
  Future<void> initFromStorage() async {
    _token = await _getStoredToken();
    if (_token != null) {
      await getProfile(); // Fetch user data if token exists
    }
  }
  
  // Private methods for token storage
  Future<void> _saveTokenLocally(String token) async {
    // Implement with SharedPreferences or secure storage
    // Example with SharedPreferences:
    // final prefs = await SharedPreferences.getInstance();
    // await prefs.setString('auth_token', token);
  }
  
  Future<String?> _getStoredToken() async {
    // Implement with SharedPreferences or secure storage
    // Example with SharedPreferences:
    // final prefs = await SharedPreferences.getInstance();
    // return prefs.getString('auth_token');
    return null;
  }
  
  Future<void> _clearTokenLocally() async {
    // Implement with SharedPreferences or secure storage
    // Example with SharedPreferences:
    // final prefs = await SharedPreferences.getInstance();
    // await prefs.remove('auth_token');
  }
}

// Auth result class
class AuthResult {
  final bool isSuccess;
  final String message;
  final Map<String, dynamic>? errors;
  
  AuthResult.success(this.message) : isSuccess = true, errors = null;
  AuthResult.error(this.message, [this.errors]) : isSuccess = false;
}
```

### 2. Data Models

```dart
// User model
class User {
  final int id;
  final String name;
  final String username;
  final String email;
  final bool isAdmin;
  final DateTime createdAt;
  final DateTime? updatedAt;
  
  User({
    required this.id,
    required this.name,
    required this.username,
    required this.email,
    required this.isAdmin,
    required this.createdAt,
    this.updatedAt,
  });
  
  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      username: json['username'],
      email: json['email'],
      isAdmin: json['is_admin'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: json['updated_at'] != null 
          ? DateTime.parse(json['updated_at']) 
          : null,
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'username': username,
      'email': email,
      'is_admin': isAdmin,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }
}

// Authentication data model
class AuthData {
  final User user;
  final String token;
  final String tokenType;
  final int expiresIn;
  
  AuthData({
    required this.user,
    required this.token,
    required this.tokenType,
    required this.expiresIn,
  });
  
  factory AuthData.fromJson(Map<String, dynamic> json) {
    return AuthData(
      user: User.fromJson(json['user']),
      token: json['token'],
      tokenType: json['token_type'],
      expiresIn: json['expires_in'],
    );
  }
}
```

---

## Production Deployment Information

### Railway Production URL
```
Base URL: https://food-beverages-production.up.railway.app
API Base: https://food-beverages-production.up.railway.app/api/v1
```

### Environment Configuration
The production environment uses:
- **Database**: PostgreSQL (managed by Railway)
- **File Storage**: Local file system (served via `/storage` route)
- **Authentication**: Laravel Sanctum with Bearer tokens
- **CORS**: Configured for cross-origin requests

### Image URLs in Production
Product and category images should be accessed with the full production URL:
```dart
String getImageUrl(String imagePath) {
  return 'https://food-beverages-production.up.railway.app/storage/$imagePath';
}
```

### Rate Limiting
- No rate limiting is currently implemented
- Consider implementing rate limiting in production for security

---

## Security Best Practices for Flutter Integration

### 1. Token Storage
- Use `flutter_secure_storage` package for storing authentication tokens
- Never store tokens in plain text or SharedPreferences in production
- Implement automatic token refresh logic

### 2. Network Security
- Always use HTTPS in production
- Implement certificate pinning for additional security
- Handle network timeouts gracefully

### 3. Error Handling
- Implement proper error handling for all API calls
- Show user-friendly error messages
- Log errors for debugging (without exposing sensitive data)

### 4. Token Management
- Implement automatic logout on token expiration
- Provide manual token refresh functionality
- Clear all app data on logout

---

## Testing Endpoints

### Using cURL
```bash
# Register
curl -X POST "https://food-beverages-production.up.railway.app/api/v1/auth/register" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST "https://food-beverages-production.up.railway.app/api/v1/auth/login" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Get profile (replace {token} with actual token)
curl -X GET "https://food-beverages-production.up.railway.app/api/v1/auth/profile" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token}"
```

---

## Migration Checklist

### From Localhost to Production:

1. ✅ **API Endpoints**: All endpoints implemented and tested
2. ✅ **Authentication**: Sanctum authentication configured
3. ✅ **CORS**: Cross-origin requests enabled
4. ✅ **Database**: PostgreSQL configured in production
5. ✅ **File Storage**: Images accessible via `/storage` route
6. ✅ **Error Handling**: Consistent error responses implemented

### Flutter Implementation Steps:

1. **Setup HTTP Client**: Configure base URL and headers
2. **Implement Auth Service**: Use the provided AuthService class
3. **Create Data Models**: Implement User and AuthData models
4. **Setup Token Storage**: Use flutter_secure_storage
5. **Implement UI**: Create login, register, and profile screens
6. **Add Error Handling**: Show user-friendly error messages
7. **Test Integration**: Test all auth flows with production API

---

## Support

For technical issues or questions about the API:
- **Backend Team**: Contact for server-side issues
- **API Version**: 1.0
- **Last Updated**: October 7, 2025
- **Compatible Laravel Version**: 11.x

**Production Status**: ✅ Ready for Flutter integration