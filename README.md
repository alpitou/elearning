<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About System

This is a backend system for an e-learning platform built with Laravel.
It provides API endpoints for managing courses, materials, assignments, discussions, submissions, and reports.
The system supports token-based authentication via Sanctum and implements soft deletes and Eloquent ORM relationships for clean data management.

## Main Features
- User Authentication (Register, Login, Logout)
- Course Management (CRUD + Enroll)
- Material Upload & Download
- Assignments & Submissions
- Discussion & Replies
- Reports & Analytics
- Soft Delete, Restore, and Permanent Delete

## Installation Guide
### 1. Clone repository
   git clone https://github.com/alpitou/elearning.git

### 2. Instal dependencies
   composer install

### 3. File environment
   cp .env.example .env

### 4. Generate key
   php artisan key:generate

### 5. Setup database
   DB_DATABASE=elearning_db
   DB_USERNAME=root
   DB_PASSWORD=

### 6. Run migration
   php artisan migrate --seed

### 7. Run server
   php artisan serve
   Access: http://127.0.0.1:8000

## Roles & Example Credentials
### Role	        Email	                   Password
Dosen	        dosen@gmail.com            password
Mahasiswa	    mahasiswa@gmail.com        password

## Feature Modules
### Modul	        Fitur Utama	                    Route Prefix
Auth	        Register, Login, Logout	        /api/auth
Course	        CRUD, Enroll, Soft Delete	    /api/courses
Material	    Upload, Download, Restore	    /api/materials
Assignment	    Create, Grade, Report	        /api/assignments
Discussion	    Diskusi & Balasan	            /api/discussions
Report	        Statistik dan Analisis	        /api/reports

## Reports
### Endpoint	                    Deskripsi
GET /api/reports/courses	        Statistik jumlah mahasiswa per mata kuliah
GET /api/reports/assignments	    Statistik tugas dinilai/belum dinilai
GET /api/reports/students/{id}	    Statistik nilai & tugas mahasiswa tertentu

## Fitur Soft Delete
Semua entitas utama (courses, materials, assignments, discussions, replies) mendukung operasi berikut:
- DELETE → soft delete (pindah ke trash)
- GET /trash → lihat data terhapus
- PUT /{id}/restore → kembalikan data
- DELETE /{id}/force → hapus permanen

## Notes
Gunakan akun dosen untuk menambahkan course dan materi.
Gunakan akun mahasiswa untuk mendaftar, mengerjakan tugas, dan berdiskusi.

Pastikan semua request API menggunakan header:
Accept: application/json
Authorization: Bearer <token>

## Teknologi yang Digunakan
- Laravel 10
- Laravel Sanctum (untuk autentikasi token)
- MySQL
- PHP 8.2
- Postman (untuk pengujian API)
