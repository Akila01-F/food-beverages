# Flutter E-commerce API Documentation

## Overview
This document provides comprehensive API documentation for the Food & Beverage Laravel backend system. The API is designed for Flutter mobile app integration and follows RESTful principles with consistent JSON responses.

## Base URL
```
Production: https://your-domain.com/api/v1
Local Development: http://127.0.0.1:8000/api/v1
```

## Authentication
Most product and category endpoints are **public** and do not require authentication. Order management endpoints require authentication using Laravel Sanctum (Bearer tokens).

## Response Format
All API responses follow this consistent format:

### Success Response
```json
{
  "status": "success",
  "message": "Descriptive success message",
  "data": {...} // Actual data
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error description",
  "data": null
}
```

## Headers
Always include these headers in your requests:
```
Accept: application/json
Content-Type: application/json
```

---

## Categories API

### 1. Get All Categories
Retrieve all active categories.

**Endpoint:** `GET /categories`

**Query Parameters:**
- `search` (optional): Search categories by name or description
- `with_products_count` (optional): Include product count for each category (true/false)

**Example Request:**
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/categories?with_products_count=true" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "status": "success",
  "message": "Categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Burgers",
      "slug": "burgers",
      "description": "Delicious beef, chicken, and veggie burgers with fresh ingredients",
      "image": "categories/burgers.jpg",
      "is_active": true,
      "products_count": 5,
      "created_at": "2025-09-14T06:05:08.000000Z",
      "updated_at": "2025-09-14T06:05:08.000000Z"
    }
  ]
}
```

### 2. Get Single Category
Retrieve details of a specific category.

**Endpoint:** `GET /categories/{id}`

**Example Request:**
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/categories/1" \
  -H "Accept: application/json"
```

---

## Products API

### 1. Get All Products
Retrieve products with filtering, searching, and pagination.

**Endpoint:** `GET /products`

**Query Parameters:**
- `search` (optional): Search in product name, description, or ingredients
- `category_id` (optional): Filter by specific category ID
- `featured` (optional): Filter featured products only (true/false)
- `in_stock` (optional): Filter products in stock only (true/false)
- `min_price` (optional): Minimum price filter
- `max_price` (optional): Maximum price filter
- `spice_level` (optional): Filter by spice level (mild, medium, hot)
- `sort_by` (optional): Sort field (name, price, created_at, stock_quantity, preparation_time)
- `sort_order` (optional): Sort order (asc, desc)
- `per_page` (optional): Items per page (max 50, default 15)
- `page` (optional): Page number

**Example Request:**
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/products?category_id=1&featured=true&per_page=10&sort_by=price&sort_order=asc" \
  -H "Accept: application/json"
```

**Example Response:**
```json
{
  "status": "success",
  "message": "Products retrieved successfully",
  "data": [
    {
      "id": 2,
      "name": "Chicken Deluxe Burger",
      "slug": "chicken-deluxe-burger",
      "description": "Delicious Chicken Deluxe Burger made with fresh ingredients.",
      "price": "14.99",
      "discounted_price": null,
      "final_price": "14.99",
      "has_discount": false,
      "discount_percentage": 0,
      "formatted_price": "$14.99",
      "formatted_final_price": "$14.99",
      "stock_quantity": 56,
      "in_stock": true,
      "sku": "FB-XGRWUSDG",
      "images": ["products/V7Uf3eqIERic2HqliUzHy14B6dboDhGUDuEaBVYy.jpg"],
      "is_active": true,
      "is_featured": true,
      "ingredients": "Fresh ingredients and spices",
      "spice_level": "mild",
      "preparation_time": 14,
      "category": {
        "id": 1,
        "name": "Burgers",
        "slug": "burgers",
        "description": "Delicious beef, chicken, and veggie burgers with fresh ingredients",
        "image": "categories/burgers.jpg",
        "is_active": true,
        "created_at": "2025-09-14T06:05:08.000000Z",
        "updated_at": "2025-09-14T06:05:08.000000Z"
      },
      "created_at": "2025-09-14T06:05:14.000000Z",
      "updated_at": "2025-10-02T09:42:55.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 15,
    "total": 17,
    "from": 1,
    "to": 15
  }
}
```

### 2. Get Single Product
Retrieve details of a specific product.

**Endpoint:** `GET /products/{id}`

**Example Request:**
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/products/2" \
  -H "Accept: application/json"
```

### 3. Get Featured Products
Retrieve a limited number of featured products for homepage display.

**Endpoint:** `GET /products/featured`

**Query Parameters:**
- `limit` (optional): Number of products to return (max 20, default 10)

**Example Request:**
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/products/featured?limit=5" \
  -H "Accept: application/json"
```

### 4. Search Products
Search products by name, description, or ingredients.

**Endpoint:** `GET /products/search`

**Query Parameters:**
- `q` (required): Search query
- `per_page` (optional): Items per page (max 50, default 15)
- `page` (optional): Page number

**Example Request:**
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/products/search?q=burger&per_page=10" \
  -H "Accept: application/json"
```

### 5. Get Products by Category
Retrieve products within a specific category.

**Endpoint:** `GET /categories/{categoryId}/products`

**Query Parameters:** (Same as Get All Products)

**Example Request:**
```bash
curl -X GET "http://127.0.0.1:8000/api/v1/categories/1/products?per_page=10" \
  -H "Accept: application/json"
```

---

## Data Models

### Product Model
```typescript
interface Product {
  id: number;
  name: string;
  slug: string;
  description: string;
  price: string;
  discounted_price: string | null;
  final_price: string;
  has_discount: boolean;
  discount_percentage: number;
  formatted_price: string;
  formatted_final_price: string;
  stock_quantity: number;
  in_stock: boolean;
  sku: string;
  images: string[];
  is_active: boolean;
  is_featured: boolean;
  ingredients: string;
  spice_level: string | null;
  preparation_time: number | null;
  category: Category;
  created_at: string;
  updated_at: string;
}
```

### Category Model
```typescript
interface Category {
  id: number;
  name: string;
  slug: string;
  description: string;
  image: string;
  is_active: boolean;
  products_count?: number; // Only when with_products_count=true
  created_at: string;
  updated_at: string;
}
```

### Pagination Meta
```typescript
interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
}
```

---

## Flutter Integration Examples

### 1. Get Categories for Category List
```dart
Future<List<Category>> getCategories() async {
  final response = await http.get(
    Uri.parse('$baseUrl/categories?with_products_count=true'),
    headers: {'Accept': 'application/json'},
  );
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    return (data['data'] as List)
        .map((item) => Category.fromJson(item))
        .toList();
  }
  throw Exception('Failed to load categories');
}
```

### 2. Get Products with Pagination
```dart
Future<ProductResponse> getProducts({
  int page = 1,
  int perPage = 15,
  String? categoryId,
  bool? featured,
  String? search,
}) async {
  final params = <String, String>{
    'page': page.toString(),
    'per_page': perPage.toString(),
  };
  
  if (categoryId != null) params['category_id'] = categoryId;
  if (featured != null) params['featured'] = featured.toString();
  if (search != null && search.isNotEmpty) params['search'] = search;
  
  final uri = Uri.parse('$baseUrl/products').replace(queryParameters: params);
  final response = await http.get(uri, headers: {'Accept': 'application/json'});
  
  if (response.statusCode == 200) {
    return ProductResponse.fromJson(json.decode(response.body));
  }
  throw Exception('Failed to load products');
}
```

### 3. Get Featured Products for Homepage
```dart
Future<List<Product>> getFeaturedProducts({int limit = 10}) async {
  final response = await http.get(
    Uri.parse('$baseUrl/products/featured?limit=$limit'),
    headers: {'Accept': 'application/json'},
  );
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    return (data['data'] as List)
        .map((item) => Product.fromJson(item))
        .toList();
  }
  throw Exception('Failed to load featured products');
}
```

---

## Image URLs
Product and category images are stored relative to the Laravel storage. To display images in your Flutter app:

```dart
String getImageUrl(String imagePath) {
  return '$baseUrl/storage/$imagePath';
}
```

---

## Error Handling
Always check the response status and handle errors appropriately:

```dart
Future<T> handleApiResponse<T>(
  Future<http.Response> Function() apiCall,
  T Function(Map<String, dynamic>) fromJson,
) async {
  try {
    final response = await apiCall();
    final data = json.decode(response.body);
    
    if (response.statusCode == 200 && data['status'] == 'success') {
      return fromJson(data);
    } else {
      throw ApiException(data['message'] ?? 'Unknown error occurred');
    }
  } catch (e) {
    throw ApiException('Network error: $e');
  }
}
```

---

## Spice Levels
The API uses these spice level values:
- `mild`
- `medium` 
- `hot`
- `null` (no spice level specified)

---

## Testing Endpoints

### Start Laravel Server
```bash
cd /path/to/laravel/project
php artisan serve --host=127.0.0.1 --port=8000
```

### Test API Endpoints
```bash
# Test categories
curl -X GET "http://127.0.0.1:8000/api/v1/categories" -H "Accept: application/json"

# Test products
curl -X GET "http://127.0.0.1:8000/api/v1/products?per_page=5" -H "Accept: application/json"

# Test featured products
curl -X GET "http://127.0.0.1:8000/api/v1/products/featured?limit=3" -H "Accept: application/json"

# Test search
curl -X GET "http://127.0.0.1:8000/api/v1/products/search?q=pizza" -H "Accept: application/json"
```

---

## Performance Considerations

1. **Pagination**: Always use pagination for product lists to avoid large payloads
2. **Image Optimization**: Consider implementing image resizing/optimization on the server
3. **Caching**: Implement caching for categories and featured products
4. **Rate Limiting**: Be mindful of API rate limits in production

---

## Production Deployment Notes

1. **HTTPS**: Ensure all API calls use HTTPS in production
2. **CORS**: Configure CORS settings for your Flutter app domain
3. **Rate Limiting**: Implement rate limiting to prevent abuse
4. **Error Logging**: Monitor API errors and performance
5. **Database Optimization**: Ensure proper indexing on frequently queried fields

---

## Support

For technical issues or questions about the API, please contact the backend development team.

**API Version:** 1.0  
**Last Updated:** October 6, 2025  
**Compatible Laravel Version:** 11.x