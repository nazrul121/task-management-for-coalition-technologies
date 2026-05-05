
# TastFlow - Task Management System

**TastFlow** is an Task Management project built with #laravel framework. TailwindCss and JQuery.

---

## 🛠 Tech Stack

* **Backend**: Laravel 11.x
* **Frontend**: Tailwind CSS, jQuery UI, Lucide Icons
* **Database**: MySQL
* **Libraries**: DataTables.net (Server-side Integration)

---

## ⚙️ Installation & Setup

Follow these steps to set up the development environment on your local machine.

### 1. Clone the Repository
```bash
git clone [https://github.com/nazrul121/task-management-for-coalition-technologies](https://github.com/nazrul121/task-management-for-coalition-technologies)
cd task-management-for-coalition-technologies
```

### 2. Install Dependencies (optional)
Install the backend PHP packages and compile the frontend utility classes:
```bash
composer install
npm install && npm run dev
```

### 3. Environment Configuration
Copy the environment template and generate your unique application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Connection (MySQL)
Open your `.env` file and update the following credentials to connect to your local MySQL server:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flow_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Database Initialization
Create the database manually in your MySQL client, then run the migrations and seeders:
```bash
php artisan migrate --seed
```

### 6. Launch the Server
Start the Laravel development server:
```bash
php artisan serve
```
Access the application at: **http://127.0.0.1:8000**.

---

## 🚀 Key Features

### 🔹 Drag-and-Drop Reordering
The system utilizes **jQuery UI Sortable** integrated directly into the DataTable rows. 
* **Interactive Handles**: Users can drag rows via a dedicated grip icon.
* **Real-time Sync**: Every move triggers an AJAX POST request to update the `priority` column in the database.

### 🔹 Premium UI/UX Design
* **Glassmorphism**: Modern interface utilizing `backdrop-blur` and semi-transparent layers.
* **Dynamic Filtering**: Project-specific views that refresh the DataTable instantly without a page reload.
* **Processing Indicators**: Custom loading spinners and table-blur effects provide a high-end feel.

---

## 👨‍💻 Developer
Developed by **Nazrul Islam** : **+880 1749015457** : **info.n121@gmail.com**..
```
