# 🧠 String Analysis RESTful API (Laravel)

A RESTful API service built with **Laravel** that analyzes strings and stores their computed properties such as length, palindrome status, unique characters, word count, and more.

---

## 🚀 Overview

This API allows clients to submit strings for analysis and retrieve their computed properties.  
Each analyzed string is uniquely identified by its **SHA-256 hash**, and duplicate submissions are gracefully handled.

### Core Features

- 🔍 Analyze any string for multiple computed properties  
- ⚡ Store and retrieve results efficiently (SHA-256 based)  
- 🧾 Filter and query analyzed strings using both structured and natural language queries  
- 🔒 Idempotent and REST-compliant API design  
- 🧠 Smart string property computations:
  - `length`
  - `is_palindrome` (case-insensitive)
  - `unique_characters`
  - `word_count`
  - `sha256_hash`
  - `character_frequency_map`

---

## 🧩 Tech Stack

- **Framework:** Laravel 11+
- **Language:** PHP 8.2+
- **Database:** MySQL / SQLite / PostgreSQL
- **Architecture:** RESTful API
- **Tools:** Eloquent ORM, JSON API responses, Validation via Form Requests

---

## 🏗️ Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/Usenmfon/string-analyzer-service.git
cd string-analysis-service

# 🧠 String Analysis RESTful API (Laravel)

A RESTful API service built with **Laravel** that analyzes strings and stores their computed properties such as length, palindrome status, unique characters, word count, and more.

---

## 🚀 Overview

This API allows clients to submit strings for analysis and retrieve their computed properties.  
Each analyzed string is uniquely identified by its **SHA-256 hash**, and duplicate submissions are gracefully handled.

### Core Features

- 🔍 Analyze any string for multiple computed properties  
- ⚡ Store and retrieve results efficiently (SHA-256 based)  
- 🧾 Filter and query analyzed strings using both structured and natural language queries  
- 🔒 Idempotent and REST-compliant API design  
- 🧠 Smart string property computations:
  - `length`
  - `is_palindrome` (case-insensitive)
  - `unique_characters`
  - `word_count`
  - `sha256_hash`
  - `character_frequency_map`

---

## 🧩 Tech Stack

- **Framework:** Laravel 11+
- **Language:** PHP 8.2+
- **Database:** MySQL / SQLite / PostgreSQL
- **Architecture:** RESTful API
- **Tools:** Eloquent ORM, JSON API responses, Validation via Form Requests

---

🧑‍💻 Development Notes

Palindrome check is case-insensitive (spaces and punctuation included by default)

Use multibyte-safe string operations for UTF-8 characters

JSON responses follow a consistent API format

Error handling conforms to REST standards (400, 404, 409, 422)
