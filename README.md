# BuildRent 🏗️
## An Online Platform for Construction Equipment Rental & Sales

---

## 🚀 Quick Start (Windows — Docker)

### Prerequisites
Install these two things (one-time setup):
1. **Docker Desktop** → https://www.docker.com/products/docker-desktop
2. **Git** → https://git-scm.com/download/win

---

### Step 1 — Clone or Download the Project

If you have Git installed, open **Command Prompt** or **PowerShell** and run:
```bash
git clone https://github.com/YOUR_USERNAME/buildrent.git
cd buildrent
```

Or simply **unzip** the downloaded folder and open a terminal inside it.

---

### Step 2 — Start the Application

In the project folder, run:
```bash
docker-compose up
```

Wait about 60 seconds for everything to start. You will see database logs scrolling — this is normal.

---

### Step 3 — Open the Application

| URL | What it is |
|-----|------------|
| http://localhost:8080 | Main website |
| http://localhost:8081 | Database admin (phpMyAdmin) |

---

## 🔑 Login Credentials

### Customer Account
- **Email:** `kofi@example.com`
- **Password:** `password`

### Admin Account
- **Email:** `admin@buildrent.com`
- **Password:** `password`

---

## 📁 Project Structure

```
buildrent/
├── app/
│   ├── controllers/       # AuthController, ProductController, OrderController, AdminController
│   ├── models/            # UserModel, ProductModel, OrderModel
│   └── views/
│       ├── user/          # Customer-facing pages
│       ├── admin/         # Admin dashboard pages
│       └── shared/        # Layouts (header, footer, admin layout)
├── config/
│   ├── app.php            # App constants
│   └── database.php       # PDO database connection
├── database/
│   ├── schema.sql         # All CREATE TABLE statements
│   └── seed.sql           # Demo data (equipment, users, orders)
├── public/
│   ├── index.php          # Front controller (single entry point)
│   ├── .htaccess          # URL rewriting
│   ├── css/               # style.css, admin.css
│   ├── js/                # app.js
│   └── images/products/   # Product images (add your own here)
├── docker/
│   └── apache.conf        # Apache virtual host config
├── docker-compose.yml     # Container orchestration
└── README.md              # This file
```

---

## 🛑 Stop the Application

```bash
docker-compose down
```

To also delete the database (fresh start):
```bash
docker-compose down -v
```

---

## 🌐 Pushing to GitHub

```bash
git init
git add .
git commit -m "feat: initial BuildRent application"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/buildrent.git
git push -u origin main
```

---

## 🏗️ Tech Stack

| Layer | Technology |
|-------|------------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8.2 |
| Database | MySQL 8.0 |
| Architecture | MVC (Model-View-Controller) |
| Containerisation | Docker + Docker Compose |
| Web Server | Apache 2.4 |

---

## 👤 Demo Users

| Name | Email | Password | Role |
|------|-------|----------|------|
| BuildRent Admin | admin@buildrent.com | password | Admin |
| Kofi Mensah | kofi@example.com | password | Customer |
| Ama Asante | ama@example.com | password | Customer |
| Yaw Darko | yaw@example.com | password | Customer |
